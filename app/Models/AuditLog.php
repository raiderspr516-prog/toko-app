<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'admin_id', 'action', 'entity_type', 'entity_id',
        'old_value', 'new_value', 'ip_address', 'user_agent',
    ];

    protected function casts(): array
    {
        return ['old_value' => 'array', 'new_value' => 'array'];
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
