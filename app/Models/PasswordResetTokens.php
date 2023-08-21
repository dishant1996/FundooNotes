<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResetTokens extends Model
{
    protected $fillable =[ 'id','email','user_id','token'];
    use HasFactory;

    public function user(){

        return $this->belongsTo(User::class);
    }
}
