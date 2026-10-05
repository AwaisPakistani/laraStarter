<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission as SpatiePermission;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Permission extends SpatiePermission
{
        protected $fillable= ['name', 'guard_name', 'status'];
        protected function name() : Attribute
        {
            return Attribute::make(
                set: fn ($value) => strtolower($value),
            );
        }
        public function scopeSearch($query, $search)
        {
            return $query->whereAny(
                ['name'],
                'like',
                "%{$search}%"
            );
        }
        public function scopeActive($query){
            return $query->where('status','active');
        }
}
