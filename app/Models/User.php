<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Console\Migrations\ResetCommand;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Note;
use Laravel\Sanctum\HasApiTokens;
use mysqli;
use Tymon\JWTAuth\Contracts\JWTsubject;



class User extends Authenticatable implements JWTsubject
{
    use HasApiTokens, HasFactory, Notifiable;
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function labels()
    {
        return $this->hasMany(Label::class);

        /**
         * The attributes that are mass assignable.
         *
         * @var array<int, string>
         */
    }

    public $table = 'users';
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // public function sendPasswordResetNotification($token)
    // {
    //    $this->notify(new ResetPassword($token));
    // }
}
