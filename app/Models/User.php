<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use App\Jobs\RefreshSubscriptions;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ["youtube_id", "name", "avatar"];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    public function subscriptions()
    {
        return $this->belongsToMany(
            Channel::class,
            "subscriptions",
            "user_id",
            "channel_id"
        );
    }

    public function refreshSubscriptions()
    {
        RefreshSubscriptions::dispatch($this);
    }
}
