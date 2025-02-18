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
                    {{ $item->produksi }}
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
                    <strong>Gambar:</strong>
                </div>
                <div class="col-sm-9">
                    <a href="{{ asset('storage/' . $item->gambar) }}" data-fancybox="gallery"
                        data-caption="Gambar {{ $item->nama_item }}">
                        <img src="{{ asset('storage/' . $item->gambar) }}" alt="Gambar Item" class="img-thumbnail mt-2"
                            width="150">
                    </a>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end align-items-center gap-2">
            @if ($approvalRoute && $item->approval_level == $approvalRoute->sequence - 1)
                <form action="{{ route('items.approve', $item->id) }}" method="POST" class="form-approval d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-success btn-approve">
                        Approve <i class="bi bi-check2-square"></i>
                    </button>
                </form>
            @endif
            <a href="{{ route('items.index') }}" class="btn btn-sm btn-outline-secondary d-inline">
                Kembali <i class="bi bi-x-square-fill"></i>
            </a>
        </div>
    </div>
@endsection
