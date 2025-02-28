@extends('layouts.app')

@php
    $page = 'master/produksi';
    $action = 'edit';
@endphp

@section('content')
    <div class="card rounded-lg shadow-sm">
        <form action="{{ route('master_produksi.update', $produksi->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-header bg-gd text-white">
                <h5 class="card-title mb-0">Edit Data</h5>
            </div>
            <div class="card-body">
                <div class="form-group mb-3">
                    <label for="name">Nama Produksi</label>
                    <input type="text" id="name" name="name" class="form-control form-control-sm"
                        value="{{ $produksi->name }}" required>
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('master_produksi.index') }}" class="btn btn-sm rounded-lg btn-outline-secondary">Batal <i
                        class="bi bi-x-square-fill"></i></a>
                <button type="submit" class="btn btn-sm rounded-lg btn-outline-primary">Simpan <i
                        class="bi bi-save-fill"></i></button>
            </div>
        </form>
    </div>
@endsection
