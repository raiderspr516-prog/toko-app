@extends('layouts.admin')
@section('title', 'Tambah Kategori')
@section('content')
<div class="bg-white rounded-xl shadow p-6 max-w-xl">
    <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
        @include('admin.categories._form')
    </form>
</div>
@endsection
