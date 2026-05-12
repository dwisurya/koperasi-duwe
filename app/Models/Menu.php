<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Models\Role;

class Menu extends Model
{
    protected $fillable = [
        'parent_id',
        'name',
        'icon',
        'route',
        'url',
        'permission',
        'order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'order' => 'integer',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'menu_role');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    public function isVisibleByUser(?User $user): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($user && $user->hasRole('Super Admin')) {
            return true;
        }

        if ($this->permission && (! $user || ! $user->can($this->permission))) {
            return false;
        }

        if ($this->roles()->exists() && (! $user || ! $user->hasAnyRole($this->roles->pluck('name')->toArray()))) {
            return false;
        }

        return true;
    }
}
