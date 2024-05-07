@extends('layouts.plantilla')

@section('title', "Home")

@section('links')
    <!-- Replace the Bootstrap 4 link with Bootstrap 5 links -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.7.0/dist/css/bootstrap.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.7.0/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="{{ asset('styles/landing.css') }}">
    <!-- Include Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection

@section('content')
    <div class="container mt-5">
        <div class="row mt-4">

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="col-12">
                <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
                    <div class="container">
                        <p class="text-center">
                            Bienvenido a nuestra aplicación, aqui podra seleccionar en la cabecera de nuestra web las acciones que desee tomar.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

