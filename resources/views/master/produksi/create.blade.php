@extends('layouts.app')

@php
    $page = 'master_produksi';
    $action = 'create';
@endphp

@section('content')
    <div class="card rounded-lg shadow-sm">
        <form action="{{ route('master_produksi.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-header bg-gd text-white">
                <h5 class="card-title" id="addModalLabel">Tambah Data</h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="name">Nama Produksi</label>
                    <input type="text" id="name" name="name" class="form-control form-control-sm" required>
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
