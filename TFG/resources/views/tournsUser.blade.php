@extends('layouts.plantilla')

@section('title', "Mis turnos")

@section('content')

<div class="container mt-5">
  <div class="d-flex  flex-column align-items-center">
      <h2>Turnos del area:{{$area->area_name}}</h2>
  </div>
</div>

<div class="container mt-5">
  <div class="card">
      <div class="card-body">
          <div id='calendar'></div>
      </div>
  </div>
</div>

@endsection

@section('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar:{
                left:'prev,next today', 
                center:'title',
                right:'dayGridMonth, timeGridWeek, timeGridDay'
            },
          initialView: 'dayGridMonth',
          timeZone: 'UTC + 01:00',
          events:'/getTourns/{{$area->id}}',
          slotEventOverlap: false,
          editable: true,
          eventClassNames: function(arg) {
            // Agrega una clase específica en función del tipo de turno
            let classNames = [];
            if (arg.event.extendedProps.typeTurn) {
                classNames.push('turno-' + arg.event.extendedProps.typeTurn);
            }
            return classNames;
          },
          eventContent:function(info){
            const eventTitle=info.event.title;
            const eventElement=document.createElement('div');
            eventElement.innerHTML=eventTitle;
            return {
                domNodes:[eventElement]
            };
          }
        });
        calendar.render();
      });
    </script>
@endsection