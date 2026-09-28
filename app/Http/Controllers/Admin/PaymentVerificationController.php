<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\PaymentProof;
use App\Services\Payment\PaymentVerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentVerificationController extends Controller
{
    public function __construct(private PaymentVerificationService $verificationService) {}

    public function index(Request $request)
    {
        $query = PaymentProof::with(['order.user', 'payment']);

        $status = $request->get('status', 'pending');
        if ($status) {
            $query->where('status', $status);
        }

        $proofs = $query->latest('uploaded_at')->paginate(15)->withQueryString();

        return view('admin.payments.index', compact('proofs'));
    }

    public function approve(PaymentProof $proof)
    {
        if ($proof->status !== 'pending') {
            return back()->with('error', 'Bukti bayar ini sudah diverifikasi sebelumnya.');
        }

        $this->verificationService->approve($proof, Auth::guard('admin')->user());

        return back()->with('success', 'Pembayaran disetujui — order berstatus PAID.');
    }

    public function reject(Request $request, PaymentProof $proof)
    {
        $request->validate(['reason' => ['required', 'string', 'max:255']]);

        if ($proof->status !== 'pending') {
            return back()->with('error', 'Bukti bayar ini sudah diverifikasi sebelumnya.');
        }

        $this->verificationService->reject($proof, Auth::guard('admin')->user(), $request->reason);

        return back()->with('success', 'Pembayaran ditolak — customer akan diminta upload ulang.');
    }

    /**
     * Serve file bukti bayar lewat route terautentikasi admin —
     * TIDAK pernah lewat URL publik langsung.
     */
    public function viewProof(PaymentProof $proof)
    {
        abort_unless(Storage::disk('local')->exists($proof->file_path), 404);

        return Storage::disk('local')->response($proof->file_path);
    }
}
