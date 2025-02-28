@extends('layouts.app')

@php
    $page = 'setting/roles';
    $action = 'edit';
@endphp

@section('content')
    <div class="card rounded-lg shadow-sm">
        <form action="{{ route('roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-header bg-gd text-white">
                <h5 class="card-title mb-0">Edit Data</h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="name">Nama Role</label>
                    <input type="text" id="name" name="name" class="form-control form-control-sm"
                        value="{{ old('name', $role->name) }}" required>
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('roles.index') }}" class="btn btn-sm rounded-lg btn-outline-secondary">Batal <i
                        class="bi bi-x-square-fill"></i></a>
                <button type="submit" class="btn btn-sm rounded-lg btn-outline-primary">Simpan <i
                        class="bi bi-save-fill"></i></button>
            </div>
        </form>
    </div>
@endsection
