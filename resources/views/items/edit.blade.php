@extends('layouts.app')

@php
    $page = 'item';
    $action = 'edit';
@endphp

@section('content')
    <div class="card shadow-sm">
        <form action="{{ route('items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-header bg-gd text-white">
                <h5 class="card-title mb-0">Edit Data</h5>
            </div>
            <div class="card-body">
                <!-- Field Produksi -->
                <div class="mb-3">
                    <div class="form-group">
                        <label for="produksi" class="form-label">Produksi</label>
                        <select id="produksi" name="produksi" class="form-select" style="width: 100%;" required>
                            <option value="P1" {{ $item->produksi == 'P1' ? 'selected' : '' }}>P1</option>
                            <option value="P2" {{ $item->produksi == 'P2' ? 'selected' : '' }}>P2</option>
                        </select>
                    </div>
                </div>
                <!-- Field Item -->
                <div class="mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="kode_item" class="form-label">Kode Item</label>
                                <select id="kode_item" name="kode_item" class="form-control" required>
                                    <option value="{{ $item->kode_item }}" selected>{{ $item->kode_item }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nama_item" class="form-label">Nama Item</label>
                                <input type="text" id="nama_item" name="nama_item" class="form-control bg-light"
                                    value="{{ $item->nama_item }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Field Jenis -->
                <div class="mb-3">
                    <div class="form-group">
                        <label for="jenis" class="form-label">Jenis</label>
                        <select id="jenis" name="jenis" class="form-select" style="width: 100%;" required>
                            <option value="Baru" {{ $item->jenis == 'Baru' ? 'selected' : '' }}>Baru</option>
                            <option value="Bekas" {{ $item->jenis == 'Bekas' ? 'selected' : '' }}>Bekas</option>
                        </select>
                    </div>
                </div>
                <!-- Field Kondisi -->
                <div class="mb-3">
                    <div class="form-group">
                        <label for="kondisi" class="form-label">Kondisi</label>
                        <select id="kondisi" name="kondisi" class="form-select" style="width: 100%;" required>
                            <option value="Baik" {{ $item->kondisi == 'Baik' ? 'selected' : '' }}>Baik</option>
                            <option value="Rusak" {{ $item->kondisi == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                        </select>
                    </div>
                </div>
                <!-- Field Lokasi -->
                <div class="mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="kode_lokasi" class="form-label">Kode Lokasi</label>
                                <select id="kode_lokasi" name="kode_lokasi" class="form-control" required>
                                    <option value="{{ $item->kode_lokasi }}" selected>{{ $item->kode_lokasi }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nama_lokasi" class="form-label">Nama Lokasi</label>
                                <input type="text" id="nama_lokasi" name="nama_lokasi" class="form-control bg-light"
                                    value="{{ $item->nama_lokasi }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Field Jumlah -->
                <div class="mb-3">
                    <div class="form-group">
                        <label for="jumlah" class="form-label">Jumlah</label>
                        <input type="number" id="jumlah" name="jumlah" class="form-control" step="0.01"
                            value="{{ $item->jumlah }}" required>
                    </div>
                </div>
                <!-- Field Gambar -->
                <div class="mb-3">
                    <div class="form-group">
                        <label for="gambar" class="form-label">Gambar</label>
                        <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*"
                            capture="environment">
                        @if ($item->gambar)
                            <a href="{{ asset('storage/' . $item->gambar) }}" data-fancybox="gallery"
                                data-caption="Gambar {{ $item->nama_item }}">
                                <img src="{{ asset('storage/' . $item->gambar) }}" alt="Gambar Item"
                                    class="img-thumbnail mt-2" width="150">
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('items.index') }}" class="btn btn-outline-secondary">Batal <i
                        class="bi bi-x-square-fill"></i></a>
                <button type="submit" class="btn btn-outline-primary">Simpan <i class="bi bi-save-fill"></i></button>
            </div>
        </form>
    </div>
@endsection
