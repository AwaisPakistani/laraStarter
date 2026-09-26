<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable,HasRoles;

    protected $fillable = ['name','email','password'];
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    // public function getNameAttribute($value){
    //     return ucwords($value);
    // }
    protected function Name(): Attribute
    {
        return Attribute::make(
            get:fn($value)=>ucwords($value),//accessor
            set:fn($value)=>strtolower($value)//mutator
        );
    }
    public function hasRole($role)
    {
        return $this->roles->contains('name', $role);
    }
    public function hasPermission($permission)
    {
        return ($this->hasRole('Super Admin') ? true :  $this->roles->flatMap->permissions->contains('name', $permission));
    }

    public function scopeSearch($query, $search)
    {
        return $query->whereAny(
            ['name', 'email'],
            'like',
            "%{$search}%"
        );
    }
}
