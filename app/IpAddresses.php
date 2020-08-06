<?php

namespace AnchorCMS;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IpAddresses extends Model
{
    use SoftDeletes, Uuid;

    protected $table = 'ip_addresses';

    protected $casts = [
        'ip_info' => 'array',
        'misc_info' => 'array'
    ];

    protected $guarded = [];


    public function lookUpIP($ip)
    {
        $result = false;

        if($record = $this->whereIp($ip)->first())
        {
            $result = $record;
        }

        return $result;
    }

    public function insertNew($data)
    {
        $results = false;

        $model = new $this();
        $model->ip = $data['ip'];
        $model->ip_info = $data['ip_info'];
        if(array_key_exists('source', $data)) {$model->source = $data['source'];}
        if(array_key_exists('lat', $data)) {$model->lat = $data['lat'];}
        if(array_key_exists('long', $data)) {$model->long = $data['long'];}
        if(array_key_exists('misc_info', $data)) {$model->source = $data['misc_info'];}

        if($model->save())
        {
            $results = $model;
        }

        return $results;
    }
}
