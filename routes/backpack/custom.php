<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.
Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web'],
    'namespace'  => 'AnchorCMS\Http\Controllers',
], function () { // custom admin routes
    Route::get('/autologin', 'Auth\LoginController@autologin');
    Route::get('/login', function () {
        return redirect('/');
    });
    Route::post('/demo-login', 'Auth\LoginController@showLoginForm')->name('backpack.auth.login');
    //Route::get('/registration', 'Auth\LoginController@render_complete_registration');
});

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', 'check-admin'],
    'namespace'  => 'AnchorCMS\Http\Controllers',
], function () { // custom admin routes

    Route::get('/dashboard', 'Admin\DashboardController@index');
    Route::get('/abilities', 'Admin\InternalAdminJSONController@abilities');
    Route::get('/abilities/{role}', 'Admin\InternalAdminJSONController@role_abilities');
    Route::get('/roles/{client_id}', 'Admin\InternalAdminJSONController@client_roles');

    Route::group(['prefix' => 'push-notifications'], function () {
        Route::get('/', 'Admin\PushNotificationsController@index');
    });


    CRUD::resource('/crud-users', 'Admin\UsersCrudController');
    CRUD::resource('/crud-roles', 'Admin\RolesCrudController');
    CRUD::resource('/crud-abilities', 'Admin\AbilitiesCrudController');
    CRUD::resource('/crud-clients', 'Admin\ClientsCrudController');
    CRUD::resource('/crud-mobile-apps', 'Admin\MobileAppCrudController');
    CRUD::resource('/crud-departments', 'Admin\DepartmentsCrudController');
    CRUD::resource('/crud-images', 'Admin\ImagesCrudController');
    CRUD::resource('/crud-verbiage', 'Admin\CopyCrudController');
    CRUD::resource('/crud-data-changes', 'Admin\AuditTrailCrudController');
    CRUD::resource('/crud-visitors', 'Admin\VisitorActivityCrudController');
    Route::get('/crud-visitors/{id}/view-more', 'Admin\VisitorActivityCrudController@visitor_activity');
}); // this should be the absolute last line of this file
