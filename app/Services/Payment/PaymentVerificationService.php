<?php

namespace App\Services\Payment;

use App\Events\PaymentApproved;
use App\Events\PaymentRejected;
use App\Models\Admin;
use App\Models\PaymentProof;
use App\Services\AuditLogService;
use Illuminate\Support\Facades\DB;

class PaymentVerificationService
{
    public function __construct(private AuditLogService $auditLogService) {}

    public function approve(PaymentProof $proof, Admin $admin): void
    {
        DB::transaction(function () use ($proof, $admin) {
            $proof->update([
                'status' => 'approved',
                'verified_at' => now(),
                'verified_by' => $admin->id,
            ]);

            $proof->payment->update(['status' => 'approved']);
            $proof->order->update(['status' => 'paid']);

            $this->auditLogService->log('approve_payment', $proof->order, ['status' => 'payment_review'], ['status' => 'paid']);
        });

        event(new PaymentApproved($proof->order->fresh()));
    }

    public function reject(PaymentProof $proof, Admin $admin, string $reason): void
    {
        DB::transaction(function () use ($proof, $admin, $reason) {
            $proof->update([
                'status' => 'rejected',
                'admin_note' => $reason,
                'verified_at' => now(),
                'verified_by' => $admin->id,
            ]);

            $proof->payment->update(['status' => 'rejected']);
            $proof->order->update(['status' => 'waiting_payment']);

            $this->auditLogService->log('reject_payment', $proof->order, ['status' => 'payment_review'], ['status' => 'waiting_payment', 'reason' => $reason]);
        });

        event(new PaymentRejected($proof->order->fresh(), $reason));
    }
}
