<?php

namespace AnchorCMS;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Silber\Bouncer\BouncerFacade as Bouncer;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class Clients extends Model
{
    use CrudTrait, Uuid, SoftDeletes;

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    protected $casts = [
        'id' => 'uuid'
    ];

    protected $fillable = ['name', 'active'];

    public static function getAllClientsDropList()
    {
        $results = ['Select a Client Account'];

        $records = self::all();

        if(count($records) > 0)
        {
            foreach ($records as $client)
            {
                $results[$client->id] = $client->name;
            }
        }

        return $results;
    }

    public static function getAllClientsScopedDropList()
    {
        $results = ['Select a Client Account'];

        $records = self::all();

        if(count($records) > 0)
        {
            foreach ($records as $client) {
                if(session()->has('active_client'))
                {
                    if($client->id == session()->get('active_client'))
                    {
                        $results[$client->id] = $client->name;
                    }
                }
                else
                {
                    $results[$client->id] = $client->name;
                }

                /*
                if(($client->uuid == $host_uuid) && (backpack_user()->client_id == $host_uuid))
                {
                    $results[$client->id] = $client->name;
                }
                elseif(backpack_user()->client_id == $client->uuid)
                {
                    $results[$client->id] = $client->name;
                }
                elseif(Bouncer::is(backpack_user())->a('god', 'admin'))
                {
                    $results[$client->id] = $client->name;
                }
                */
            }
        }

        return $results;
    }

    public static function getHostClient()
    {
        $results = false;

        $model = self::whereName(env('HOST_CLIENT'))->first();

        if(!is_null($model))
        {
            $results = $model->id;
        }

        return $results;
    }
}
