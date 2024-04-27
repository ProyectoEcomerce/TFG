@extends('layouts.plantilla')

@section('title', "Admin Usuarios")

@section('content')

<div class="container mt-4 mb-4 justify-content-center justify-center">
    <div class="row">
        <div class="col-md-6 mb-3">
            <form action="{{ route('usersFilter') }}" method="GET">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <input type="text" name="searchText" class="form-control" placeholder="Buscar por nombre de usuario">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">Buscar</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <form action="{{ route('areaFilter') }}" method="GET">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <select name="areaId" class="form-control">
                            <option value="">Selecciona un área</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}">{{ $area->area_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">Filtrar por área</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="container mt-4 mb-4">
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach($users as $user)
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        {{ $user->username }}
                    </div>
    
                    <div class="card-body">
                        <div class="profile-info">
                            <p><strong>Nombre:</strong> {{ $user->name }} {{ $user->surname }}</p>
                            <p><strong>Correo Electrónico:</strong> {{ $user->email }}</p>
                            <p><strong>Cargo:</strong> {{ $user->cargo }}</p>
                            <p><strong>Área:</strong> {{ $user->area->area_name}}</p>
                            <a href="#editUserModal{{$user->id}}" data-bs-toggle="modal" data-bs-target="#editUserModal{{$user->id}}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@foreach ($users as $user)
    <div class="modal fade" id="editUserModal{{$user->id}}">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Editando al usuario: {{ $user->name }}</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('updateUserAdmin', $user->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        {{-- Cláusula para obtener un token de formulario al enviarlo --}}
                        <label for="name">Nombre</label>
                        <input type="text" name="name" class="form-control mb-2" value="{{ $user->name }}" placeholder="{{ $user->name }}" autofocus>

                        <label for="surname">Apellidos</label>
                        <input type="text" name="surname" class="form-control mb-2" value="{{ $user->surname }}" placeholder="{{ $user->surname }}" autofocus>

                        <label for="username">Nombre de usuario</label>
                        <input type="text" name="username" class="form-control mb-2" value="{{ $user->username }}" placeholder="{{ $user->username }}" autofocus>

                        <label for="cargo">Cargo</label>
                        <select name="cargo" class="form-control mb-2">
                            <option value="agente">Agente</option>
                            <option value="coordinador">Coordinador</option>
                        </select>
                        
                        <label for="area">Area asignada</label>
                        <select name="area" class="form-control mb-2">
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}">{{ $area->area_name }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-secondary btn-block" type="submit" onclick="return confirm('¿Quieres editar al usuario '+'{{ $user->name}}'+'?')">Guardar cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

@endsection


