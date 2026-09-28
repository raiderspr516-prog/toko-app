@extends('layouts.admin')
@section('title', 'Tambah Kupon')
@section('content')
<div class="bg-white rounded-xl shadow p-6 max-w-2xl">
    <form method="POST" action="{{ route('admin.coupons.store') }}">
        @include('admin.coupons._form')
    </form>
</div>
@endsection
