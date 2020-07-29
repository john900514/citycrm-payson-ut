<?php

namespace AnchorCMS;

use AnchorCMS\Clients;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuOptions extends Model
{
    use Uuid, SoftDeletes;

    public static function getOptions($type, $menu_name, $page_shown = 'any', string $role = '')
    {
        $results = [];

        $records = self::whereType($type)->whereMenuName($menu_name);

        if(!empty($role))
        {
            $records = $records->wherePermittedRole($role);
        }

        if($page_shown == 'any')
        {
            $records = $records->wherePageShown('any');
        }
        else
        {
            $records = $records->whereIn('page_shown', ['any', $page_shown]);

        }

        if(count($records = $records->orderBy('order', 'ASC')->get()) > 0)
        {
            $results = $records;
        }

        return $results;
    }
}
