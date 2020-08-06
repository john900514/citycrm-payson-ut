<?php

namespace AnchorCMS;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Copy extends Model
{
    use CrudTrait, LogsActivity, SoftDeletes, Uuid;

    protected $table = 'copy';
    protected $fillable = [
      'page',
      'name',
      'title',
      'desc',
      'style',
      'cascade_position',
      'active'
    ];

    protected static $logFillable = true;

    public function getVerbiageforAPage($page)
    {
        $results = false;

        $record = $this->where('page', '=', $page)
            ->where('active', '=', 1)
            ->get();

        if(count($record) > 0)
        {
            $results = [];

            foreach($record as $def)
            {
                if($def->style == 'normal')
                {
                    $results[$def->name] = $def->desc;
                }
                else if($def->style == 'cascade')
                {
                    $results[$def->name] =  [
                        'title' => $def->title,
                        'img' => null,
                        'img_orientation' => $def->cascade_position,
                        'desc' => $def->desc
                    ];
                }

            }
        }

        return $results;
    }

    public function getVerbiageforAPageByName($page, $name)
    {
        $results = false;

        $record = $this->where('page', '=', $page)
            ->where('name', '=', $name)
            ->where('active', '=', 1)
            ->first();

        if(!is_null($record))
        {
            $results = $record->desc;
        }

        return $results;
    }

    public static function _getVerbiageforAPage($page)
    {
       $model = new self();

        return $model->getVerbiageforAPage($page);
    }

    public static function _getVerbiageforAPageByName($page, $name)
    {
        $model = new self();

        return $model->getVerbiageforAPageByName($page, $name);
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $user = backpack_user();
        $activity->causer_id = $user->id;
        $activity->causer_type = User::class;
    }
}
