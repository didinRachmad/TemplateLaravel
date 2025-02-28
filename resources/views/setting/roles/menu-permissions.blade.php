@extends('layouts.app')

@php
    $page = 'setting/roles';
    $action = 'edit';
@endphp

@section('content')
    <div class="card rounded-lg shadow-sm">
        <form action="{{ route('roles.assign-menu-permissions', $role->id) }}" method="POST">
            @csrf
            <div class="card-header bg-gd text-white">
                <h5 class="card-title mb-0">Pengaturan Menu untuk Role: {{ $role->name }}</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-responsive table-striped shadow-sm">
                    <thead class="bg-gd">
                        <tr>
                            <th>Menu</th>
                            <th>Route</th>
                            <th>Permission</th>
                            <th>Check All</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Grouping menu berdasarkan parent_id
                            $groupedMenus = $menus->groupBy('parent_id');
                        @endphp

                        {{-- Tampilkan menu top-level --}}
                        @foreach ($groupedMenus->get(null) as $parentMenu)
                            @if (empty($parentMenu->route))
                                <!-- Jika parent menu tidak memiliki route, tampilkan sebagai header grup -->
                                <tr class="bg-gray-200">
                                    <td colspan="4"><strong>{{ $parentMenu->title }}</strong></td>
                                </tr>
                                <!-- Tampilkan menu anak dari header grup -->
                                @if (isset($groupedMenus[$parentMenu->id]))
                                    @foreach ($groupedMenus[$parentMenu->id] as $childMenu)
                                        @if (!empty($childMenu->route))
                                            <tr>
                                                <td class="ps-3">{{ $childMenu->title }}</td>
                                                <td>{{ $childMenu->route }}</td>
                                                <td>
                                                    @php
                                                        $currentPermissionIds =
                                                            $roleMenuPermissions[$childMenu->id] ?? [];
                                                    @endphp
                                                    <div class="row">
                                                        @foreach ($permissions as $permission)
                                                            <div class="col-md-3 mb-2">
                                                                <label>
                                                                    <input type="checkbox"
                                                                        class="menu-permission-checkbox-{{ $childMenu->id }}"
                                                                        name="menu_permissions[{{ $childMenu->id }}][]"
                                                                        value="{{ $permission->id }}"
                                                                        {{ in_array($permission->id, $currentPermissionIds) ? 'checked' : '' }}>
                                                                    {{ ucfirst($permission->name) }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <!-- Master checkbox untuk menu anak -->
                                                    <label>
                                                        <input type="checkbox"
                                                            onchange="toggleMenuPermissionsCheckbox(this, {{ $childMenu->id }})">
                                                        All
                                                    </label>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endif
                            @else
                                <!-- Jika menu top-level memiliki route, tampilkan langsung -->
                                <tr>
                                    <td>{{ $parentMenu->title }}</td>
                                    <td>{{ $parentMenu->route }}</td>
                                    <td>
                                        @php
                                            $currentPermissionIds = $roleMenuPermissions[$parentMenu->id] ?? [];
                                        @endphp
                                        <div class="row">
                                            @foreach ($permissions as $permission)
                                                <div class="col-md-3 mb-2">
                                                    <label>
                                                        <input type="checkbox"
                                                            class="menu-permission-checkbox-{{ $parentMenu->id }}"
                                                            name="menu_permissions[{{ $parentMenu->id }}][]"
                                                            value="{{ $permission->id }}"
                                                            {{ in_array($permission->id, $currentPermissionIds) ? 'checked' : '' }}>
                                                        {{ ucfirst($permission->name) }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <!-- Master checkbox untuk menu top-level -->
                                        <label>
                                            <input type="checkbox"
                                                onchange="toggleMenuPermissionsCheckbox(this, {{ $parentMenu->id }})"> All
                                        </label>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('roles.index') }}" class="btn btn-sm rounded-lg btn-outline-secondary">
                    Batal <i class="bi bi-x-square-fill"></i>
                </a>
                <button type="submit" class="btn btn-sm rounded-lg btn-outline-primary">
                    Simpan <i class="bi bi-save-fill"></i>
                </button>
            </div>
        </form>
    </div>
@endsection
