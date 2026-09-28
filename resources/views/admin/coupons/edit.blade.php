@extends('layouts.admin')
@section('title', 'Edit Kupon')
@section('content')
<div class="bg-white rounded-xl shadow p-6 max-w-2xl">
    <form method="POST" action="{{ route('admin.coupons.update', $coupon) }}">
        @method('PUT')
        @include('admin.coupons._form')
    </form>
</div>
@endsection
