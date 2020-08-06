<?php

namespace AnchorCMS;

use Backpack\CRUD\CrudTrait;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;

class VisitorActivity extends Model
{
    use CrudTrait, SoftDeletes, Uuid;

    protected $table = 'visitor_activity';

    protected $fillable = [
        'route',
        'url_parameters',
        'activity_type',
        'server_info',
        'campaign',
    ];

    protected $casts = [
        'url_parameters' => 'array',
        'misc_info' => 'array',
        'id' => 'uuid'
    ];

    public function insert($data)
    {
        $results = false;

        $model                 = new $this();
        $model->route          = $data['route'];
        $model->url_parameters = $data['params'];
        $model->activity_type  = $this->activityType($data['params']);
        $model->campaign       = $this->campaign($data['params']);
        $model->ip_address     = $data['serverData']['REMOTE_ADDR'];

        $model->misc_info    = $data['serverData'];

        if(!backpack_auth()->guest())
        {
            $model->user_id = backpack_user()->id;
        }

        if($model->save())
        {
            $results = $model;
        }

        return $results;
    }

    private function activityType($data)
    {
        switch (true) {
            case empty($data):
                $results = 'Organic Visit';
                break;

            case array_key_exists('utm_source', $data):
                $results = 'UTM Redirect';
                break;

            default:
                $results = 'Unknown';
        }
        return $results;
    }

    public function campaign($data)
    {
        switch (true) {
            case array_key_exists('utm_campaign', $data):
                $results = $data['utm_campaign'];
                break;

            default:
                $results = 'Unknown/None';
        }
        return $results;
    }

    public function getUniqueArrayFromColumnName($column_header)
    {
        $data  = self::all($column_header);
        $array = [];

        foreach ($data as $k => $v) {
            $array[$v->$column_header] = ucfirst($v->$column_header);
        }

        $collection = collect($array)->unique();

        return $collection->toArray();
    }


    public function ip_address()
    {
        return $this->hasOne('AnchorCMS\IpAddresses', 'ip', 'ip');
    }

    public function getTotalVisitCountByIp($ip)
    {
        $results = 0;

        $records = $this->whereIpAddress($ip)->get();

        if(count($records) > 0)
        {
            $results = count($records);
        }
        else
        {
            $fucked_up_record = $this->selectRaw('
                    JSON_EXTRACT(misc_info, \'$.REMOTE_ADDR\') as ip_address,
                    count( JSON_EXTRACT(misc_info, \'$.REMOTE_ADDR\') ) as total
                ')->whereRaw('JSON_EXTRACT(misc_info, \'$.REMOTE_ADDR\') = "'.$ip.'"')
                ->groupBy(DB::raw('JSON_EXTRACT(misc_info, \'$.REMOTE_ADDR\')'))
                ->first();

            if(!is_null($fucked_up_record))
            {
                $results = $fucked_up_record->total;
            }
        }

        return $results;
    }

    public function getTotalDailyVisitCountByIp($ip)
    {
        $results = 0;

        $fucked_up_records = $this->selectRaw('
                    DATE(created_at) as logged,
                    count(*) as total
                ')->whereIpAddress($ip)
            ->groupBy(DB::raw('ip_address, DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'), 'ASC')
            ->get();

        if(count($fucked_up_records) > 0)
        {
            $results = $fucked_up_records->toArray();
        }
        else
        {
            try {
                $fucked_up_records = $this->selectRaw('
                    DATE(created_at) as logged,
                    count(*) as total
                ')->whereRaw('JSON_EXTRACT(misc_info, \'$.REMOTE_ADDR\') = "'.$ip.'"')
                    ->groupBy(DB::raw('JSON_EXTRACT(misc_info, \'$.REMOTE_ADDR\'), DATE(created_at)'))
                    ->orderBy(DB::raw('DATE(created_at)'), 'ASC')
                    ->get();
            }
            catch(\Exception $e)
            {
                $fucked_up_records = null;
            }

            if(!is_null($fucked_up_records))
            {
                $results = [];
                if(count($fucked_up_records) > 0)
                {
                    $results = $fucked_up_records->toArray();
                }
            }
        }

        return $results;
    }

    public function getVisitorLogByIp($ip)
    {
        $results = [];

        $records = $this->whereIpAddress($ip)
            ->orderBy('created_at', 'ASC')
            ->get();

        if(count($records) > 0)
        {
            $results = $records;
        }
        else
        {
            $records = $this->whereRaw('JSON_EXTRACT(misc_info, \'$.REMOTE_ADDR\') = "'.$ip.'"')
                ->orderBy(DB::raw('created_at'), 'ASC')
                ->get();

            if(count($records) > 0)
            {
                $results = $records;
            }
        }

        return $results;
    }
}
