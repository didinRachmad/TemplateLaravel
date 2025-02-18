@extends('layouts.app')

@php
    $page = 'permission';
    $action = 'edit';
@endphp

@section('content')
    <div class="card shadow-sm">
        <form action="{{ route('permissions.update', $permission->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-header bg-gd text-white">
                <h5 class="card-title mb-0">Edit Data</h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="name">Nama Permission</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ $permission->name }}"
                        required>
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary">Batal <i
                        class="bi bi-x-square-fill"></i></a>
                <button type="submit" class="btn btn-outline-primary">Simpan <i class="bi bi-save-fill"></i></button>
            </div>
        </form>
    </div>
@endsection
