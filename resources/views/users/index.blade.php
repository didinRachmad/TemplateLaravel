@extends('layouts.app')

@php
    $page = 'users';
    $action = 'index';
@endphp

@section('content')
    <div class="card shadow-sm p-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Daftar Users</h2>

            @if (auth()->user()->hasMenuPermission($menu->id, 'create'))
                <a class="btn btn-outline-primary" href="{{ route('users.create') }}">
                    <i class="bi bi-plus-circle-fill"></i> Tambah Data
                </a>
            @endif
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
                        <td>{{ $user->produksi->name ?? '-' }}</td>
                        <td>{{ $user->contact ?? '-' }}</td>
                        <td>{{ implode(', ', $user->getRoleNames()->toArray()) }}</td>
                        <td>
                            @if (auth()->user()->hasMenuPermission($menu->id, 'edit'))
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                            @endif

                            <!-- Tombol Reset Password -->
                            @if (auth()->user()->hasMenuPermission($menu->id, 'update'))
                                <form action="{{ route('users.reset-password', $user->id) }}" method="POST"
                                    class="d-inline form-reset-password">
                                    @csrf
                                    <button type="button" class="btn btn-sm btn-outline-success btn-reset-password">
                                        <i class="bi bi-check2-square"></i> Reset Password
                                    </button>
                                </form>
                            @endif

                            {{-- Tombol Hapus --}}
                            @if (auth()->user()->hasMenuPermission($menu->id, 'destroy'))
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                    class="form-delete d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-delete">
                                        <i class="bi bi-trash-fill"></i> Hapus
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
