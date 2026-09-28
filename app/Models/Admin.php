<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_super_admin',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_super_admin' => 'boolean',
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'admin_role');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Super admin selalu punya semua permission.
     * Admin biasa dicek lewat role → permission.
     */
    public function hasPermission(string $slug): bool
    {
        if ($this->is_super_admin) {
            return true;
        }

        return $this->roles->loadMissing('permissions')
            ->pluck('permissions')
            ->flatten()
            ->pluck('slug')
            ->contains($slug);
    }

    // Relasi audit_logs akan ditambahkan di Phase 16 (Security Hardening):
    // public function auditLogs() { return $this->hasMany(AuditLog::class); }
}
