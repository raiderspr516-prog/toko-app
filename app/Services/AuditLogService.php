<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as RequestFacade;

/**
 * Satu pintu pencatatan aksi admin. Dipanggil dari service/controller admin
 * mana pun yang melakukan perubahan penting (produk dihapus, order diubah
 * statusnya, pembayaran di-approve/reject, user dinonaktifkan, dst).
 */
class AuditLogService
{
    public function log(string $action, $entity = null, ?array $oldValue = null, ?array $newValue = null): AuditLog
    {
        return AuditLog::create([
            'admin_id' => Auth::guard('admin')->id(),
            'action' => $action,
            'entity_type' => $entity ? get_class($entity) : null,
            'entity_id' => $entity?->id,
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'ip_address' => RequestFacade::ip(),
            'user_agent' => RequestFacade::userAgent(),
        ]);
    }
}
