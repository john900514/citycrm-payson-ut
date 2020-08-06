<?php

namespace  AnchorCMS\Http\Controllers\API\Audit;

use AnchorCMS\Actions\Reporting\GetSpecificVisitorActivity;
use Illuminate\Http\Request;
use AnchorCMS\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class ActivityTrackingAPIController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function ip_activity($ip, GetSpecificVisitorActivity $action)
    {
        $results = $action->execute($ip);

        return response()->json($results);
    }
}
