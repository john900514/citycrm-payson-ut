<?php

namespace AnchorCMS\Http\Middleware;

use AnchorCMS\Clients;
use AnchorCMS\Departments;
use Closure;
use Silber\Bouncer\BouncerFacade as Bouncer;

class CheckIfAdmin
{
    /**
     * Checked that the logged in user is an administrator.
     *
     * --------------
     * VERY IMPORTANT
     * --------------
     * If you have both regular users and admins inside the same table,
     * change the contents of this method to check that the logged in user
     * is an admin, and not a regular user.
     *
     * @param [type] $user [description]
     *
     * @return bool [description]
     */
    private function checkIfUserIsAdmin($user)
    {
        // return ($user->is_admin == 1);
        if(!session()->has('active_client'))
        {
            if(!backpack_user()->isHostUser())
            {
                session()->put('active_client', backpack_user()->client_id);
            }
        }

        if(!session()->has('active_department'))
        {
            $client_id = session()->has('active_client')
                ? session()->get('active_client')
                : backpack_user()->client_id;

            $client = Clients::find($client_id);

            if(!backpack_user()->can('access-all-departments', $client))
            {
                // if the user can't view all departments, they need to be scoped to their assigned department.

                // @todo - check the user-assigned departments for the assignment
            }
            else
            {
                $depts = Departments::getDepartmentOptions();

                // If use only has one department in their client, just assign it.
                if(count($depts) == 1)
                {
                    foreach ($depts as $dept)
                    {
                        session()->put('active_department', $dept->id);
                        break;
                    }
                }
            }
        }

        return true;
    }

    /**
     * Answer to unauthorized access request.
     *
     * @param [type] $request [description]
     *
     * @return [type] [description]
     */
    private function respondToUnauthorizedRequest($request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response(trans('backpack::base.unauthorized'), 401);
        } else {
            return redirect()->guest(backpack_url('login'));
        }
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (backpack_auth()->guest()) {
            return $this->respondToUnauthorizedRequest($request);
        }

        if (!$this->checkIfUserIsAdmin(backpack_user())) {
            return $this->respondToUnauthorizedRequest($request);
        }

        return $next($request);
    }
}
