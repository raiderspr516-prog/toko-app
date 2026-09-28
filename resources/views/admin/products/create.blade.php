@extends('layouts.admin')
@section('title', 'Tambah Produk')
@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6 max-w-3xl">
    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
        @include('admin.products._form')
    </form>
</div>
@endsection
