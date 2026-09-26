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
                get: fn ($value) => ucfirst($value),
                set: fn ($value) => strtolower($value),
            );
        }
}
