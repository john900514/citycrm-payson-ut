<?php

namespace AnchorCMS\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
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

            $extras = $_SERVER;
            $dude = env('USER');
            foreach($extras as $env => $eval)
            {
                $e = array_key_exists($env, $_ENV);
                if($e)
                {
                    unset($extras[$env]);
                }
            }

            $payload['headers'] = array_merge($payload['headers'],$extras);
            $this->logVisitorActivity($payload);

        }
        catch (\Throwable $e) {
            if (env('APP_ENV') != 'local') {
                Log::emergency('UserActionToVisitorLog - something broke. - '.$e->getMessage());
            }
        }

        return $next($request);
    }

    private function logVisitorActivity($data)
    {
        $args = [
            'route' => $data['route'],
            'params' => $data['data'],
            'serverData' => $data['headers'],
            'ip' => $data['ip'],
        ];

        $this->visitor_activity->insert($args);
    }
}
