@extends('layouts.plantilla')

@section('title', "Admin")

@section('content')

<div class="container mt-4 mb-4">
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach($users as $user)
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        Perfil de Usuario
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
                    <form action="{{ route('updateUser', $user->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        {{-- Cláusula para obtener un token de formulario al enviarlo --}}
                        <label for="name">Area asignada</label>
                        <input type="text" name="name" class="form-control mb-2" value="{{ $user->name }}"
                        placeholder="Nombre" autofocus>
                        <button class="btn btn-secondary btn-block" type="submit" onclick="return confirm('¿Quieres editar al usuario '+'{{ $user->name}}'+'?')">Guardar cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

@endsection


