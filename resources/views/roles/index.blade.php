@extends('layouts.app')

@php
    $page = 'roles';
    $action = 'index';
@endphp

@section('content')
    <div class="card shadow-sm p-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Daftar Roles</h2>

            @if (auth()->user()->hasMenuPermission($menu->id, 'create'))
                <a class="btn btn-outline-primary" href="{{ route('roles.create') }}">
                    <i class="bi bi-plus-circle-fill"></i> Tambah Data
                </a>
            @endif
        </div>
        <table id="datatables" class="table table-sm table-bordered table-responsive table-striped shadow-sm">
            <thead class="bg-gd">
                <tr>
                    <th>No</th>
                    <th>Nama Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $key => $role)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $role->name }}</td>
                        <td class="text-center">
                            @if (auth()->user()->hasMenuPermission($menu->id, 'edit'))
                                <a href="{{ route('roles.menu-permissions', $role->id) }}"
                                    class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-eye-fill"></i> Atur
                                    Permissions</a>
                            @endif
                            @if (auth()->user()->hasMenuPermission($menu->id, 'edit'))
                                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                            @endif

                            @if (auth()->user()->hasMenuPermission($menu->id, 'destroy'))
                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
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
