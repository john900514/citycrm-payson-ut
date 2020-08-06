<?php

namespace AnchorCMS\Http\Controllers\Admin;

use AnchorCMS\Clients;
use AnchorCMS\VisitorActivity;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use AnchorCMS\Http\Requests\StandardStoreRequest as StoreRequest;
use AnchorCMS\Http\Requests\StandardUpdateRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class VisitorActivityCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class VisitorActivityCrudController extends CrudController
{
    public function setup()
    {
        $this->qualifyAccess();
        $this->data['page'] = 'crud-visitors';
        /**
         * |--------------------------------------------------------------------------
         * | CrudPanel Basic Information
         * |--------------------------------------------------------------------------
         */
        $this->crud->setModel('AnchorCMS\VisitorActivity');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/crud-visitors');
        $this->crud->setEntityNameStrings('realtime visitor activity', 'realtime visitor activity');
        $this->crud->enableExportButtons();
        $this->crud->orderBy('created_at', 'DESC');

        $this->crud->denyAccess('delete');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('create');


        /**
         * |--------------------------------------------------------------------------
         * | CrudPanel Configuration
         * |--------------------------------------------------------------------------
         */
        $route = [
            'name'  => 'route', // the db column name (attribute name)
            'label' => "Route/URI", // the human-readable label for it
            'type'  => 'text' // the kind of column to show
        ];

        $url_parameters = [
            'name'  => 'url_parameters', // the db column name (attribute name)
            'label' => "URL Parameters", // the human-readable label for it
            'type'  => 'text' // the kind of column to show
        ];

        $server_info = [
            'name'  => 'server_info', // the db column name (attribute name)
            'label' => "Server Info", // the human-readable label for it
            'type'  => 'text' // the kind of column to show
        ];

        $activity_type = [
            'name'  => 'activity_type', // the db column name (attribute name)
            'label' => "Activity Type/Source", // the human-readable label for it
            'type'  => 'text' // the kind of column to show
        ];

        $campaign = [
            'name'  => 'campaign', // the db column name (attribute name)
            'label' => "Campaign", // the human-readable label for it
            'type'  => 'text' // the kind of column to show
        ];

        $ip = [
            //'name'  => 'server_info.REMOTE_ADDR', // the db column name (attribute name)
            'name'  => 'ip_address', // the db column name (attribute name)
            'label' => "Location", // the human-readable label for it
            'type'  => 'closure',// the kind of column to show
            'function' => function($entry){
                    if($entry->ip_address != NULL)
                    {
                        $ip_record = $entry->ip_address()->first();
                        if(!is_null($ip_record))
                        {
                            $city = $ip_record->ip_info['city'];
                            $state = $ip_record->ip_info['region'];

                            return $city.', '.$state;
                        }
                        else {
                            return $entry->ip_address;
                        }

                    }else{
                        return $entry->ip_address;
                    }

            }
        ];

        $created_at = [
            'name'  => 'created_at',
            'label' => 'Date & Time',
            'type'  => 'timestamp',

        ];

        $column_defs = [
            $activity_type,
            $campaign,
            $route,
            $ip,
            $created_at
        ];

        $this->crud->addColumns($column_defs);

        /**
         * FILTERS START
         */

        //Route
        $routes = [
            '/',
        ];
        sort($routes);
        $this->crud->addFilter([
            'type'  => 'dropdown',
            'name'  => 'route',
            'label' => 'Route',
        ],
            $routes,
            function ($entry) use ($routes){
                $this->crud->addClause('where', 'route', '=', $routes[$entry]); // apply the "active" eloquent scope
            }
        );

        //Campaign
        $campaigns = $this->crud->model->getUniqueArrayFromColumnName('campaign');

        $this->crud->addFilter([
            'type'  => 'dropdown',
            'name'  => 'campaign',
            'label' => 'Campaign',
        ],
            $campaigns,
            function ($entry) use ($campaigns) {
                $this->crud->addClause('where', 'campaign', '=', $campaigns[$entry]); // apply the "active" eloquent scope
            }
        );

        //Activity Type
        $activity_type = $this->crud->model->getUniqueArrayFromColumnName('activity_type');

        $this->crud->addFilter([
            'type'  => 'dropdown',
            'name'  => 'activity_type',
            'label' => 'Activity Type',
        ],
            $activity_type,
            function ($entry) use ($activity_type) {
                $this->crud->addClause('where', 'activity_type', '=', $activity_type[$entry]); // apply the "active" eloquent scope
            }
        );

        //Date
        $date_range = [
                        'type' => 'date_range',
                        'name' => 'created_at',
                        'label'=> 'Date Range'
        ];
        $this->crud->addFilter($date_range, false, function($value) { // if the filter is active, apply these constraints
            $dates = json_decode($value);
            $this->crud->addClause('where', 'created_at', '>=', $dates->from);
            $this->crud->addClause('where', 'created_at', '<=', $dates->to . ' 23:59:59');
        });

        /**
         * END FILTERS
         */

        $this->crud->addButtonFromView('line', 'view-more', 'view-more-details', 'last');
        // add asterisk for fields that are required in VisitorActivityRequest
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

        if(backpack_user()->can('access-data-changes', $client))
        {

        }
        else
        {
            $this->crud->hasAccessOrFail('');
        }
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

    public function visitor_activity($record_id)
    {
        $args   = [
            'crud'  => $this->crud,
            'entry' => [], //requires a default value in case the db call fails or something
            'page' => 'crud-visitors',
            'active_bc' => 'View More Info'
        ];

        $record = $this->crud->model->find($record_id);

        if($record)
        {
            $args['entry'] = $record;
        }

        return view('city-crm.cms.pages.view-more-activity', $args);
    }
}
