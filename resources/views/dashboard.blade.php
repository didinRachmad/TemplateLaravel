@extends('layouts.app')

@php
    $page = 'dashboard';
    $action = 'index';
@endphp

@section('content')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 fw-bold">
                {{ __('Selamat datang') }}
            </div>
        </div>
    </div>
@endsection
