<?php

namespace AnchorCMS;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class Departments extends Model
{
    use CrudTrait, SoftDeletes, Uuid;

    protected $fillable = ['name', 'parent_department', 'client_id'];

    protected $casts = [
        'id' => 'uuid'
    ];

    public function child_departments()
    {
        return $this->hasMany('AnchorCMS\Departments', 'parent_department', 'id');
    }

    public static function getDepartmentOptions()
    {
        $results = [];
        $client_id = session()->has('active_client')
            ? session()->get('active_client')
            : backpack_user()->client_id;

        $client = Clients::find($client_id);

        // If a user can create departments, then they can access all departments
        if(backpack_user()->can('create-departments', $client))
        {
            $depts = self::whereClientId($client_id)
                ->whereNull('parent_department')
                ->with('child_departments')
                ->get();
        }
        else
        {

            /**
             * Steps
             * @todo - make user-assigned departments
             * 1. If user can access all departments, get all client's departments
             * 2. else if user assigned-department does not have a parent_department
             *      get the department record PLUS it's children, if any.
             * 3. else if user assigned-department has a parent_department,
             *      get just that department's parent, PLUS the child
             * 4. else, return nothing.
             */
            $depts = [];
        }

        if(count($depts) > 0)
        {
            $results = $depts;
        }

        return $results;
    }

    public static function getAllDepartmentsScopedDropList($d_id = null)
    {
        $results = [];

        $client_id = session()->has('active_client')
            ? session()->get('active_client')
            : backpack_user()->client_id;

        $client = Clients::find($client_id);

        if(session()->has('active_department'))
        {
            $dept = self::find(session()->get('active_department'));

            if(!is_null($dept))
            {
                if(!is_null($dept->parent_department))
                {
                    $depts = self::whereId($dept->parent_department)->get();
                }
                else
                {
                    $depts = [$dept];

                    if(!is_null($d_id))
                    {
                        $d = self::find($d_id);

                        $depts[] = $d;
                    }

                    $depts = collect($depts);
                }
            }
        }
        else
        {
            $depts = self::whereClientId($client_id)
                ->whereNull('parent_department')
                ->get();
        }

        if(count($depts) > 0)
        {
            foreach($depts as $dept)
            {
                $results[$dept->id] = $dept->name;
            }
        }

        return $results;
    }
}
