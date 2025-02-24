@extends('layouts.app')

@php
    $page = 'item';
    $action = 'show';
@endphp

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-gd text-white">
            <h5 class="card-title mb-0">Detail Data Item</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-sm-3">
                    <strong>Produksi:</strong>
                </div>
                <div class="col-sm-9">
                    {{ $item->produksi->name }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3">
                    <strong>Kode Item:</strong>
                </div>
                <div class="col-sm-9">
                    {{ $item->kode_item }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3">
                    <strong>Nama Item:</strong>
                </div>
                <div class="col-sm-9">
                    {{ $item->nama_item }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3">
                    <strong>Jenis:</strong>
                </div>
                <div class="col-sm-9">
                    {{ $item->jenis }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3">
                    <strong>Kondisi:</strong>
                </div>
                <div class="col-sm-9">
                    {{ $item->kondisi }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3">
                    <strong>Kode Lokasi:</strong>
                </div>
                <div class="col-sm-9">
                    {{ $item->kode_lokasi }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3">
                    <strong>Nama Lokasi:</strong>
                </div>
                <div class="col-sm-9">
                    {{ $item->nama_lokasi }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3">
                    <strong>Jumlah:</strong>
                </div>
                <div class="col-sm-9">
                    {{ $item->jumlah }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3">
                    <strong>Status:</strong>
                </div>
                <div class="col-sm-9">
                    {{ $item->status }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3">
                    <strong>Keterangan:</strong>
                </div>
                <div class="col-sm-9">
                    {{ $item->keterangan }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3">
                    <strong>Gambar:</strong>
                </div>
                <div class="col-sm-9">
                    @if (!empty($item->gambar))
                        @foreach ($item->gambar as $gambar)
                            <a href="{{ asset('storage/' . $gambar) }}" data-fancybox="gallery"
                                data-caption="Gambar {{ $item->nama_item }}">
                                <img src="{{ asset('storage/' . $gambar) }}" alt="Gambar Item" class="img-thumbnail mt-2"
                                    width="150">
                            </a>
                        @endforeach
                    @else
                        <p>Tidak ada gambar tersedia.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end align-items-center gap-2">
            <!-- Tombol Approve -->
            @if ($approvalRoute && $item->approval_level == $approvalRoute->sequence - 1)
                @if (!($item->approval_level > 0))
                    <form action="{{ route('items.approve', $item->id) }}" method="POST" class="d-inline form-approval">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-success btn-approve">
                            <i class="bi bi-check2-square"></i> Approve
                        </button>
                    </form>
                @else
                    <!-- Dropdown Action untuk Approve, Reject, dan Revise -->
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-success dropdown-toggle h-100" type="button"
                            id="actionDropdown{{ $item->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                            Action
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="actionDropdown{{ $item->id }}">
                            <li>
                                <form action="{{ route('items.approve', $item->id) }}" method="POST"
                                    class="d-inline form-approval">
                                    @csrf
                                    <button type="submit" class="dropdown-item btn-approve text-success">Approve</button>
                                </form>
                            </li>
                            {{-- <li>
                                <form action="{{ route('items.revise', $item->id) }}" method="POST"
                                    class="d-inline form-revisi">
                                    @csrf
                                    <button type="button" class="dropdown-item btn-revisi text-warning">Revisi</button>
                                </form>
                            </li> --}}
                            <li>
                                <form action="{{ route('items.reject', $item->id) }}" method="POST"
                                    class="d-inline form-reject">
                                    @csrf
                                    <button type="button" class="dropdown-item btn-reject text-danger">Reject</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endif
            @endif
            <a href="{{ route('items.index') }}" class="btn btn-sm btn-outline-secondary d-inline">
                Kembali <i class="bi bi-x-square-fill"></i>
            </a>
        </div>
    </div>
@endsection
