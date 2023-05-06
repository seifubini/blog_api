<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected $fillable = ['title', 'content', 'category_id'];

    public function iamges()
    {
       return $this->hasMany('App\Models\Image');
    }

    public function categories()
    {
        return $this->belongsTo('App\Models\Category');
    }
}
