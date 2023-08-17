<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;
    protected $fillable = [

        'user_id', 'title', 'body', 'remainder', 'pinned', 'archieved', 'deleted', 'index'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function labels()
    {
        return $this->belongsToMany(Label::class, 'labels_note', 'note_id', 'label_id')->withTimestamps();
    }
}
