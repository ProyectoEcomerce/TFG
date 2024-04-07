@extends('layouts.plantilla')

@section('title', "Turnos")

@section('content')
<div class="container mt-5">
    <button id="fillTurnosButton">Llenar Turnos</button>
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
          editable: true,

          //Eliminar
          eventContent:function(info){
            const eventTitle=info.event.title;
            const eventElement=document.createElement('div');
            eventElement.innerHTML='<span style="cursor:pointer;"> <i class="fas fa-trash-alt"></i> </span>' + eventTitle;

            eventElement.querySelector('span').addEventListener('click', function(){
                if(confirm("¿Quieres eliminar este evento?")){
                    let eventId=info.event.id;
                    $.ajax({
                        method:'DELETE',
                        url:'/deleteTourns/'+eventId,
                        success:function(response){
                            console.log('Se elimino' + eventId);
                            calendar.refetchEvents();
                        },
                        error:function(error){
                            console.log('Error al eliminar el evento', error);
                        }
                    });
                }
            });
            return {
                domNodes:[eventElement]
            };
          },

          //Arrastrar eventos

          eventDrop:function(info){
            let eventId = info.event.id;
            let newStartDate = info.event.start;
        
            // Convertir la fecha de inicio del evento a un objeto Date
            let startDate = new Date(newStartDate);
        
            // Convertir la fecha de inicio del evento a un objeto Carbon
            let startDateCarbon = moment(startDate);
        
            // Obtener el año y el número de semana
            let year = startDateCarbon.year();
            let weekNumber = startDateCarbon.isoWeek();
        
            // Obtener el número del día de la semana (0 para domingo, 1 para lunes, etc.)
            let dayOfWeek = startDate.getDay();
            $.ajax({
                method:'PUT',
                url:`/updateTourns/${eventId}`,
                data:{
                    year: year,
                    weekNumber: weekNumber,
                    dayOfWeek: dayOfWeek,
                },
                success:function()
                {
                    console.log('Se ha movido el evento');
                },
                error:function(error){
                    console.log('Error al mover el evento', error);
                }
            });
          }
        });
        calendar.render();
        $('#fillTurnosButton').click(function() {
            $.ajax({
                method: 'POST',
                url: '/fill-tourns/{{$area->id}}',
                success: function(response) {
                    console.log(response.message);
                    calendar.refetchEvents();
                },
                error: function(xhr, status, error) {
                    console.error('Error al llenar los turnos', error);
                }
            });
        });
      });


    </script>
@endsection