<?php

namespace AnchorCMS;

use Backpack\CRUD\CrudTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Spatie\Activitylog\Models\Activity;

class Images extends Model
{
    use CrudTrait, SoftDeletes, Uuid;

    protected $fillable = [
        'page',
        'name',
        'url',
        'active',
        'schedule_start',
        'schedule_end',
    ];

    protected static $logFillable = true;

    public function getImagesforAPage($page)
    {
        $results = false;

        /*ORIGINAL QUERY*/
        //        $record = $this->where('page', '=', $page)
//            ->where('name', '=', $name)
//            ->where('active', '=', 1)
//            ->whereRaw('(schedule_start IS NULL OR ((schedule_start <= NOW()) AND (NOW() <= schedule_end)))')
//            ->get();

        $image_names = $this->select(DB::raw('DISTINCT name'))
            ->where('page', '=', $page)
            ->where('active', true)
            ->get();

        if(count($image_names) > 0)
        {
            $results = [];
            foreach($image_names as $name)
            {
                $results[$name->name] = $this->getImagesforAPageByName($page, $name->name);
            }

        }
        /*
        $records = $this->where('page', '=', $page)
            ->where('active', true)
            ->where(function ($query) {
                $query->whereNull('schedule_start')
                    ->orWhere('schedule_start', '<=', Now())
                    ->where('schedule_end', '>=', Now())
                    ->orWhereNull('schedule_end');
            })
            ->get();

        if (count($records) > 0) {
            $results = [];

            $unique = $records->unique('name');

            foreach ($unique as $def) {
                $results[$def->name] = $records->where('name', $def->name)->sortBy(function ($item) {
                    return [$item->schedule_end, $item->schedule_start];
                })->last()->url;
            }
        }
        */
        return $results;
    }

    public function getImagesforAPageByName($page, $name)
    {
        $results = false;
        /*ORIGINAL QUERY*/
        $record = [];
        $record = $this->wherePage($page)
            ->whereName($name)
            ->whereActive(1)
            ->whereNull('schedule_start')
            ->whereNull('schedule_end')
            ->first();


        $record2 = $this->wherePage($page)
            ->whereName($name)
            ->whereActive(1)
            ->whereNotNull('schedule_start')
            ->where(function($query) {
                $query->whereRaw('NOW() > schedule_start')
                    ->where(function ($query) {
                       $query->whereNull('schedule_end')
                            ->orWhereRaw('NOW() < schedule_end');
                    });
            })
            ->first();

            //->whereRaw('(schedule_start IS NULL OR ((schedule_start <= NOW()) AND (NOW() <= schedule_end)))')
            //->orderBy('schedule_start', 'desc')
            //->orderBy('schedule_end', 'desc')


        /*
        $record = Images::where('page', $page)
            ->whereName($name)
            ->whereActive(true)
            ->where(function ($query) {
                $query->orWhere(function ($query) {
                    $query->where('schedule_start', '<', Now())
                        ->where('schedule_end', '>', Now());
                });

                $query->orWhere(function ($query) {
                    $query->where('schedule_start', '<', Now())
                        ->whereNull('schedule_end');
                });

                $query->orWhere(function ($query) {
                    $query->whereNull('schedule_start')
                        ->whereNull('schedule_end');
                });
            })
            //->orderBy('schedule_end', 'desc')
            //->orderBy('schedule_start', 'desc')
            ->first();
        */

        if (!is_null($record2)) {
            $results = $record2->url;
        }
        elseif(!is_null($record))
        {
            $results = $record->url;
        }

        return $results;
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $user = backpack_user();
        $activity->causer_id = $user->id;
        $activity->causer_type = User::class;
    }
}
