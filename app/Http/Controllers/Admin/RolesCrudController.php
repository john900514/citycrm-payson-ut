<?php

namespace AnchorCMS\Http\Controllers\Admin;

use AnchorCMS\Clients;
use AnchorCMS\Roles;
use Backpack\CRUD\CrudPanel;
use Prologue\Alerts\Facades\Alert;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use AnchorCMS\Http\Requests\RolesRequest as StoreRequest;
use AnchorCMS\Http\Requests\RolesRequest as UpdateRequest;

/**
 * Class RolesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class RolesCrudController extends CrudController
{

    public function setup()
    {
        $this->data['page'] = 'crud-roles';
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('AnchorCMS\Roles');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/crud-roles');
        $this->crud->setEntityNameStrings('roles', 'roles');

        $this->qualifyAccess();

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $name = [
            'name' => 'name', // the db column name (attribute name)
            'label' => "Role Name", // the human-readable label for it
            'type' => 'text' // the kind of column to show
        ];
        $title = [
            'name' => 'title', // the db column name (attribute name)
            'label' => "Role Title", // the human-readable label for it
            'type' => 'text' // the kind of column to show
        ];

        $client = [
            'name' => 'client.name',
            'label' => 'Client',
            'type' => 'text'
        ];

        $add_role_client_select = [
            'name' => 'entity_id',
            'label' => 'Assign a Client',
            'type' => 'select2_from_array',
            'options' => Clients::getAllClientsScopedDropList(),
            'default' => $this->getSomething()
        ];

        if(session()->has('active_client'))
        {
            if(!array_key_exists('attributes', $add_role_client_select))
            {
                $add_role_client_select['attributes'] = [];
            }

            $add_role_client_select['attributes']['readonly'] = 'readonly';

            if(!backpack_user()->isHostUser())
            {
                $add_role_client_select['type'] = 'hidden';
            }
        }

        $route = \Route::current()->uri();
        $mode = 'edit';
        if(strpos($route,'create') !== false)
        {
            $mode = 'create';
        }

        $abilities_box = [
            'name' => 'assignable_abilities',
            'label' => 'Assign Abilities',
            'type' => 'custom_html',
            'value' => "
                <label>Assign Abilities</label>
                <role-ability-assign
                    mode='{$mode}'
                ></role-ability-assign>
            ",
        ];

        if($mode == 'edit')
        {
            $add_role_client_select['attributes'] = [];
            $add_role_client_select['attributes']['disabled'] = 'disabled';
        }

        $column_defs = [$name, $title, $client];
        $edit_create_defs = [$name, $title ];
        $this->crud->addColumns($column_defs);
        $this->crud->addFields($edit_create_defs, 'both');

        $create_defs = [$add_role_client_select, $abilities_box];
        $this->crud->addFields($create_defs, 'create');
        $this->crud->addFields($create_defs, 'edit');
        // add asterisk for fields that are required in RolesRequest

        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    private function qualifyAccess()
    {
        /**
         * The only users that have access to this CRUD are -
         * 1. God users can see all roles, scoped and unscoped
         * 2. Admin Users can see all roles, scoped and unscoped
         * 3. Client Executives can see all roles scopes to their client
         * 4. Host users that are not gods or admins must have the create-roles ability and are scoped to their client
         * 4. Client users that are not executives must have the create-roles ability and are scoped to their client
         */
        $client_id = session()->has('active_client')
            ? session()->get('active_client')
            : backpack_user()->client_id;

        $client = Clients::find($client_id);

        if(backpack_user()->can('create-roles', $client))
        {
            if(session()->has('active_client'))
            {
                $this->crud->addClause('where', 'client_id', '=', session()->get('active_client'));
            }
        }
        else
        {
            $this->crud->hasAccessOrFail('');
        }
    }

    public function store(StoreRequest $request, Roles $role_model)
    {
        $data = $request->all();

        if(array_key_exists('client_id', $data))
        {
            $client_id = $data['client_id'];
        }
        else
        {
            $client_id = session()->has('active_client')
                ? session()->get('active_client')
                : backpack_user()->client_id;

            $data['client_id'] = $client_id;
        }

        $client = Clients::find($client_id);

        if(backpack_user()->can('create-roles', $client))
        {
            // your additional operations before save here
            //$redirect_location = parent::storeCrud();
            if(array_key_exists('name', $data))
            {
                if(is_null($data['name']))
                {
                    \Alert::error('Error - missing role name.')->flash();
                    return redirect()->back();
                }
            }

            if(array_key_exists('title', $data))
            {
                if(is_null($data['title']))
                {
                    \Alert::error('Error - missing role title.')->flash();
                    return redirect()->back();
                }
            }

            $new_role = Bouncer::role();

            $payload = [
                'name' => $data['name'],
                'title' => $data['title'],
                'client_id' => $data['entity_id'],
            ];

            foreach ($payload as $col => $val)
            {
                $new_role->$col = $val;
            }

            if($new_role->save())
            {
                $requested_abilities = explode(',', $request->all()['abilities']);

                if(count($requested_abilities) == 1 && empty($requested_abilities[0]))
                {
                    $requested_abilities[0] = $request->all()['abilities'];
                }

                foreach ($requested_abilities as $idx => $ab)
                {
                    $ability = Bouncer::ability();
                    $ability = $ability->find($ab);
                    $requested_abilities[$ability->name] = $ability;
                    unset($requested_abilities[$idx]);
                }

                $role = $data['name'];
                foreach($requested_abilities as $req_ability)
                {
                    Bouncer::allow($role)->to($req_ability, $client);
                }

                Alert::success(trans('backpack::crud.insert_success'))->flash();
            }
            else
            {
                Alert::error(trans('backpack::crud.insert_fail'))->flash();
            }
            // your additional operations after save here
            // use $this->data['entry'] or $this->crud->entry
            // show a success message
        }
        else
        {
            \Alert::error('Access Denied. You do not have permission to create new abilities for this client.')->flash();
        }


        return redirect('/crud-roles');
    }

    public function update(UpdateRequest $request, Roles $role_model)
    {
        // your additional operations before save here
        //$redirect_location = parent::updateCrud($request);
        $data = $request->all();

        if(array_key_exists('client_id', $data))
        {
            $client_id = $data['client_id'];
        }
        else
        {
            $client_id = session()->has('active_client')
                ? session()->get('active_client')
                : backpack_user()->client_id;

            $data['client_id'] = $client_id;
        }

        $client = Clients::find($client_id);

        if(backpack_user()->can('create-roles', $client))
        {
            if(array_key_exists('name', $data))
            {
                if(is_null($data['name']))
                {
                    \Alert::error('Error - missing role name.')->flash();
                    return redirect()->back();
                }
            }

            if(array_key_exists('title', $data))
            {
                if(is_null($data['title']))
                {
                    \Alert::error('Error - missing role title.')->flash();
                    return redirect()->back();
                }
            }

            $role = $this->crud->model->find($data['id']);

            $payload = [
                'name' => $data['name'],
                'title' => $data['title'],
            ];

            foreach ($payload as $col => $val)
            {
                $role->$col = $val;
            }

            if($role->save())
            {
                $requested_abilities = explode(',', $request->all()['abilities']);

                if(count($requested_abilities) == 1 && empty($requested_abilities[0]))
                {
                    $requested_abilities[0] = $request->all()['abilities'];
                }

                $temp_ab = [];
                foreach ($requested_abilities as $idx => $ab)
                {
                    $ability = Bouncer::ability();
                    $ability = $ability->find($ab);
                    $temp_ab[$ability->id] = $ability;
                }

                $requested_abilities = $temp_ab;

                $role = $request->all()['name'];
                $abilities = $role_model->getAssignedAbilities($role);

                if(count($abilities) > 0)
                {
                    // retract any abilities not in $requested_abilities
                    foreach ($abilities as $ability)
                    {
                        if(!array_key_exists($ability['id'], $requested_abilities))
                        {
                            $ab = Bouncer::ability()->find($ability['id']);
                            Bouncer::disallow($role)->to($ab);
                        }
                    }
                }

                $role = $data['name'];
                foreach($requested_abilities as $req_ability)
                {
                    Bouncer::allow($role)->to($req_ability, $client);
                }

                Alert::success(trans('backpack::crud.insert_success'))->flash();
            }
        }
        else
        {
            Alert::error('Access Denied. You do not have permission to update roles.')->flash();
        }

        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return redirect('/crud-roles');
    }

    private function getSomething()
    {
        $id = \Route::current()->parameter('crud_role');

        if($id)
        {
            $role = Bouncer::role()->find($id);
            $results = $role->client_id;
        }
        else
        {
            $results = session()->has('active_client') ? session()->get('active_client') : '';
        }
        return $results;
    }
}
