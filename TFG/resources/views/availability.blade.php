@extends('layouts.plantilla')

@section('title', "Home")

@section('content')
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
          initialView: 'timeGridWeek',
          timeZone: 'UTC + 01:00',
          events:'/getAvailability',
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
                        url:'/deleteAvailability/'+eventId,
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
            //Sacamos el id del evento y las fechas a las que empieza y acaba
            let eventId = info.event.id;
            let newStartDate = info.event.start;
            let newEndDate = info.event.end;
        
            // Convertir la fecha de inicio del evento a un objeto Date
            let startDate = new Date(newStartDate);
            let endDate = new Date(newEndDate);
        
            // Convertir la fecha de inicio del evento a un objeto Carbon
            let startDateCarbon = moment(startDate);
            let endDateCarbon = moment(endDate);
        
            // Obtener el año y el número de semana
            let year = startDateCarbon.year();
            let weekNumber = startDateCarbon.isoWeek();
        
            // Obtener el número del día de la semana (0 para domingo, 1 para lunes, etc.)
            let dayOfWeek = startDate.getDay();

            //Sacar horas y minutos en el formato correcto
            let startHoursUTC = newStartDate.getUTCHours();
            let startMinutesUTC = newStartDate.getUTCMinutes();
            let startTime = startHoursUTC.toString().padStart(2, '0') + ":" + startMinutesUTC.toString().padStart(2, '0') + ":" + "00";
            
            let endHoursUTC = newEndDate.getUTCHours();
            let endMinutesUTC = newEndDate.getUTCMinutes();
            let endTime = endHoursUTC.toString().padStart(2, '0') + ":" + endMinutesUTC.toString().padStart(2, '0') + ":" + "00";

            $.ajax({
                method:'PUT',
                url:`/updateAvailability/${eventId}`,
                data:{
                    year: year,
                    weekNumber: weekNumber,
                    dayOfWeek: dayOfWeek,
                    startHour: startTime,
                    endHour: endTime
                },
                success:function()
                {
                    console.log('Se ha movido el evento');
                },
                error:function(error){
                    alert('No hay turnos en este horario');
                }
            });
          }
        });
        calendar.render();
      });

    </script>
@endsection