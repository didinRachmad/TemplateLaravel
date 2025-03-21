@extends('layouts.app')

@php
    $page = 'setting/users';
    $action = 'create';
@endphp

@section('content')
    <div class="card rounded-lg shadow-sm">
        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-header bg-gd text-white">
                <h5 class="card-title" id="addModalLabel">Tambah Data</h5>
            </div>
            <div class="card-body">
                <div class="form-group mb-3">
                    <label for="name">Nama</label>
                    <input type="text" id="name" name="name" class="form-control form-control-sm" required
                        value="{{ old('name') }}">
                </div>

                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control form-control-sm" required
                        value="{{ old('email') }}">
                </div>

                <div class="form-group mb-3">
                    <label for="produksi" class="form-label">Produksi</label>
                    <select id="produksi" name="produksi[]" class="form-select form-select-sm" multiple="multiple"
                        style="width: 100%;">
                        @foreach ($produksiList as $produksi)
                            <option value="{{ $produksi->id }}"
                                {{ collect(old('produksi'))->contains($produksi->id) ? 'selected' : '' }}>
                                {{ $produksi->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="contact">Nomor WhatsApp</label>
                    <input type="text" id="contact" name="contact" class="form-control form-control-sm"
                        placeholder="Contoh: +6281234567890" value="{{ old('contact') }}">
                </div>

                <div class="form-group mb-3">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control form-control-sm" required>
                </div>

                <div class="form-group mb-3">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="form-control form-control-sm" required>
                </div>

                <div class="form-group mb-3">
                    <label for="role_id">Role</label>
                    <select id="role_id" name="role_id" class="form-select form-select-sm" required>
                        <option value="">-- Pilih Role --</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('users.index') }}" class="btn btn-sm rounded-lg btn-outline-secondary">Batal <i
                        class="bi bi-x-square-fill"></i></a>
                <button type="submit" class="btn btn-sm rounded-lg btn-outline-primary">Simpan <i
                        class="bi bi-save-fill"></i></button>
            </div>
        </form>
    </div>
@endsection
