@extends('layouts.app')

@php
    $page = 'approval_routes';
    $action = 'index';
@endphp

@section('content')
    <div class="card shadow-sm p-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Daftar Approval Routes</h2>

            @if (auth()->user()->hasMenuPermission($menu->id, 'create'))
                <a class="btn btn-outline-primary" href="{{ route('approval_routes.create') }}">
                    <i class="bi bi-plus-circle-fill"></i> Tambah Data
                </a>
            @endif
        </div>

        <table id="datatables" class="table table-sm table-bordered table-responsive table-striped shadow-sm">
            <thead class="bg-gd">
                <tr>
                    <th>No</th>
                    <th>Module</th>
                    <th>Role</th>
                    <th>Sequence</th>
                    <th>Assigned User</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($routes as $key => $route)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $route->module }}</td>
                        <td>{{ $route->role->name }}</td>
                        <td>{{ $route->sequence }}</td>
                        <td>{{ $route->assignedUser ? $route->assignedUser->email : '-' }}</td>
                        <td class="text-center">
                            @if (auth()->user()->hasMenuPermission($menu->id, 'edit'))
                                <a href="{{ route('approval_routes.edit', $route->id) }}"
                                    class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                            @endif

                            @if (auth()->user()->hasMenuPermission($menu->id, 'destroy'))
                                <form action="{{ route('approval_routes.destroy', $route->id) }}" method="POST"
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
