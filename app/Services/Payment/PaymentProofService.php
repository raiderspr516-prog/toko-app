<?php

namespace App\Services\Payment;

use App\Events\PaymentProofUploaded;
use App\Models\Order;
use App\Models\PaymentProof;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentProofService
{
    /**
     * Simpan bukti bayar. Nama file DIBUAT ULANG (UUID) — tidak pernah
     * memakai nama file asli dari user — dan disimpan di disk 'private'
     * (tidak bisa diakses lewat URL publik langsung).
     */
    public function upload(Order $order, User $user, UploadedFile $file): PaymentProof
    {
        if (! $order->payment) {
            throw new \RuntimeException('Order ini belum punya data pembayaran.');
        }

        if (! in_array($order->status, ['waiting_payment', 'payment_review'], true)) {
            throw new \RuntimeException('Order ini tidak sedang menunggu pembayaran.');
        }

        return DB::transaction(function () use ($order, $user, $file) {
            $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('payment-proofs', $filename, 'local');

            $proof = PaymentProof::create([
                'payment_id' => $order->payment->id,
                'order_id' => $order->id,
                'user_id' => $user->id,
                'file_path' => $path,
                'status' => 'pending',
                'uploaded_at' => now(),
            ]);

            $order->payment->update(['status' => 'waiting_review']);
            $order->update(['status' => 'payment_review']);

            event(new PaymentProofUploaded($proof));

            return $proof;
        });
    }
}
