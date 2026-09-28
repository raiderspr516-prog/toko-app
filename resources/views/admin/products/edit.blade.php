@extends('layouts.admin')
@section('title', 'Edit Produk')
@section('content')
<div class="bg-white rounded-xl shadow p-6 max-w-3xl">
    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
        @method('PUT')
        @include('admin.products._form')
    </form>
</div>
@endsection
