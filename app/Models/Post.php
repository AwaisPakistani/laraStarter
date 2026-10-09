<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable= ['title', 'description'];
    public function scopeSearch($query, $search)
    {
            return $query->whereAny(
                ['title'],
                'like',
                "%{$search}%"
            );
    }
    public function scopeActive($query){
        return $query->where('status','active');
    }
}
