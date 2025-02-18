@extends('layouts.app')

@php
    $page = 'menu';
    $action = 'index';
@endphp

@section('content')
    <div class="card shadow-sm p-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Daftar Menu</h2>

            @if (auth()->user()->hasMenuPermission($menu_id->id, 'create'))
                <a class="btn btn-outline-primary" href="{{ route('menus.create') }}">
                    <i class="bi bi-plus-circle-fill"></i> Tambah Data
                </a>
            @endif
        </div>

        <table id="datatables" class="table table-sm table-bordered table-responsive table-striped shadow-sm">
            <thead class="bg-gd">
                <tr>
                    <th>No</th>
                    <th>title</th>
                    <th>Route</th>
                    <th>Icon</th>
                    <th>Order</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($menus as $key => $menu)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $menu->title }}</td>
                        <td>{{ $menu->route }}</td>
                        <td>{{ $menu->icon }}</td>
                        <td>{{ $menu->order }}</td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                @if (auth()->user()->hasMenuPermission($menu_id->id, 'edit'))
                                    <a href="{{ route('menus.edit', $menu_id->id) }}"
                                        class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                @endif

                                @if (auth()->user()->hasMenuPermission($menu_id->id, 'destroy'))
                                    <form action="{{ route('menus.destroy', $menu->id) }}" method="POST"
                                        class="form-delete d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete">
                                            <i class="bi bi-trash-fill"></i> Hapus
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
