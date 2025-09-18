<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Rennokki\QueryCache\Traits\QueryCacheable;

class Role extends Model
{
    use HasFactory, QueryCacheable;
    protected $fillable = ['name', 'priority'];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($role) {
            static::flushQueryCache();
        });

        static::updated(function ($role) {
            static::flushQueryCache();
        });

        static::deleted(function ($role) {
            static::flushQueryCache();
        });
    }

    public function isAdmin(): bool
    {
        return $this->id === UserRole::ADMIN->value;
    }

    public function isNotAdmin(): bool
    {
        return !$this->isAdmin();
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
