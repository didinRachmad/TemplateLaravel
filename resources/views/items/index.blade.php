@extends('layouts.app')

@php
    $page = 'item';
    $action = 'index';
@endphp

@section('content')
    <div class="card shadow-sm p-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Daftar item Bekas (di Luar MOI)</h2>

            @if (auth()->user()->hasMenuPermission($menu->id, 'create'))
                <a class="btn btn-outline-primary" href="{{ route('items.create') }}">
                    <i class="bi bi-plus-circle-fill"></i> Tambah Data
                </a>
            @endif
        </div>

        <table id="datatables" class="table table-sm table-bordered table-responsive table-striped shadow-sm">
            <thead class="bg-gd">
                <tr>
                    <th>No</th>
                    <th>Produksi</th>
                    <th>Kode Item</th>
                    <th>Nama Item</th>
                    <th>Jenis</th>
                    <th>Kondisi</th>
                    <th>Kode Lokasi</th>
                    <th>Nama Lokasi</th>
                    <th>Jumlah</th>
                    <th>Gambar</th>
                    <th>Status Approval</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item->produksi }}</td>
                        <td>{{ $item->kode_item }}</td>
                        <td>{{ $item->nama_item }}</td>
                        <td>{{ $item->jenis }}</td>
                        <td>{{ $item->kondisi }}</td>
                        <td>{{ $item->kode_lokasi }}</td>
                        <td>{{ $item->nama_lokasi }}</td>
                        <td>{{ $item->jumlah }}</td>
                        <td class="text-center">
                            <a href="{{ asset('storage/' . $item->gambar) }}" data-fancybox="gallery"
                                data-caption="Gambar {{ $item->nama_item }}">
                                <img src="{{ asset('storage/' . $item->gambar) }}" alt="Gambar" width="50">
                            </a>
                        </td>
                        <!-- Kolom Status Approval -->
                        <td>
                            @if ($approvalRoute && $item->approval_level >= $approvalRoute->sequence)
                                Approved
                            @else
                                {{ $item->status }}
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                @if (auth()->user()->hasMenuPermission($menu->id, 'show'))
                                    <a href="{{ route('items.show', $item->id) }}" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye-fill"></i> View
                                    </a>
                                @endif

                                @if ($approvalRoute && $item->approval_level == $approvalRoute->sequence - 1)
                                    <form action="{{ route('items.approve', $item->id) }}" method="POST"
                                        class="form-approval">
                                        @csrf
                                        <button type="button" class="btn btn-sm btn-outline-success btn-approve">
                                            <i class="bi bi-check2-square"></i> Approve
                                        </button>
                                    </form>
                                @endif

                                @if (auth()->user()->hasMenuPermission($menu->id, 'edit'))
                                    <a href="{{ route('items.edit', $item->id) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                @endif

                                @if (auth()->user()->hasMenuPermission($menu->id, 'destroy'))
                                    <form action="{{ route('items.destroy', $item->id) }}" method="POST"
                                        class="form-delete d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete">
                                            <i class="bi bi-trash-fill"></i> Hapus
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
