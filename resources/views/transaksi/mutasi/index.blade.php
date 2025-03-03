@extends('layouts.app')

@php
    $page = 'transaksi/mutasi';
    $action = 'index';
@endphp

@section('content')
    <div class="card rounded-lg shadow-sm px-5 pb-5 pt-0 mt-3 position-relative">
        <!-- Card Judul Melayang -->
        <div class="card rounded-lg shadow p-3 position-relative mx-auto bg-gd-rev"
            style="width: 100%; top: -10px; transform: translateY(-10px); text-align: center;">
            <div class="d-flex justify-content-between align-items-center">
                <div class="display-6 fw-bold mb-0">Mutasi Items</div>
            </div>
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
                    {{-- <th>Kode Lokasi</th> --}}
                    <th>Nama Lokasi</th>
                    <th>Jumlah</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item->produksi->name }}</td>
                        <td>{{ $item->kode_item }}</td>
                        <td>{{ $item->nama_item }}</td>
                        <td>{{ $item->jenis }}</td>
                        <td>{{ $item->kondisi }}</td>
                        {{-- <td>{{ $item->kode_lokasi }}</td> --}}
                        <td>{{ $item->nama_lokasi }}</td>
                        <td>{{ $item->jumlah }}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center align-items-center flex-nowrap gap-1">
                                {{-- <div class="btn btn-sm rounded-lg btn-group" role="group"> --}}
                                <!-- Tombol View -->
                                @if (auth()->user()->hasMenuPermission($menu->id, 'show'))
                                    <a href="{{ route('mutasi.show', $item->id) }}"
                                        class="btn btn-sm rounded-lg btn-outline-info" data-bs-toggle="tooltip"
                                        data-bs-title="Detail">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                @endif

                                <!-- Tombol Edit  -->
                                @if (auth()->user()->hasMenuPermission($menu->id, 'edit'))
                                    <a href="{{ route('mutasi.edit', $item->id) }}"
                                        class="btn btn-sm rounded-lg btn-outline-warning" data-bs-toggle="tooltip"
                                        data-bs-title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                @endif

                                @if (auth()->user()->hasMenuPermission($menu->id, 'show'))
                                    <button class="btn btn-sm rounded-lg btn-outline-info view-history"
                                        data-item-id="{{ $item->id }}" data-bs-toggle="tooltip"
                                        data-bs-title="Riwayat Mutasi">
                                        <i class="bi bi-clock-history"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal untuk menampilkan riwayat mutasi -->
    <div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="historyModalLabel">Riwayat Mutasi</h5>
                    <button type="button" class="btn btn-sm btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Konten riwayat akan dimuat di sini -->
                    <div id="historyContent">Loading...</div>
                </div>
            </div>
        </div>
    </div>
@endsection
