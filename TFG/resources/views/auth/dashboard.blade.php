@extends('layouts.plantilla')

@section('title', "Dashboard")

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Perfil de Usuario
                </div>

                <div class="card-body">
                    <!-- Mostrar errores de validación -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Mostrar mensaje de éxito si hay alguno -->
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Mostrar imagen de perfil actual -->
                    @if(auth()->user()->profile_image)
                        <h4>Imagen de Perfil Actual:</h4>
                        <img src="{{ asset(auth()->user()->profile_image) }}" alt="Imagen de perfil" class="img-fluid">
                    @else
                        <p>No hay imagen de perfil</p>
                    @endif

                    <hr>

                    <div class="profile-info">
                        <p><strong>Nombre:</strong> {{ auth()->user()->name }} {{ auth()->user()->surname }}</p>
                        <p><strong>Correo Electrónico:</strong> {{ auth()->user()->email }}</p>
                        <p><strong>Cargo:</strong> {{ auth()->user()->cargo }}</p>
                        <p><strong>Área:</strong> {{ auth()->user()->area_id }}</p>
                    </div>
                    <h4>Turnos Asignados</h4>
                    <ul>
                        @foreach(auth()->user()->tourns as $turno)
                            <li>Día: {{ $turno->n_day }}, Tipo de Turno: {{ $turno->type_turn }}, Horas: {{ $turno->hours }}</li>
                        @endforeach
                    </ul>

                    <!-- Formulario para cargar una nueva imagen de perfil -->
                    <h4>Actualizar Imagen de Perfil</h4>
                    <form action="{{ route('user.uploadProfileImage') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="profile_image">Seleccionar una nueva imagen:</label>
                                <input type="file" class="form-control" id="profile_image" name="profile_image">
                            </div>
                        <button type="submit" class="btn btn-primary">Subir Imagen</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
