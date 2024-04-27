@extends('layouts.plantilla')

@section('title', "Dashboard")

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Perfil de Usuario
                    <a href="#editUserModal" data-bs-toggle="modal" data-bs-target="#editUserModal" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
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
                        <p><strong>Nombre:</strong> {{ $user->name }} {{ $user->surname }}</p>
                        <p><strong>Nombre usuario:</strong> {{ $user->username }}</p>
                        <p><strong>Correo Electrónico:</strong> {{ $user->email }}</p>
                        <p><strong>Cargo:</strong> {{ $user->cargo }}</p>
                        @if ($user->area)
                            <p><strong>Área:</strong> {{ $user->area->area_name }}</p>
                        @else
                            <p><strong>Área:</strong> No asignada</p>
                        @endif
                    </div>
                    <h4>Turnos Asignados</h4>
                    <ul>
                        @foreach($user->tourns as $turno)
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
                <div class="card-footer">
                    <!-- Formulario para salir de la sesión -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger">Salir de la sesión</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editUserModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Editando perfil</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('updateUser', $user->id) }}" method="POST">
                    @method('PUT')
                    @csrf
                    {{-- Cláusula para obtener un token de formulario al enviarlo --}}
                    <label for="name">Nombre</label>
                    <input type="text" name="name" class="form-control mb-2" value="{{ $user->name }}" placeholder="{{ $user->name }}" autofocus>

                    <label for="surname">Apellidos</label>
                    <input type="text" name="surname" class="form-control mb-2" value="{{ $user->surname }}" placeholder="{{ $user->surname }}" autofocus>

                    <label for="username">Nombre de usuario</label>
                    <input type="text" name="username" class="form-control mb-2" value="{{ $user->username }}" placeholder="{{ $user->username }}" autofocus>

                    <label for="email">Email</label>
                    <input type="text" name="email" class="form-control mb-2" value="{{ $user->email }}" placeholder="{{ $user->email }}" autofocus>

                    <button class="btn btn-secondary btn-block" type="submit" onclick="return confirm('¿Quieres actualizar tu perfil?')">Guardar cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
