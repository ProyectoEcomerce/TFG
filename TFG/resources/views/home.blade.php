@extends('layouts.plantilla')

@section('title', "Home")

@section('content')
    @foreach($areas as $area)
        <a href="{{route('show.area', $area->id)}}" class="btn btn-primary" role="button">Acceder a area {{$area->area_name}}</a>
    @endforeach
    <a href="{{route('availability')}}" class="btn btn-primary" role="button">Ver mis disponibilidades</a>
@endsection


