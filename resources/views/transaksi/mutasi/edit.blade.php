@extends('layouts.app')

@php
    $page = 'transaksi/mutasi';
    $action = 'edit';
@endphp

@section('content')
    <div class="card rounded-lg shadow-sm">
        <form action="{{ route('mutasi.update', $item->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-header bg-gd text-white">
                <h5 class="card-title mb-0">Mutasi Item</h5>
            </div>
            <div class="card-body">
                <h3>Data Awal</h3>
                <!-- Produksi Lama (Read-only) -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="produksi_lama" class="form-label">Produksi Lama</label>
                            <input type="text" id="produksi_lama" class="form-control form-control-sm bg-light"
                                value="{{ $item->produksi->name ?? 'N/A' }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <!-- Nama Item (Read-only) -->
                        <div class="form-group mb-3">
                            <label for="kode_item" class="form-label">Kode Item</label>
                            <input type="text" id="kode_item" name="kode_item"
                                class="form-control form-control-sm bg-light" value="{{ $item->kode_item }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <!-- Nama Item (Read-only) -->
                        <div class="form-group mb-3">
                            <label for="nama_item" class="form-label">Nama Item</label>
                            <input type="text" id="nama_item" name="nama_item"
                                class="form-control form-control-sm bg-light" value="{{ $item->nama_item }}" readonly>
                        </div>
                    </div>
                </div>
                <!-- Lokasi Lama (Read-only) -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="lokasi_lama" class="form-label">Lokasi Lama</label>
                            <input type="text" id="lokasi_lama" class="form-control form-control-sm bg-light"
                                value="{{ $item->nama_lokasi }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <!-- Detail Lokasi Lama (Read-only) -->
                        <div class="form-group mb-3">
                            <label for="detail_lokasi" class="form-label">Detail Lokasi</label>
                            <textarea id="detail_lokasi" name="detail_lokasi" class="form-control form-control-sm bg-light" readonly>{{ old('detail_lokasi', $item->detail_lokasi) }}</textarea>
                        </div>
                    </div>
                </div>
                <!-- Jumlah (Read-only) -->
                <div class="form-group mb-3">
                    <label for="jumlah" class="form-label">Jumlah</label>
                    <input type="number" id="jumlah" class="form-control form-control-sm bg-light"
                        value="{{ $item->jumlah }}" readonly>
                </div>

                <hr>
                <h3>Data Mutasi</h3>
                <!-- Produksi Baru -->
                <div class="form-group mb-3">
                    <label for="produksi_baru" class="form-label">Produksi Baru</label>
                    <select id="produksi_baru" name="produksi_baru" class="form-select form-select-sm" style="width: 100%;"
                        required>
                        <option value="" selected>Pilih Produksi</option>
                        @foreach ($produksiList as $produksi)
                            <option value="{{ $produksi->id }}"
                                {{ old('produksi_baru') == $produksi->id ? 'selected' : '' }}>
                                {{ $produksi->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <!-- Lokasi Baru -->
                        <div class="form-group mb-3">
                            <label for="lokasi_baru" class="form-label">Lokasi Baru</label>
                            <input type="text" id="lokasi_baru" name="lokasi_baru" class="form-control form-control-sm"
                                placeholder="Masukkan lokasi baru" value="{{ old('lokasi_baru') }}" required>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group mb-3">
                            <label for="detail_lokasi_baru" class="form-label">Detail Lokasi</label>
                            <textarea id="detail_lokasi_baru" name="detail_lokasi_baru" class="form-control form-control-sm">{{ old('detail_lokasi') }}</textarea>
                        </div>
                    </div>
                </div>
                <!-- Jumlah Mutasi -->
                <div class="form-group mb-3">
                    <label for="jumlah_mutasi" class="form-label">Jumlah Mutasi</label>
                    <input type="number" id="jumlah_mutasi" name="jumlah_mutasi" class="form-control form-control-sm"
                        step="0.01" value="{{ old('jumlah_mutasi') }}" max="{{ $item->jumlah }}"
                        oninvalid="this.setCustomValidity('Nilai tidak boleh lebih dari {{ $item->jumlah }}')"
                        oninput="this.setCustomValidity('')" placeholder="Masukkan jumlah mutasi" required>
                </div>

            </div>
            <div class="card-footer text-end">
                <a href="{{ route('mutasi.index') }}" class="btn btn-sm rounded-lg btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-sm rounded-lg btn-primary shadow-sm">Simpan</button>
            </div>
        </form>
    </div>
@endsection
