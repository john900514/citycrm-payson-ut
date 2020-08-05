<?php

namespace AnchorCMS\Http\Controllers\Admin;

use AnchorCMS\Clients;
use AnchorCMS\Departments;
use Prologue\Alerts\Facades\Alert;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use AnchorCMS\Http\Requests\StandardStoreRequest as StoreRequest;
use AnchorCMS\Http\Requests\StandardUpdateRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Silber\Bouncer\BouncerFacade as Bouncer;

/**
 * Class DepartmentsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class DepartmentsCrudController extends CrudController
{
    public function setup()
    {
        $this->data['page'] = 'crud-departments';
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('AnchorCMS\Departments');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/crud-departments');
        $this->crud->setEntityNameStrings('departments', 'departments');

        $this->qualifyAccess();
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->addColumns($this->getReadColumns());
        $this->crud->addFields($this->getCreateColumns(), 'create');
        $this->crud->addFields($this->getUpdateColumns(), 'update');

        // Removing the default button
         $this->crud->denyAccess('delete');
        // Replacing with the Custom Delete Button
        $this->crud->addButtonFromView('line', 'Remove', 'department-delete', 'last');


        // add asterisk for fields that are required in DepartmentsRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    private function qualifyAccess()
    {
        $client_id = session()->has('active_client')
            ? session()->get('active_client')
            : backpack_user()->client_id;

        $client = Clients::find($client_id);

        if(backpack_user()->can('access-all-departments', $client))
        {
            if(session()->has('active_client'))
            {
                $this->crud->addClause('where', 'client_id', '=', session()->get('active_client'));
            }

            if(session()->has('active_department'))
            {
                $this->crud->addClause('where', 'id', '=', session()->get('active_department'));
                $this->crud->addClause('orWhere', 'parent_department', '=', session()->get('active_department'));
            }
        }
        else
        {
            $this->crud->hasAccessOrFail('');
        }
    }

    public function store(StoreRequest $request)
    {
        $client_id = session()->has('active_client')
            ? session()->get('active_client')
            : backpack_user()->client_id;

        $client = Clients::find($client_id);

        if(backpack_user()->can('create-departments', $client))
        {
            $data = $request->all();

            if(!array_key_exists($client_id, $data))
            {
                $data['client_id'] = $client_id;
            }
            // your additional operations before save here

            $item = $this->crud->create($data);
            $this->data['entry'] = $this->crud->entry = $item;

            // show a success message
            \Alert::success(trans('backpack::crud.insert_success'))->flash();

            // save the redirect choice for next time
            $this->setSaveAction();

            $redirect_location = $this->performSaveAction($item->getKey());
            // your additional operations after save here
            // use $this->data['entry'] or $this->crud->entry
            return $redirect_location;
        }
        else
        {
            \Alert::error('Access Denied. You do not have permission to create new Departments for this client.')->flash();
        }

        return redirect('/crud-departments');
    }

    public function update(UpdateRequest $request)
    {
        $client_id = session()->has('active_client')
            ? session()->get('active_client')
            : backpack_user()->client_id;

        $client = Clients::find($client_id);

        if(backpack_user()->can('create-departments', $client))
        {
            $data = $request->all();

            if(!array_key_exists($client_id, $data))
            {
                $data['client_id'] = $client_id;
            }
            // your additional operations before save here

            $item = $this->crud->update($request->get($this->crud->model->getKeyName()),
                $request->except('save_action', '_token', '_method', 'current_tab', 'http_referrer'));
            $this->data['entry'] = $this->crud->entry = $item;

            // show a success message
            \Alert::success(trans('backpack::crud.insert_success'))->flash();

            // save the redirect choice for next time
            $this->setSaveAction();

            $redirect_location = $this->performSaveAction($item->getKey());
            // your additional operations after save here
            // use $this->data['entry'] or $this->crud->entry
            return $redirect_location;
        }
        else
        {
            \Alert::error('Access Denied. You do not have permission to update Departments for this client.')->flash();
        }

        return redirect('/crud-departments');
    }

    public function destroy($id)
    {
        $clients = new Clients();
        $is_host = backpack_user()->isHostUser();
        $client_id = $this->crud->model->find($id)->client_id;
        $client = $clients->find($is_host ? backpack_user()->client_id : $client_id);

        if(backpack_user()->can('delete-departments', $client))
        {
            /**
             * @todo - also delete any sub departments
             * 1. Query for all records with parent_department this record's id or skip
             * 2. For each record, delete.
             * 3. Lastly, delete the main record.
             */
            return $this->crud->delete($id);
        }
        else
        {
            //Alert::error('Access Denied.')->flash();
            return false;
        }
    }

    private function getReadColumns()
    {
        return $this->getListViewColumns();
    }

    private function getListViewColumns()
    {
        $results = [];

        $results[] = [
            'name' => 'name',
            'label' => 'Department',
            'type' => 'text'
        ];

        $results[] = [
            'name' => 'parent_department.name',
            'label' => 'Parent Department',
            'type' => 'closure',
            'function' => function ($entry) {
                $results = 'none';

                if(!is_null($entry->parent_department))
                {
                    $parent = Departments::find($entry->parent_department);

                    if(!is_null($parent))
                    {
                        $results = $parent->name;
                    }
                }

                return $results;
            }
        ];

        $results[] = [
            'name' => 'active',
            'label' => 'Active',
            'type' => 'boolean'
        ];

        return $results;
    }

    private function getCreateColumns()
    {
        $results = [];

        $results[] = [
            'name' => 'name',
            'label' => 'Department',
            'type' => 'text'
        ];

        $id  = \Route::current()->parameter('crud_department');
        $d = false;
        if($id != '')
        {
            $d = Departments::find($id);

            if(!is_null($d->parent_department))
            {
                $d = Departments::find($d->parent_department);
            }
        }

        $results[] = [
            'name' => 'parent_department',
            'label' => 'Parent Department (optional)',
            'type' => 'select2_from_array',
            'options' => Departments::getAllDepartmentsScopedDropList(($d) ? $d->id : null),
            'allows_null' => true,
            'default' => (!is_null($d) && ($d)) ? $d->id : (session()->has('active_department') ? session()->get('active_department') : '')
        ];

        $results[] = [
            'name' => 'active',
            'label' => 'Active',
            'type' => 'boolean'
        ];

        if(backpack_user()->isHostUser())
        {
            $results[] = [
                'name' => 'client_id',
                'label' => 'Assign a Client',
                'type' => 'select2_from_array',
                'options' => Clients::getAllClientsScopedDropList(),
                'default' => $this->getSomething()
            ];
        }

        return $results;
    }

    private function getSomething()
    {
        $id = \Route::current()->parameter('crud_department');

        if($id)
        {
            $dept = Departments::find($id);
            $results = $dept->client_id;
        }
        else
        {
            $dept = session()->has('active_department') ? session()->get('active_department') : '';

            if($dept != '')
            {
                $dept = Departments::find($dept);
                $results = $dept->client_id;
            }
            else
            {
                $results = '';
            }
        }
        return $results;
    }

    private function getUpdateColumns()
    {
        $results = [];

        $results[] = [
            'name' => 'name',
            'label' => 'Department',
            'type' => 'text'
        ];

        $id  = \Route::current()->parameter('crud_department');
        $d = false;
        if($id != '')
        {
            $d = Departments::find($id);

            if(!is_null($d->parent_department))
            {
                $d = Departments::find($d->parent_department);
            }
        }

        $results[] = [
            'name' => 'parent_department',
            'label' => 'Parent Department (optional)',
            'type' => 'select2_from_array',
            'options' => Departments::getAllDepartmentsScopedDropList(($d) ? $d->id : null),
            'allows_null' => true,
            'default' => (!is_null($d) && ($d)) ? $d->id : (session()->has('active_department') ? session()->get('active_department') : '')
        ];

        $results[] = [
            'name' => 'active',
            'label' => 'Active',
            'type' => 'boolean'
        ];

        if(backpack_user()->isHostUser())
        {
            $options = Clients::getAllClientsScopedDropList();
            unset($options[0]);
            $results[] = [
                'name' => 'client_id',
                'label' => 'Assign a Client',
                'type' => 'select2_from_array',
                'options' => $options,
                'default' => $this->getSomething(),
                'allows_null' => false,
            ];
        }

        return $results;
    }
}
