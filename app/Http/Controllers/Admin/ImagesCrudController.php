<?php

namespace AnchorCMS\Http\Controllers\Admin;

use AnchorCMS\Images;
use DateTime, DateTimeZone;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use AnchorCMS\Http\Requests\ImagesRequest as StoreRequest;
use AnchorCMS\Http\Requests\ImagesRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Support\Facades\Storage;

/**
 * Class ImagesCrudController
 * @package App\Http\Controllers\CMS
 * @property-read CrudPanel $crud
 */
class ImagesCrudController extends CrudController
{
    public function setup()
    {
        $this->data['page'] = 'crud-images';
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('AnchorCMS\Images');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/crud-images');
        $this->crud->setEntityNameStrings('Image', 'Images');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $page            = [
            'name'     => 'page',  // the db column name (attribute name)
            'label'    => "Page", // the human-readable label for it
            'type'     => 'text',
            'limit'    => 25,
            'priority' => 4
        ];
        $this->page_elem = $page;

        $name = [
            'name'     => 'name',  // the db column name (attribute name)
            'label'    => "Image Title", // the human-readable label for it
            'type'     => 'text',
            'limit'    => 25,
            'priority' => 1
        ];

        $status = [
            'name'     => 'active',  // the db column name (attribute name)
            'label'    => "Status", // the human-readable label for it
            'type'     => 'closure',
            'priority' => 3,
            'function' => function ($entry) {
                $results = 'Inactive';

                if ($entry->active == '1') {
                    if (is_null($entry->schedule_start)) {
                        $results = 'Active';
                    }
                    else {
                        if(is_null($entry->schedule_end))
                        {
                            $results = 'Active';
                        }
                        else
                        {
                            $results = strtotime('now') >= strtotime($entry->schedule_end) ? 'Expired on '.$entry->schedule_end : 'Active';

                            if($results == 'Active')
                            {
                                if((strtotime('now') <= strtotime($entry->schedule_start)))
                                {
                                    $results = 'Scheduled for '.$entry->schedule_start;
                                }
                            }
                            // @todo - check if schedule start AND schedule end

                            if($results == 'Expired')
                            {
                                $entry->active = 0;
                                $entry->save();
                            }
                        }
                    }
                    /*
                    else {
                        $dt           = new \DateTime();
                        $cst          = new \DateTimeZone('CST');
                        $tz           = timezone_open("CST");
                        $dateTimeOslo = date_create("now", timezone_open("UTC"));
                        if (strtotime('now') < strtotime($entry->schedule_start)) {

                            $dt->setTimestamp(strtotime($entry->schedule_start));
                            $dt->setTimezone($cst);
                            $results = 'Queued - Scheduled for <b>' . strftime('%A, %B %d, %G @ %I:%M %p', ($dt->getTimestamp() + timezone_offset_get($tz, $dateTimeOslo))) . '</b> CST';
                        } else {
                            if (strtotime('now') <= strtotime($entry->schedule_end)) {

                                $dt->setTimestamp(strtotime($entry->schedule_end));
                                $dt->setTimezone($cst);

                                $results = 'Active - Expires on <b>' . strftime('%A, %B %d, %G @ %I:%M %p', ($dt->getTimestamp() + timezone_offset_get($tz, $dateTimeOslo))) . '</b> CST';
                            } else {
                                $results = 'Expired';
                            }
                        }
                    }
                    */
                }

                return $results;
            }
        ];

        $f_page = [
            'type'  => 'dropdown',
            'name'  => 'page',
            'label' => 'Web Page'
        ];

        $f_page_options = [
            '/' => 'Home',

        ];

        (env('APP_ENV') != 'production') ? $f_page_options['test'] = 'test' : null;

        $edit_page = [
            'name'    => 'page',  // the db column name (attribute name)
            'label'   => "Page", // the human-readable label for it
            'type'    => 'select_from_array',
            'options' => $f_page_options
        ];

        $image_url = [
            'name'  => 'url',  // the db column name (attribute name)
            'label' => "Image URL", // the human-readable label for it
            'type'  => 'view',
            'view'  => 'city-crm.cms.column-views.for.articles.regarding-image-uploads'
        ];

        $image_nail = [
            'name'     => 'url',  // the db column name (attribute name)
            'label'    => 'Image to Show', // the human-readable label for it
            'type'     => 'image',
            'height'   => '100px',
            'width'    => '100px',
            'priority' => 2
        ];

        $schedule_view = [
            'name'  => 'schedule_start',  // the db column name (attribute name)
            'label' => "Scheduling", // the human-readable label for it
            'type'  => 'view',
            'view'  => 'city-crm.cms.column-views.for.images.regarding-image-scheduling'
        ];

        $active = [
            'name'  => 'active',
            'label' => 'Active',
            'type'  => 'checkbox',
            'default' => true
        ];

        $view_columns = [$page, $name, $image_nail, $status];
        $this->crud->addColumns($view_columns);

        $create_defs = [];
        $update_defs = [];
        $both_defs   = [$edit_page, $name, $image_url, $schedule_view, $active];
        $this->crud->addFields($update_defs, 'update');
        $this->crud->addFields($both_defs, 'both');
        $this->crud->addFields($create_defs, 'create');

        $this->crud->addFilter($f_page, $f_page_options, function ($entry) {
            if (!empty($entry)) {
                $this->crud->addClause('where', 'page', '=', $entry);
            }
        });

        // add asterisk for fields that are required in ImagesRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->setActionsColumnPriority(2);
        $this->crud->enableResponsiveTable();
    }

    public function store(StoreRequest $request, Images $imgs)
    {
        // your additional operations before save here
        $data = $request->all();

        $valid = $this->evaluateImage($data, $imgs);

        if($valid)
        {
            $redirect_location = parent::storeCrud($request);
            return $redirect_location;
        }
        else
        {
            return redirect(\Request::has('http_referrer') ? \Request::get('http_referrer') : $this->crud->route);
        }

        // your additional operations after save here
        /*
        if (($data['scheduleEnabled'] == 'true') || ($data['scheduleEnabled'] === true)) {

            $dt           = new \DateTime();
            $tz           = timezone_open("CST");
            $dateTimeOslo = date_create("now", timezone_open("UTC"));
            $dt->setTimestamp(strtotime($data['scheduleStart']));
            $this->data['entry']->schedule_start = date('Y-m-d H:i:s', ($dt->getTimestamp() - timezone_offset_get($tz, $dateTimeOslo)));

            if (!empty($data['scheduleEnd'])) {
                $dt->setTimestamp(strtotime($data['scheduleEnd']));
                $this->data['entry']->schedule_end = date('Y-m-d H:i:s', ($dt->getTimestamp() - timezone_offset_get($tz, $dateTimeOslo)));
            } else {
                $this->data['entry']->schedule_end = NULL;
            }


            $this->data['entry']->save();
        }
        else {
            $this->data['entry']->schedule_start = null;
            $this->data['entry']->schedule_end   = null;
            $this->data['entry']->save();
        }
        */
        // use $this->data['entry'] or $this->crud->entry

    }

    public function update(UpdateRequest $request, Images $imgs)
    {
        // your additional operations before save here
        $data = $request->all();

        $valid = $this->evaluateImage($data, $imgs);

        if($valid)
        {
            $redirect_location = parent::updateCrud($request);
            // use $this->data['entry'] or $this->crud->entry

            return $redirect_location;
        }
        else
        {
            \Alert::error('Your date range is in conflict with another <a href="">record</a>. Please deactivate it before toggling this one');
            // use $this->data['entry'] or $this->crud->entry
            return redirect(\Request::has('http_referrer') ? \Request::get('http_referrer') : url()->previous());
        }
        /*
        // your additional operations after save here
        if (($data['scheduleEnabled'] == 'true') || ($data['scheduleEnabled'] === true)) {
            $dt           = new \DateTime();
            $tz           = timezone_open("CST");
            $dateTimeOslo = date_create("now", timezone_open("UTC"));
            $dt->setTimestamp(strtotime($data['scheduleStart']));
            $this->data['entry']->schedule_start = date('Y-m-d H:i:s', ($dt->getTimestamp() - timezone_offset_get($tz, $dateTimeOslo)));

            if (!empty($data['scheduleEnd'])) {
                $dt->setTimestamp(strtotime($data['scheduleEnd']));
                $this->data['entry']->schedule_end = date('Y-m-d H:i:s', ($dt->getTimestamp() - timezone_offset_get($tz, $dateTimeOslo)));
            } else {
                $this->data['entry']->schedule_end = NULL;
            }


            $this->data['entry']->save();
        } else {
            $this->data['entry']->schedule_start = null;
            $this->data['entry']->schedule_end   = null;
            $this->data['entry']->save();
        }
        */
    }

    private function evaluateImage($data, Images $imgs)
    {
        $results = false;

        if (($data['scheduleEnabled'] == 'true') || ($data['scheduleEnabled'] === true))
        {
            // Check for another record in the same window
            if($data['active'] == 1)
            {
                $imgs = $imgs->wherePage($data['page'])->whereName($data['name'])->whereActive(1)
                    ->whereNotNull('schedule_start')
                    ->get();

                if(count($imgs) > 0)
                {
                    $results = true;
                    foreach($imgs as $img)
                    {
                        $caught = false;
                        if(array_key_exists('id', $data) && $data['id'] == $img->id)
                        {
                            continue;
                        }
                        else
                        {
                            if((!is_null($data['schedule_start'])) && (!is_null($data['schedule_end'])))
                            {
                                // schedule_start IS NOT NULL and schedule_end IS NULL
                                $caught = is_null($img->schedule_end);

                                if(!$caught)
                                {
                                    // schedule_start IS NOT NULL and schedule_end occurs after this.schedule.start
                                    $caught = strtotime($data['schedule_start']) < strtotime($img->schedule_end);
                                }

                                if(!$caught)
                                {
                                    // schedule_start IS NOT NULL and schedule_end occurs after this.schedule_end
                                    $caught = strtotime($data['schedule_end']) < strtotime($img->schedule_end);
                                }

                            }
                            else /* if((!is_null($data['schedule_start']))) */
                            {
                                // schedule_start IS NOT NULL and schedule_end IS NULL
                                $caught = is_null($img->schedule_end);

                                if(!$caught)
                                {
                                    // schedule_start occurs before this.schedule_start and schedule_end occurs after this.schedule.start
                                    $caught = strtotime($img->schedule_start) > strtotime($data['schedule_start']);

                                    if(!$caught)
                                    {
                                        // schedule_end IS NOT NULL and this.schedule_start occurs after schedule_start
                                        $caught = strtotime($data['schedule_end']) < strtotime($img->schedule_end);
                                    }
                                }
                            }

                            if($caught)
                            {
                                \Alert::error('Your date range is in conflict with another <a href="' . backpack_url('image-mgnt') . '/' . $img->id . '/edit">record</a>. Please deactivate it before toggling this one')->flash();
                                $results = false;
                                break;
                            }
                        }

                    }
                }
                else
                {
                    // In the clear, no other records in our way!
                    $results = true;
                }
            }
            else
            {
                // @todo - this below
                // if another record exists but the record being saved in not active
                // Allow the save but warn that it cannot be activated

                //else normal save
                $results = true;
            }
        }
        else
        {
            if($data['active'] == 1)
            {
                // if an unscheduled Active Image exists, yell at the user.
                $img = $imgs->wherePage($data['page'])->whereName($data['name'])->whereActive(1)
                    ->whereNull('schedule_start')
                    ->whereNull('schedule_end')
                    ->first();
                if(!is_null($img))
                {
                    \Alert::error('Cannot have more than one active unscheduled image. Please deactivate the <a href="' . backpack_url('image-mgnt') . '/' . $img->id . '/edit">previous</a> image and try again')->flash();
                }
                else
                {
                    $results = true;
                }
            }
            else
            {
                $results = true;
            }
        }

        return $results;
    }

    public function upload_img()
    {
        $results = ['success' => false, 'reason' => 'No File Uploaded.'];
        $status  = 401;

        $data = $this->request->all();

        /**
         * Steps
         * 1. Upload the image to S3 or fail
         * 2. Return the URL of the Image.
         */
        if (array_key_exists('file', $data)) {
            if (!(Storage::disk('s3-assets')->exists($data['file']->getClientOriginalName()))) {
                Storage::disk('s3-assets')->put($data['file']->getClientOriginalName(), $data['file']->get(), 'public');
            }

            $url = Storage::disk('s3-assets')->url($data['file']->getClientOriginalName());

            if (!empty($url)) {
                $results = ['success' => true, 'url' => $url];
            } else {
                $results['reason'] = 'File could not be uploaded. Please Try Again';

            }

            $status = 200;
        }


        return response($results, $status);
    }
}
