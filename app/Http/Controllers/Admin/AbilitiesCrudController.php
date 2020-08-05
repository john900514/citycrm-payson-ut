<?php

namespace AnchorCMS\Http\Controllers\Admin;

use AnchorCMS\Abilities;
use AnchorCMS\Clients;
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
class AbilitiesCrudController extends CrudController
{
    public function setup()
    {
        $this->data['page'] = 'crud-abilities';
        /*
                |--------------------------------------------------------------------------
                | CrudPanel Basic Information
                |--------------------------------------------------------------------------
                */
        $this->crud->setModel('AnchorCMS\Abilities');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/crud-abilities');
        $this->crud->setEntityNameStrings('ability', 'abilities');

        $this->qualifyAccess();

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $name = [
            'name' => 'name', // the db column name (attribute name)
            'label' => "Ability Name", // the human-readable label for it
            'type' => 'text' // the kind of column to show
        ];
        $title = [
            'name' => 'title', // the db column name (attribute name)
            'label' => "Ability Title", // the human-readable label for it
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
            'default' => session()->has('active_client') ? session()->get('active_client') : '',
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

        $column_defs = [$name, $title, $client];
        $add_edit_defs = [$name, $title, $add_role_client_select];

        $add_role_client_select['attributes']['disabled'] = 'disabled';
        $edit_defs = [$name, $title, $add_role_client_select];

        if(backpack_user()->isHostUser())
        {
            $add_role_client_owner = [
                'name' => 'client_id',
                'label' => 'Client Ownership',
                'type' => 'select2_from_array',
                'options' => Clients::getAllClientsDropList(),
            ];

            $add_role_client_owner['attributes']['disabled'] = 'disabled';
            $edit_defs[] = $add_role_client_owner;
        }

        $this->crud->addColumns($column_defs);
        $this->crud->addFields($edit_defs, 'update');
        $this->crud->addFields($add_edit_defs, 'create');
        // add asterisk for fields that are required in RolesRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    private function qualifyAccess()
    {
        /**
         * The only users that have access to this CRUD are -
         * 1. God users can see all abilities, scoped and unscoped
         * 2. Admin Users can see all abilities, scoped and unscoped
         * 3. Client Executives can see all abilities scopes to their client
         * 4. Host users that are not gods or admins must have the create-abilities ability and are scoped to their client
         * 4. Client users that are not executives must have the create-abilities ability and are scoped to their client
         */
        $client_id = session()->has('active_client')
            ? session()->get('active_client')
            : backpack_user()->client_id;

        $client = Clients::find($client_id);

        if(backpack_user()->can('create-abilities', $client))
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

    public function store(StoreRequest $request)
    {
        $data = $request->all();

        //dd($data);

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

        if(backpack_user()->can('create-abilities', $client))
        {
            // your additional operations before save here
            //$redirect_location = parent::storeCrud();
            $new_ability = Bouncer::ability();
            $payload = [
                'name' => $data['name'],
                'title' => $data['title'],
                'entity_id' => $data['entity_id'],
                'entity_type' => Clients::class,
                'client_id' => $data['client_id']
            ];

            foreach ($payload as $col => $val)
            {
                $new_ability->$col = $val;
            }

            // if assigned client is Host, then client cannot assign abilities to another client
            $host_id = Clients::getHostClient();
            if($payload['entity_id'] == $host_id)
            {
                if($payload['client_id'] != $host_id)
                {
                    \Alert::error('Clients cannot own abilities to manage other clients')->flash();
                    return redirect('/crud-abilities');
                }
            }


            if($new_ability->save())
            {
                //$new_ability->client_id = $request->all()['client_id'];
                //$new_ability->save();

                \Alert::success(trans('backpack::crud.insert_success'))->flash();
            }
            else
            {
                \Alert::error(trans('backpack::crud.insert_fail'))->flash();
            }
            // your additional operations after save here
            // use $this->data['entry'] or $this->crud->entry
            // show a success message
        }
        else
        {
            \Alert::error('Access Denied. You do not have permission to create new abilities for this client.')->flash();
        }

        return redirect('/crud-abilities');
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
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

        if(backpack_user()->can('create-abilities', $client))
        {
            // your additional operations before save here
            //$redirect_location = parent::storeCrud();
            $new_ability = Abilities::find($data['id']);

            if($new_ability)
            {
                $new_ability->name = $data['name'];
                $new_ability->title = $data['title'];
                $new_ability->save();

                \Alert::success(trans('backpack::crud.insert_success'))->flash();
            }
            else
            {
                \Alert::error(trans('backpack::crud.insert_fail'))->flash();
            }
            // your additional operations after save here
            // use $this->data['entry'] or $this->crud->entry
            // show a success message
        }
        else
        {
            \Alert::error('Access Denied. You do not have permission to create new abilities.')->flash();
        }
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return redirect('/crud-abilities');
    }
}
