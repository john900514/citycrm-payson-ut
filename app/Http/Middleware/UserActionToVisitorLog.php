<?php

namespace AnchorCMS\Http\Middleware;

use Closure;
use Throwable;
use AnchorCMS\VisitorActivity;

class UserActionToVisitorLog
{
    protected $visitor_activity;

    public function __construct(VisitorActivity $act)
    {
        $this->visitor_activity = $act;
    }

    public function handle($request, Closure $next)
    {
        try
        {
            $route = $request->route()->uri();
            $ip = $request->ip();

            $payload = [
                'route' => $route,
                'ip' => $ip,
                'data' => $request->all(),
                'headers' => $request->headers->all()
            ];

            //$payload['headers'] = $_SERVER;
            $this->logVisitorActivity($payload);

        }
        catch (\Throwable $e) {
            if (env('APP_ENV') != 'local') {
                // @todo - do something here.
            }
        }

        return $next($request);
    }

    private function logVisitorActivity($data)
    {
        $args = [
            'route' => $data['route'],
            'params' => $data['data'],
            'serverData' => $data['headers']
        ];

        $this->visitor_activity->insert($args);
    }
}
