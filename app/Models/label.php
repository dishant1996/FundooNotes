<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use HasFactory;
    protected $fillable = [

        'id', 'user_id', 'label'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function notes()
    {
        return $this->belongsToMany(User::class, 'label_notes', 'label_id', 'note_id')->withTimestamps();
    }
}


