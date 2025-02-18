@extends('layouts.app')

@php
    $page = 'approval_routes';
    $action = 'create';
@endphp

@section('content')
    <div class="card shadow-sm p-5">
        <h2>Tambah Konfigurasi Approval</h2>

        <form action="{{ route('approval_routes.store') }}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <label for="module">Module</label>
                <select name="module" id="module" class="form-control" required>
                    <option value="">-- Pilih Module --</option>
                    @foreach ($menus as $menu)
                        <option value="{{ $menu->route }}" {{ old('module') == $menu->route ? 'selected' : '' }}>
                            {{ $menu->route }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mb-3">
                <label for="role_id">Role</label>
                <select name="role_id" id="role_id" class="form-control" required>
                    <option value="">-- Pilih Role --</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mb-3">
                <label for="sequence">Urutan Approval (Sequence)</label>
                <input type="number" name="sequence" id="sequence" class="form-control" value="{{ old('sequence') }}"
                    min="1" required>
            </div>
            <div class="form-group mb-3">
                <label for="assigned_user_id">Assigned User (Opsional)</label>
                <select name="assigned_user_id" id="assigned_user_id" class="form-control">
                    <option value="">-- Tidak Ada --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ old('assigned_user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->email }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Konfigurasi</button>
        </form>
    </div>
@endsection
