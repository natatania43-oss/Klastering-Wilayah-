<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'jabatan',
        'no_telepon',
        'foto',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get role label in Indonesian
     */
    public function roleLabel(): string
    {
        $labels = [
            'super_admin' => 'Super Admin',
            'admin' => 'Admin',
            'pimpinan' => 'Pimpinan',
        ];

        return $labels[$this->role] ?? ucfirst($this->role);
    }

    /**
     * Get role badge class
     */
    public function roleBadgeClass(): string
    {
        $classes = [
            'super_admin' => 'danger',
            'admin' => 'primary',
            'pimpinan' => 'success',
        ];

        return $classes[$this->role] ?? 'secondary';
    }

    /**
     * Get status label
     */
    public function statusLabel(): string
    {
        return $this->is_active ? 'Aktif' : 'Nonaktif';
    }

    /**
     * Get status badge class
     */
    public function statusBadgeClass(): string
    {
        return $this->is_active ? 'success' : 'danger';
    }

    // Method untuk cek role
    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'super_admin']);
    }

    public function isPimpinan(): bool
    {
        return $this->role === 'pimpinan';
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    // Scope untuk filter
    public function scopeRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessor untuk role_label
    public function getRoleLabelAttribute(): string
    {
        return $this->roleLabel();
    }

    // Accessor untuk status_label
    public function getStatusLabelAttribute(): string
    {
        return $this->statusLabel();
    }
}