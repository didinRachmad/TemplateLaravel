@extends('layouts.app')

@php
    $page = 'permissions';
    $action = 'create';
@endphp

@section('content')
    <div class="card shadow-sm">
        <form action="{{ route('permissions.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-header bg-gd text-white">
                <h5 class="card-title" id="addModalLabel">Tambah Data</h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="name">Nama Permission</label>
                    <input type="text" id="name" name="name" class="form-control" required>
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
