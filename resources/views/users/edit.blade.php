@extends('layouts.app')

@php
    $page = 'users';
    $action = 'edit';
@endphp

@section('content')
    <div class="card shadow-sm">
        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-header bg-gd text-white">
                <h5 class="card-title mb-0">Edit Data</h5>
            </div>
            <div class="card-body">
                <!-- Nama -->
                <div class="form-group mb-3">
                    <label for="name">Nama</label>
                    <input type="text" id="name" name="name" class="form-control"
                        value="{{ old('name', $user->name) }}" required>
                </div>

                <!-- Email -->
                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email" class="form-control"
                        value="{{ old('email', $user->email) }}" required>
                </div>

                <!-- Produksi -->
                <div class="form-group mb-3">
                    <label for="produksi" class="form-label">Produksi</label>
                    <select id="produksi" name="produksi" class="form-select" style="width: 100%;">
                        <option value="" {{ old('produksi', $user->produksi) == '' ? 'selected' : '' }}>Pilih
                            Produksi</option>
                        <option value="P1" {{ old('produksi', $user->produksi) == 'P1' ? 'selected' : '' }}>P1</option>
                        <option value="P2" {{ old('produksi', $user->produksi) == 'P2' ? 'selected' : '' }}>P2</option>
                    </select>
                </div>

                <!-- Contact -->
                <div class="form-group mb-3">
                    <label for="contact">Contact</label>
                    <input type="text" id="contact" name="contact" class="form-control"
                        value="{{ old('contact', $user->contact) }}" placeholder="Contoh +6285xxxxxxxxx">
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Batal <i
                        class="bi bi-x-square-fill"></i></a>
                <button type="submit" class="btn btn-outline-primary">Simpan <i class="bi bi-save-fill"></i></button>
            </div>
        </form>
    </div>
@endsection
