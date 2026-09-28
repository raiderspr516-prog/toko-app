@extends('layouts.admin')
@section('title', 'Edit Kategori')
@section('content')
<div class="bg-white rounded-xl shadow p-6 max-w-xl">
    <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data">
        @method('PUT')
        @include('admin.categories._form')
    </form>
</div>
@endsection
