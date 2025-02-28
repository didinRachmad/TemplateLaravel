@extends('layouts.app')

@php
    $page = 'setting/menu';
    $action = 'edit';
@endphp

@section('content')
    <div class="card rounded-lg shadow-sm">
        <form action="{{ route('menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-header bg-gd text-white">
                <h5 class="card-title mb-0">Edit Data Menu</h5>
            </div>
            <div class="card-body">
                <!-- Field Title -->
                <div class="mb-3">
                    <div class="form-group">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" id="title" name="title" class="form-control form-control-sm"
                            value="{{ old('title', $menu->title) }}" required>
                    </div>
                </div>
                <!-- Field Route -->
                <div class="mb-3">
                    <div class="form-group">
                        <label for="route" class="form-label">Route</label>
                        <input type="text" id="route" name="route" class="form-control form-control-sm"
                            value="{{ old('route', $menu->route) }}" required>
                    </div>
                </div>
                <!-- Field Icon -->
                <div class="mb-3">
                    <div class="form-group">
                        <label for="icon" class="form-label">Icon</label>
                        <input type="text" id="icon" name="icon" class="form-control form-control-sm"
                            value="{{ old('icon', $menu->icon) }}">
                    </div>
                </div>
                <!-- Field Order -->
                <div class="mb-3">
                    <div class="form-group">
                        <label for="order" class="form-label">Order</label>
                        <input type="text" id="order" name="order" class="form-control form-control-sm"
                            value="{{ old('order', $menu->order) }}" required>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('menus.index') }}" class="btn btn-sm rounded-lg btn-outline-secondary">Batal <i
                        class="bi bi-x-square-fill"></i></a>
                <button type="submit" class="btn btn-sm rounded-lg btn-outline-primary">Simpan <i
                        class="bi bi-save-fill"></i></button>
            </div>
        </form>
    </div>
@endsection
