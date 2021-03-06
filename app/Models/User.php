<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Auth;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

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

 
    public function user_skills()
    {
        return $this->hasMany('App\Models\User_skills', 'user_id', 'id');
    }

    public function friend_requests_friend()
    {
        return $this->hasOne('App\Models\User_friends', 'friend_id', 'id')->where('user_id', Auth::user()->id);
    }
    public function friend_requests_user()
    {
        return $this->hasOne('App\Models\User_friends', 'user_id', 'id')->where('friend_id', Auth::user()->id);
    }
   
    public function friend_requests_pending()
    {
        return $this->hasOne('App\Models\User_friends', 'friend_id', 'id')->where('status', 'pending');
    }

    public function friend_requests_confirmed()
    {
        return $this->hasOne('App\Models\User_friends', 'friend_id', 'id')->where('status', 'confirmed')->where('user_id', Auth::user()->id);;
    }
  
    public function friend_requests_user_confirm()
    {
        return $this->hasOne('App\Models\User_friends', 'user_id', 'id')->where('status', 'confirmed')->where('friend_id', Auth::user()->id);;
    }
}
