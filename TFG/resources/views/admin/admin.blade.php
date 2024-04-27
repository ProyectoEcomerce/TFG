@extends('layouts.plantilla')

@section('title', "Admin")

@section('content')
    <div class="container mt-4 mb-4">
        <h2 class="text-center mb-4">{{ __('Administrador') }}</h2>
        <div class="row row-cols-1 row-cols-md-2 g-2">

            <div class="col">
                <a href="{{ route('adminUsers') }}" class="card-link text-decoration-none">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-list"></i> {{ __('Administrar Usuarios') }}</h5>
                            <p class="card-text">{{ __('Gestión, creación, actualización y eliminación de usuarios') }}</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col">
                <a href="{{ route('adminAreas') }}" class="card-link text-decoration-none">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-list"></i> {{ __('Administrar Areas') }}</h5>
                            <p class="card-text">{{ __('Gestión, creación, actualización y eliminación de areas') }}</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
