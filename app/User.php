<?php

namespace AnchorCMS;

use AnchorCMS\Jobs\User\OnboardNewUser;
use Backpack\CRUD\CrudTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use GoldSpecDigital\LaravelEloquentUUID\Foundation\Auth\User as Authenticatable;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use AnchorCMS\Clients;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable
{
    use CausesActivity, CrudTrait, HasRolesAndAbilities, LogsActivity, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'username', 'email', 'password', 'client_id',
    ];

    protected static $logFillable = true;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function username()
    {
        return 'username'; //or return the field which you want to use.
    }

    public function client()
    {
        return $this->hasOne('AnchorCMS\Clients', 'id', 'client_id');
    }


    protected static function boot(): void
    {
        parent::boot();

        static::created(function ($user) {
            OnboardNewUser::dispatch($user, backpack_user())->onQueue('anchor-'.env('APP_ENV').'-emails');
        });
    }

    public function isHostUser()
    {
        return $this->client_id == Clients::getHostClient();
    }

    public function getActiveClient()
    {
        if(session()->has('active_client'))
        {
            $client_id = session()->get('active_client');
            $results = Clients::find($client_id)->name;
        }
        else
        {
            $results = 'All Clients';
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
