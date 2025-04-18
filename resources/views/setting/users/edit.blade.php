@extends('layouts.app')

@php
    $page = 'setting/users';
    $action = 'edit';
@endphp

@section('content')
    <div class="card rounded-lg shadow-sm">
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
                    <input type="text" id="name" name="name" class="form-control form-control-sm"
                        value="{{ old('name', $user->name) }}" required>
                </div>

                <!-- Email -->
                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email" class="form-control form-control-sm"
                        value="{{ old('email', $user->email) }}" required>
                </div>

                <!-- Produksi -->
                <div class="form-group mb-3">
                    <label for="produksi" class="form-label">Produksi</label>
                    <select id="produksi" name="produksi[]" class="form-select form-select-sm" multiple="multiple"
                        style="width: 100%;">
                        @foreach ($produksiList as $produksi)
                            <option value="{{ $produksi->id }}"
                                {{ in_array($produksi->id, old('produksi', $user->produksis->pluck('id')->toArray())) ? 'selected' : '' }}>
                                {{ $produksi->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Contact -->
                <div class="form-group mb-3">
                    <label for="contact">Contact</label>
                    <input type="text" id="contact" name="contact" class="form-control form-control-sm"
                        value="{{ old('contact', $user->contact) }}" placeholder="Contoh +6285xxxxxxxxx">
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
