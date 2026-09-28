<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\AddressRequest;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Auth::user()->addresses()->latest()->get();

        return view('customer.addresses.index', compact('addresses'));
    }

    public function create()
    {
        return view('customer.addresses.create');
    }

    public function store(AddressRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        DB::transaction(function () use ($data) {
            if (! empty($data['is_default']) || ! Auth::user()->addresses()->exists()) {
                Auth::user()->addresses()->update(['is_default' => false]);
                $data['is_default'] = true;
            }

            Address::create($data);
        });

        return redirect()->back()->with('success', 'Alamat berhasil ditambahkan.');
    }

    public function destroy(Address $address)
    {
        abort_unless($address->user_id === Auth::id(), 403);
        $address->delete();

        return back()->with('success', 'Alamat dihapus.');
    }
}
