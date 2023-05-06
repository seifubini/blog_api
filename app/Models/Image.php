<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $table = 'images';

    protected $fillable = ['image_url', 'post_id', 'comment_id'];

    public function posts()
    {
        return $this->belongsTo('App\Models\Post');
    }

    public function comments()
    {
        return $this->belongsTo('App\Models\Comment');
    }
}
