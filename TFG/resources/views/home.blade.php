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
          events:'/events',
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
                        url:'/event/'+eventId,
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
            let eventId=info.event.id;
            let newStartDate=info.event.start;
            let newEndDate=info.event.end||newStartDate;
            let newStarDateUTC=newStartDate.toISOString().slice(0,10);
            let newEndDateUTC=newEndDate.toISOString().slice(0,10);

            $.ajax({
                method:'PUT',
                url:`/event/${eventId}`,
                data:{
                    start_date:newStarDateUTC,
                    end_date:newEndDateUTC,
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
      });

    </script>
@endsection



