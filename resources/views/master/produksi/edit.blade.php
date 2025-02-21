@extends('layouts.app')

@php
    $page = 'master/produksi';
    $action = 'edit';
@endphp

@section('content')
    <div class="card shadow-sm">
        <form action="{{ route('master_produksi.update', $produksi->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-header bg-gd text-white">
                <h5 class="card-title mb-0">Edit Data</h5>
            </div>
            <div class="card-body">
                <div class="form-group mb-3">
                    <label for="nama_produksi">Nama Produksi</label>
                    <input type="text" id="nama_produksi" name="nama_produksi" class="form-control"
                        value="{{ $produksi->nama_produksi }}" required>
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('master_produksi.index') }}" class="btn btn-outline-secondary">Batal <i
                        class="bi bi-x-square-fill"></i></a>
                <button type="submit" class="btn btn-outline-primary">Simpan <i class="bi bi-save-fill"></i></button>
            </div>
        </form>
    </div>
@endsection
