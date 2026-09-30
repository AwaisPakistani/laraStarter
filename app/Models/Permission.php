<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission as SpatiePermission;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Permission extends SpatiePermission
{
        protected $fillable= ['name'];
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
}
