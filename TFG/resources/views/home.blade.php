@extends('layouts.plantilla')

@section('title', "Home")

@section('content')
    @if(auth()->user()->hasRole('admin'))
        @foreach($areas as $area)
            <a href="{{ route('show.area', $area->id) }}" class="btn btn-primary" role="button">Acceder a área {{ $area->area_name }}</a>
        @endforeach
    @endif

    <a href="{{route('availability')}}" class="btn btn-primary" role="button">Ver mis disponibilidades</a>
@endsection


