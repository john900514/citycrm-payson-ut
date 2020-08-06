<?php

namespace AnchorCMS;

use AnchorCMS\Permissions;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Roles extends Model
{
    use CrudTrait, LogsActivity;

    protected $fillable = ['name', 'title', 'client_id'];

    protected static $logFillable = true;

    public function tapActivity(Activity $activity, string $eventName)
    {
        $user = backpack_user();
        $activity->causer_id = $user->id;
        $activity->causer_type = User::class;
    }

    public function getAssignedAbilities($role, $client_id = null)
    {
        $results = false;

        $record = $this->whereName($role);

        if(!is_null($client_id))
        {
            $record = $record->whereClientId($client_id);
        }

        $record = $record->first();

        if(!is_null($record))
        {
            $permissions = Permissions::whereEntityType('roles')
                ->whereEntityId($record->id)
                ->get();

            $results = [];
            if(count($permissions) > 0)
            {
                foreach ($permissions as $perm)
                {
                    $ability = $perm->ability()->first();

                    if(!is_null($ability))
                    {
                        $results[] = $ability->toArray();
                    }
                }
            }
        }

        return $results;
    }

    public function getClientAssignedRoles($client_id)
    {
        $results = false;

        $records = $this->whereClientId($client_id)->get();

        if(count($records) > 0)
        {
            $results = [];

            foreach ($records as $role)
            {
                $results[$role->name] = $role->title;
            }
        }

        return $results;
    }

    public static function getRoleTitle($role)
    {
        $results = false;

        $record = self::whereName($role)->first();

        if(!is_null($record))
        {
            $results = $record->title;
        }

        return $results;
    }

    public static function getAllRolesDropList()
    {
        $results = ['Select a User Role'];

        $records = self::all();
        $host_uuid = Clients::getHostClient();

        if(count($records) > 0)
        {

        }

        return $results;
    }

    public function client()
    {
        return $this->hasOne('AnchorCMS\Clients', 'id', 'client_id');
    }
}
