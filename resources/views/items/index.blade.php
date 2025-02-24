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
                    {{-- <th>Gambar</th> --}}
                    <th>Status</th>
                    <th>Keterangan</th>
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
                        <td>{{ $item->kode_lokasi }}</td>
                        <td>{{ $item->nama_lokasi }}</td>
                        <td>{{ $item->jumlah }}</td>
                        {{-- <td class="text-center">
                            <a href="{{ asset('storage/' . $item->gambar) }}" data-fancybox="gallery"
                                data-caption="Gambar {{ $item->nama_item }}">
                                <img src="{{ asset('storage/' . $item->gambar) }}" alt="Gambar" width="50">
                            </a>
                        </td> --}}
                        <!-- Kolom Status Approval -->
                        <td>
                            {{ $item->status }}
                            {{-- @if ($approvalRoute && $item->approval_level + 2 == $approvalRoute->sequence)
                                Approved
                                @else
                                {{ $item->status }}
                            @endif --}}
                        </td>
                        <td>{{ $item->keterangan }}</td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <!-- Tombol View -->
                                @if (auth()->user()->hasMenuPermission($menu->id, 'show'))
                                    <a href="{{ route('items.show', $item->id) }}" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" data-bs-title="Detail">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                @endif

                                <!-- Tombol Approve -->
                                @if ($approvalRoute && $item->approval_level == $approvalRoute->sequence - 1 && $item->status !== 'Rejected')
                                    @if (!($item->approval_level > 0))
                                        <form action="{{ route('items.approve', $item->id) }}" method="POST"
                                            class="d-inline form-approval">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success btn-approve">
                                                <i class="bi bi-check2-square"></i> Approve
                                            </button>
                                        </form>
                                    @else
                                        <!-- Dropdown Action untuk Approve, Reject, dan Revise -->
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-success dropdown-toggle h-100"
                                                type="button" id="actionDropdown{{ $item->id }}"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                Action
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="actionDropdown{{ $item->id }}">
                                                <li>
                                                    <form action="{{ route('items.approve', $item->id) }}" method="POST"
                                                        class="d-inline form-approval">
                                                        @csrf
                                                        <button type="submit"
                                                            class="dropdown-item h-100 btn-approve text-success">Approve</button>
                                                    </form>
                                                </li>
                                                {{-- <li>
                                                    <form action="{{ route('items.revise', $item->id) }}" method="POST"
                                                        class="d-inline form-revisi">
                                                        @csrf
                                                        <button type="button"
                                                            class="dropdown-item h-100 btn-revisi text-warning">Revisi</button>
                                                    </form>
                                                </li> --}}
                                                <li>
                                                    <form action="{{ route('items.reject', $item->id) }}" method="POST"
                                                        class="d-inline form-reject">
                                                        @csrf
                                                        <button type="button"
                                                            class="dropdown-item h-100 btn-reject text-danger">Reject</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
                                @endif

                                <!-- Tombol Print QR -->
                                @if (auth()->user()->hasMenuPermission($menu->id, 'print'))
                                    <a href="{{ route('items.printQR', $item->id) }}"
                                        class="btn btn-sm btn-outline-secondary" target="_blank" data-bs-toggle="tooltip" data-bs-title="Print QR">
                                        <i class="bi bi-printer"></i>
                                    </a>
                                @endif


                                <!-- Tombol Edit dan Hapus hanya muncul jika kondisi terpenuhi -->
                                @php
                                    $canModify = !($item->approval_level > 0);
                                @endphp

                                @if ($canModify)
                                    @if (auth()->user()->hasMenuPermission($menu->id, 'edit'))
                                        <a href="{{ route('items.edit', $item->id) }}"
                                            class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip" data-bs-title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    @endif

                                    @if (auth()->user()->hasMenuPermission($menu->id, 'destroy'))
                                        <form action="{{ route('items.destroy', $item->id) }}" method="POST"
                                            class="form-delete d-inline"
                                            onsubmit="return confirm('Anda yakin ingin menghapus item ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm h-100 btn-outline-danger btn-delete" data-bs-toggle="tooltip" data-bs-title="Hapus">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
