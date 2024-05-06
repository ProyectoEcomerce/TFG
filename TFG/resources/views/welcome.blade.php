@extends('layouts.plantilla')

@section('title', "Home")

@section('content')

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
                        <h2 class="text-center">Sobre nosotros</h2>
                        <p class="text-center">
                           Somos un grupo de desarrolladores web que nos dedicamos a la creación de aplicativos y webs que nos pidan los clientes. 
                        </p>

                        <h2 class="text-center">Servicio</h2>
                        <p class="text-center">
                            Ofrecemos una plataforma en la que los usuarios pueden marcar las preferencias de sus turnos y los administradores pueden rellenar los turnos en base de las preferecias de los usuarios y dirigirt la aplicación.
                        </p>

                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <td class="text-center">
                                        <img src="{{ asset('images/pc1.webp') }}" class="img-fluid" alt="PC Image 1">
                                    </td>
                                    <td class="text-center">
                                        <img src="{{ asset('images/pc2.webp') }}" class="img-fluid" alt="PC Image 2">
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <p class="text-center">
                            @lang('messages.promise')
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

