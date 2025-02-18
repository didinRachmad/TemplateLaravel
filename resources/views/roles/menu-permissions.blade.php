@extends('layouts.app')

@php
    $page = 'roles';
    $action = 'index';
@endphp

@section('content')
    <div class="card shadow-sm">
        <form action="{{ route('roles.assign-menu-permissions', $role->id) }}" method="POST">
            @csrf
            <div class="card-header bg-gd text-white">
                <h5 class="card-title mb-0">Pengaturan Menu untuk Role : {{ $role->name }}</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-responsive table-striped shadow-sm">
                    <thead class="bg-gd">
                        <tr>
                            <th>Menu</th>
                            <th>Route</th>
                            <th>Permission</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($menus as $menu)
                            <tr>
                                <td>{{ $menu->title }}</td>
                                <td>{{ $menu->route }}</td>
                                <td>
                                    @php
                                        // Ambil permission_ids yang sudah disetting untuk menu ini
                                        $currentPermissionIds = $roleMenuPermissions[$menu->id] ?? [];
                                    @endphp

                                    <div class="row">
                                        @foreach ($permissions as $permission)
                                            <div class="col-md-3 mb-2">
                                                <label>
                                                    <input type="checkbox" name="menu_permissions[{{ $menu->id }}][]"
                                                        value="{{ $permission->id }}"
                                                        {{ in_array($permission->id, $currentPermissionIds) ? 'checked' : '' }}>
                                                    {{ ucfirst($permission->name) }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">Batal <i
                        class="bi bi-x-square-fill"></i></a>
                <button type="submit" class="btn btn-outline-primary">Simpan <i class="bi bi-save-fill"></i></button>
            </div>
        </form>
    </div>
@endsection
