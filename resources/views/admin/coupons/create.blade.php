@extends('layouts.admin')
@section('title', 'Tambah Kupon')
@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6 max-w-2xl">
    <form method="POST" action="{{ route('admin.coupons.store') }}">
        @include('admin.coupons._form')
    </form>
</div>
@endsection
