<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResetToken extends Model
{
    use HasFactory;
    protected $fillable = ['id','user','token','email','userid'];
    public function user(){

        return $this->belongsTo(User::class,'userid');
    }
}
