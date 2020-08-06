<?php

namespace  AnchorCMS\Http\Controllers\API\Audit;

use AnchorCMS\Actions\Reporting\GetSpecificVisitorLocation;
use Illuminate\Http\Request;
use AnchorCMS\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class GeoLocationAPIController extends Controller
{
    protected $clubs, $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function ip_address(GetSpecificVisitorLocation $action)
    {
        $results = $action->execute($this->request->all());

        return response($results);
    }
}
