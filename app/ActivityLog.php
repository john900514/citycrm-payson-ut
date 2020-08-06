<?php

namespace AnchorCMS;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Activity;

class ActivityLog extends Activity
{
    use CrudTrait;

    protected $table = 'activity_log';

    protected $casts = [
        'properties' => 'array'
    ];
}
