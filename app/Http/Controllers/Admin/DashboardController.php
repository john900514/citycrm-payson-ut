<?php

namespace AnchorCMS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use AnchorCMS\Http\Controllers\Controller;

class DashboardController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        parent::__construct();
        $this->request = $request;
    }

    public function index()
    {
        $args = [
            'page' => 'dashboard'
            //'sidebar_menu' => $this->menu_options()->getOptions('sidebar')
        ];

        return view('backpack::dashboard', $args);
    }
}
