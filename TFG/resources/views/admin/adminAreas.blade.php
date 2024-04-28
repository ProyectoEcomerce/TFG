@extends('layouts.plantilla')

@section('title', "Admin Areas")

@section('content')


<div class="container mt-5">
    <div class="d-flex justify-content-center">
        <a href="#" data-bs-toggle="modal" data-bs-target="#createAreaModal" class="btn btn-success mb-5"><i class="fas fa-plus"></i> Crear area</a>
    </div>
</div>


<div class="container mt-1 mb-4">
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach($areas as $area)
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        {{$area->area_name}}
                        <a href="#editAreaModal{{$area->id}}" data-bs-toggle="modal" data-bs-target="#editAreaModal{{$area->id}}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                    </div>
    
                    <div class="card-body">
    
                        <div class="profile-info">
                            <p><strong>Turno de mañana:</strong> {{ substr($area->mañana_start_time,0,-3) }}-{{ substr($area->mañana_end_time,0,-3) }}</p>
                            <p><strong>Turno de tarde:</strong> {{ substr($area->tarde_start_time,0,-3) }}-{{ substr($area->tarde_end_time,0,-3) }}</p>
                            <p><strong>Turno de noche:</strong> {{ substr($area->noche_start_time,0,-3) }}-{{ substr($area->noche_end_time,0,-3) }}</p>
                            <a href="{{ route('show.area', $area->id) }}" class="btn btn-primary" role="button">Acceder a área {{ $area->area_name }}</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@foreach ($areas as $area)
    <div class="modal fade" id="editAreaModal{{$area->id}}">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Editando el area: {{ $area->area_name }}</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('updateArea', $area->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        {{-- Cláusula para obtener un token de formulario al enviarlo --}}
                        <label for="area">Nombre del area</label>
                        <input type="text" name="area" class="form-control mb-2" value="{{ $area->area_name }}" placeholder="Nombre del area" autofocus>

                        <label for="mañana_start_time">Comienzo turno mañana</label>
                        <input type="time" name="mañana_start_time" class="form-control mb-2" value="{{ $area->mañana_start_time }}" placeholder="Comienzo turno mañana" autofocus>

                        <label for="mañana_end_time">Final turno mañana</label>
                        <input type="time" name="mañana_end_time" class="form-control mb-2" value="{{ $area->mañana_end_time }}" placeholder="Final turno mañana" autofocus>

                        <label for="tarde_start_time">Comienzo turno tarde</label>
                        <input type="time" name="tarde_start_time" class="form-control mb-2" value="{{ $area->tarde_start_time }}" placeholder="Comienzo turno tarde" autofocus>

                        <label for="tarde_end_time">Final turno tarde</label>
                        <input type="time" name="tarde_end_time" class="form-control mb-2" value="{{ $area->tarde_end_time }}" placeholder="Final turno tarde" autofocus>

                        <label for="noche_start_time">Comienzo turno noche</label>
                        <input type="time" name="noche_start_time" class="form-control mb-2" value="{{ $area->noche_start_time }}" placeholder="Comienzo turno noche" autofocus>

                        <label for="noche_end_time">Final turno noche</label>
                        <input type="time" name="noche_end_time" class="form-control mb-2" value="{{ $area->noche_end_time }}" placeholder="Final turno noche" autofocus>
                        
                        <button class="btn btn-secondary btn-block" type="submit" onclick="return confirm('¿Quieres editar el area '+'{{ $area->name}}'+'?')">Guardar cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

<div class="modal fade" id="createAreaModal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Crear area</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </button>
        </div>
        <div class="modal-body">
          <form action="{{ route('createArea') }}" method="POST">
            @csrf
            <label for="area">Nombre del area</label>
            <input type="text" name="area" class="form-control mb-2" placeholder="Nombre del area" autofocus>
            <label for="mañana_start_time">Comienzo turno mañana</label>
            <input type="time" name="mañana_start_time" class="form-control mb-2" placeholder="Comienzo turno mañana" autofocus>
            <label for="mañana_end_time">Final turno mañana</label>
            <input type="time" name="mañana_end_time" class="form-control mb-2" placeholder="Final turno mañana" autofocus>
            <label for="tarde_start_time">Comienzo turno tarde</label>
            <input type="time" name="tarde_start_time" class="form-control mb-2" placeholder="Comienzo turno tarde" autofocus>
            <label for="tarde_end_time">Final turno tarde</label>
            <input type="time" name="tarde_end_time" class="form-control mb-2" placeholder="Final turno tarde" autofocus>
            <label for="noche_start_time">Comienzo turno noche</label>
            <input type="time" name="noche_start_time" class="form-control mb-2" placeholder="Comienzo turno noche" autofocus>
            <label for="noche_end_time">Final turno noche</label>
            <input type="time" name="noche_end_time" class="form-control mb-2" placeholder="Final turno noche" autofocus>
            <button class="btn btn-secondary btn-block" type="submit" onclick="return confirm('¿Quieres crear esta area?')">
                Guarda area
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

@endsection
