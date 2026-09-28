<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct(private AuditLogService $auditLogService) {}

    public function index(Request $request)
    {
        $query = User::withCount('orders')->withSum('orders as total_spent', 'grand_total');

        if ($request->filled('cari')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->cari.'%')
                  ->orWhere('email', 'like', '%'.$request->cari.'%');
            });
        }

        $customers = $query->latest()->paginate(15)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        $customer->load(['orders' => fn ($q) => $q->latest()->take(10)]);

        return view('admin.customers.show', compact('customer'));
    }

    /**
     * Toggle aktif/nonaktif — TIDAK PERNAH menampilkan/mengekspos password.
     */
    public function toggleStatus(User $customer)
    {
        $old = $customer->status;
        $customer->update(['status' => $customer->status === 'active' ? 'inactive' : 'active']);

        $this->auditLogService->log('toggle_customer_status', $customer, ['status' => $old], ['status' => $customer->status]);

        return back()->with('success', 'Status customer berhasil diperbarui.');
    }
}
