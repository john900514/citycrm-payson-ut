<?php

namespace AnchorCMS\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use Backpack\CRUD\CrudPanel;
use AnchorCMS\Http\Requests\StandardStoreRequest as StoreRequest;
use AnchorCMS\Http\Requests\StandardUpdateRequest as UpdateRequest;

/**
 * Class CopyCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class CopyCrudController extends CrudController
{
    public function setup()
    {
        $this->data['page'] = 'crud-verbiage';
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('AnchorCMS\Copy');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/crud-verbiage');
        $this->crud->setEntityNameStrings('verbiage', 'copy');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        // Columns
        $this->crud->addColumn(['name' => 'page', 'type' => 'text', 'label' => 'Page']);
        $this->crud->addColumn(['name' => 'name', 'type' => 'text', 'label' => 'Name']);
        $this->crud->addColumn(['name' => 'title', 'type' => 'text', 'label' => 'Title']);
        $this->crud->addColumn(['name' => 'desc', 'type' => 'text', 'label' => 'Description']);

        $f_page_options = [
            '/' => 'Home',
        ];

        $edit_page = [
            'name' => 'page',  // the db column name (attribute name)
            'label' => "Page", // the human-readable label for it
            'type' => 'select_from_array',
            'options' => $f_page_options
        ];

        $this->crud->addField($edit_page);
        $this->crud->addField(['name' => 'name', 'type' => 'text', 'label' => 'Name']);
        $this->crud->addField(['name' => 'title', 'type' => 'text', 'label' => 'Title']);
        $this->crud->addField([
            'name' => 'desc',
            'type' => 'tinymce',
            'label' => 'Description',
            'options' => []]);
        $this->crud->addField(['name' => 'active', 'type' => 'checkbox', 'label' => 'Active', 'default' => true]);

	    $this->crud->addField(
		    [
			    'name' => 'cascade_position',
			    'type' => 'select_from_array',
			    'label' => 'Cascade Position',
			    'options' => [null => 'NULL', 'right' => 'right', 'left' => 'left'],
			    'allows_null' => false,
			    'default' => 'one',
		    ]);

	    $this->crud->addField(
		    [
			    'name' => 'style',
			    'type' => 'select_from_array',
			    'label' => 'Style',
			    'options' => ['normal' => 'Normal', 'cascade' => 'Cascade'],
			    'allows_null' => false,
			    'default' => 'one',
		    ]);

        $f_page = [
            'type' => 'dropdown',
            'name' => 'page',
            'label' => 'Web Page'
        ];

        $this->crud->addFilter($f_page, $f_page_options, function($entry) {
            if(!empty($entry))
            {
                $this->crud->addClause('where', 'page', '=', $entry);
            }
        });

        // add asterisk for fields that are required in CopyRequest
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
}
