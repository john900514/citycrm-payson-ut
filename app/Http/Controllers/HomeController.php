<?php

namespace AnchorCMS\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $args = [];
        /**
         * Steps -
         * 1. Get all the copy.
         * 2. Get all the images.
         */
        return view('welcome', $args);
    }
}
