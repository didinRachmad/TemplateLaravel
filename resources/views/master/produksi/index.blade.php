@extends('layouts.app')

@php
    $page = 'master/produksi';
    $action = 'index';
@endphp

@section('content')
    <div class="card rounded-lg shadow-sm px-5 pb-5 pt-0 mt-3 position-relative">
        <!-- Card Judul Melayang -->
        <div class="card rounded-lg shadow p-3 position-relative mx-auto bg-gd-rev"
            style="width: 100%; top: -10px; transform: translateY(-10px); text-align: center;">
            <div class="d-flex justify-content-between align-items-center">
                <div class="display-6 fw-bold mb-0">Daftar Produksi</div>

                @if (auth()->user()->hasMenuPermission($menu->id, 'create'))
                    <a class="btn btn-sm rounded-lg btn-primary shadow-sm" href="{{ route('master_produksi.create') }}">
                        <i class="bi bi-plus-circle-fill"></i> Tambah Data
                    </a>
                @endif
            </div>
        </div>

        <table id="datatables" class="table table-sm table-bordered table-responsive table-striped shadow-sm">
            <thead class="bg-gd">
                <tr>
                    <th>No</th>
                    <th>Nama Produksi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($produksis as $key => $produksi)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $produksi->name }}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center align-items-center flex-nowrap gap-1">
                                @if (auth()->user()->hasMenuPermission($menu->id, 'edit'))
                                    <a href="{{ route('master_produksi.edit', $produksi->id) }}"
                                        class="btn btn-sm rounded-lg btn-outline-warning" data-bs-toggle="tooltip"
                                        data-bs-title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                @endif

                                @if (auth()->user()->hasMenuPermission($menu->id, 'destroy'))
                                    <form action="{{ route('master_produksi.destroy', $produksi->id) }}" method="POST"
                                        class="form-delete d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm rounded-lg btn-outline-danger btn-delete"
                                            data-bs-toggle="tooltip" data-bs-title="Hapus">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
