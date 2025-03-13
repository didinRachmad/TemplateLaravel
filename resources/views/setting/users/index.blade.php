@extends('layouts.app')

@php
    $page = 'setting/users';
    $action = 'index';
@endphp

@section('content')
    <div class="card rounded-lg shadow-sm px-5 pb-5 pt-0 mt-3 position-relative">
        <!-- Card Judul Melayang -->
        <div class="card border-0 rounded-lg shadow p-3 position-relative mx-auto bg-gd-rev"
            style="width: 100%; top: -10px; transform: translateY(-10px); text-align: center;">
            <div class="d-flex justify-content-between align-items-center">
                <div class="display-6 fw-bold mb-0">Daftar Users</div>

                @if (Auth::user()->hasMenuPermission($menu->id, 'create'))
                    <a class="btn btn-sm rounded-lg btn-primary shadow-sm" href="{{ route('users.create') }}">
                        <i class="bi bi-plus-circle-fill"></i> Tambah Data
                    </a>
                @endif
            </div>
        </div>

        <table id="datatables" class="table table-sm table-bordered table-responsive table-striped shadow-sm">
            <thead class="bg-gd">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Produksi</th>
                    <th>Kontak</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $key => $user)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if ($user->produksis->isNotEmpty())
                                @foreach ($user->produksis as $produksi)
                                    <span class="badge bg-info">{{ $produksi->name }}</span>
                                @endforeach
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $user->contact ?? '-' }}</td>
                        <td>{{ implode(', ', $user->getRoleNames()->toArray()) }}</td>
                        <td>
                            @if (Auth::user()->hasMenuPermission($menu->id, 'edit'))
                                <a href="{{ route('users.edit', $user->id) }}"
                                    class="btn btn-sm rounded-lg btn-outline-warning" data-bs-toggle="tooltip"
                                    data-bs-title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                            @endif

                            <!-- Tombol Reset Password -->
                            @if (Auth::user()->hasMenuPermission($menu->id, 'update'))
                                <form action="{{ route('users.reset-password', $user->id) }}" method="POST"
                                    class="d-inline form-reset-password">
                                    @csrf
                                    <button type="button"
                                        class="btn btn-sm rounded-lg btn-outline-secondary btn-reset-password"
                                        data-bs-toggle="tooltip" data-bs-title="Reset Password">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </button>
                                </form>
                            @endif

                            {{-- Tombol Hapus --}}
                            @if (Auth::user()->hasMenuPermission($menu->id, 'destroy'))
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                    class="form-delete d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm rounded-lg btn-outline-danger btn-delete"
                                        data-bs-toggle="tooltip" data-bs-title="Hapus">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
