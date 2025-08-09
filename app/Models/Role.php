<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'priority'];

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
