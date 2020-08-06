<?php

namespace AnchorCMS\Http\Controllers\Admin;

use AnchorCMS\Clients;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use AnchorCMS\Http\Requests\StandardStoreRequest as StoreRequest;
use AnchorCMS\Http\Requests\StandardUpdateRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class AuditTrailCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AuditTrailCrudController extends CrudController
{
    public function setup()
    {
        $this->data['page'] = 'crud-data-changes';
        $this->qualifyAccess();
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('AnchorCMS\ActivityLog');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/crud-data-changes');
        $this->crud->setEntityNameStrings('Data Change', 'Data Changes');



        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->addColumns($this->getReadColumns());

        $this->crud->allowAccess('show');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');


        // add asterisk for fields that are required in AuditTrailRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    private function getReadColumns()
    {
        $results = [];

        $results[] = [
            'name' => 'description',
            'label' => 'What Happened',
            'type' => 'text'
        ];

        $results[] = [
            'name' => 'subject_id',
            'label' => 'On What',
            'type' => 'closure',
            'function' => function($entry) {
                if((!is_null($entry->subject_id)) && (!is_null($entry->subject_type)))
                {
                    $type = "\\".$entry->subject_type;
                    $subject_model = new $type();

                    $subject = $subject_model->find($entry->subject_id);

                    if(!is_null($subject))
                    {
                        switch(class_basename($subject_model))
                        {
                            case 'User':
                                return $subject->first_name.' '.$subject->last_name;
                                break;
                            default:
                                if(array_key_exists('name', $subject->toArray()))
                                {
                                    return class_basename($subject_model).' - '.$subject->name;
                                }
                        }
                    }
                    else
                    {
                        if($entry->description == 'deleted')
                        {
                            return 'Deleted '.class_basename($subject_model).' record';
                        }
                        else if($entry->description == 'created')
                        {
                            $name = '';
                            switch(class_basename($subject_model))
                            {
                                case 'User':
                                    $name = $subject->first_name.' '.$subject->last_name;
                                    break;

                                case 'Copy':
                                    $json = $entry->properties;
                                    if(array_key_exists('title', $json['attributes']))
                                    {
                                        $name = $json['attributes']['title'];
                                    }
                                    else
                                    {
                                        $name = "Unknown";
                                    }
                                    break;

                                case 'Departments':
                                    $json = $entry->properties;
                                    if(array_key_exists('name', $json['attributes']))
                                    {
                                        $name = $json['attributes']['name'];
                                    }
                                    else
                                    {
                                        $name = "Unknown";
                                    }
                                    break;

                                default:
                                    if(array_key_exists('name', $subject->toArray()))
                                    {
                                        $name = $subject->name;
                                    }
                            }

                            return 'New '.class_basename($subject_model).' entry - '.$name;
                        }
                        else
                        {
                            return 'Unknown '.class_basename($subject_model).' record';
                        }
                    }

                }
                else {
                    return 'Not Quite Sure...';
                }

            }
        ];

        $results[] = [
            'name' => 'causer_id',
            'label' => 'By Whom?',
            'type' => 'closure',
            'function' => function($entry) {
                if((!is_null($entry->causer_id)) && (!is_null($entry->causer_type)))
                {
                    $type = "\\".$entry->causer_type;
                    $subject_model = new $type();

                    $subject = $subject_model->find($entry->causer_id);

                    if(!is_null($subject))
                    {
                        switch(class_basename($subject_model))
                        {
                            case 'User':
                                return "{$subject->first_name} {$subject->last_name}";
                                break;

                                default:
                                    if(!is_null($subject))
                                    {
                                        if(array_key_exists('name', $subject->toArray()))
                                        {
                                            return class_basename($subject_model).' - '.$subject->name;
                                        }
                                        else
                                        {
                                            return "Unknown";
                                        }
                                    }
                                    else
                                    {
                                        return "Unknown";
                                    }
                        }
                    }
                    else
                    {
                        return 'Can\'t tell...';
                    }
                }
                else {
                    return 'Was Not Logged...';
                }
            }
        ];

        $results[] = [
            'name' => 'created_at',
            'label' => 'When',
            'type' => 'text'
        ];

        $results[] = [
            'name' => 'properties',
            'label' => 'Affected',
            'type' => 'closure',
            'function' => function($entry) {
                $results = 'Unknown';

                $props = $entry->properties;

                if(count($props) > 0)
                {
                    $val = '';
                    if(array_key_exists('attributes', $props))
                    {
                        if(array_key_exists('name', $props['attributes']))
                        {
                            $val .= 'New - '.$props['attributes']['name'].'. ';
                        }
                        else
                        {
                            $val .= 'New - Unsure. ';
                        }
                    }

                    if(array_key_exists('old', $props))
                    {
                        if(array_key_exists('name', $props['old']))
                        {
                            $val .= 'Old - '.$props['old']['name'].'.';
                        }
                        else
                        {
                            $val .= 'Old - Unsure. ';
                        }
                    }

                    $results = $val;
                }
                return $results;

            }
        ];

        return $results;
    }

    private function qualifyAccess()
    {
        $client_id = session()->has('active_client')
            ? session()->get('active_client')
            : backpack_user()->client_id;

        $client = Clients::find($client_id);

        if(backpack_user()->can('access-data-changes', $client))
        {

        }
        else
        {
            $this->crud->hasAccessOrFail('');
        }
    }
}
