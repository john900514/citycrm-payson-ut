<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use AnchorCMS\Departments;

Route::get('/', 'HomeController@index');

Route::get('/home', function () {
    return redirect('dashboard');
});

Route::get('/switch/{client_id}', function ($client_id) {
    if(backpack_user()->isHostUser()) {
        if($client_id == 'all')
        {
            session()->forget('active_client');
            session()->forget('active_department');
        }
        else
        {
            session()->put('active_client', $client_id);
            if(session()->has('active_department'))
            {
                $dept_id = session()->get('active_department');
                $dept_record = Departments::find($dept_id);

                if($dept_record->client_id != session()->get('active_client'))
                {
                    session()->forget('active_department');
                }
            }
        }
    }

    return redirect(url()->previous());
});

Route::get('/dept-switch/{dept_id}', function ($dept_id) {
    if($dept_id == 'all')
    {
        session()->forget('active_department');
    }
    else
    {
        session()->put('active_department', $dept_id);
    }

    return redirect(url()->previous());
});

Route::get('/registration',  'UserRegistrationController@render_complete_registration');
Route::post('/registration', 'UserRegistrationController@complete_registration');
