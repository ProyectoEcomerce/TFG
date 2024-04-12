@extends('layouts.plantilla')

@section('title', "Home")

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-center">
        <a href="#" data-bs-toggle="modal" data-bs-target="#createAvailabilityModal" class="btn btn-success mb-5"><i class="fas fa-plus"></i> Crear disponibilidad</a>
    </div>
    <div class="card">
        <div class="card-body">
            <div id='calendar'></div>
        </div>
    </div>
</div>

<div class="modal fade" id="createAvailabilityModal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Crear solicitud de turno</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </button>
        </div>
        <div class="modal-body">
          <form id="availabilityForm" method="POST">
            @csrf
            <label>Dia disponible</label>
            <input type="date" id="dateAvai" name="dateAvai" class="form-control mb-2" required>
            <label>Tipo de turno</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="typeTurn" id="turno1" value="manana">
                <label class="form-check-label" for="turno1">Mañana</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="typeTurn" id="turno2" value="tarde">
                <label class="form-check-label" for="turno2">Tarde</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="typeTurn" id="turno3" value="noche">
                <label class="form-check-label" for="turno3">Noche</label>
            </div>
            <button class="btn btn-secondary btn-block" type="submit" onclick="return confirm('¿Quieres crear esta disponibilidad?')">
                Guarda disponibilidad
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
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

            // Convertir la fecha a hora local
            let startDate = new Date(newStartDate);
            let endDate = new Date(newEndDate);

            let startDateLocal = new Date(startDate.getTime() + startDate.getTimezoneOffset() * 60000);
            let endDateLocal = new Date(endDate.getTime() + endDate.getTimezoneOffset() * 60000);

            // Convertir la fecha de inicio del evento a un objeto Carbon
            let startDateCarbon = moment(startDateLocal);
            let endDateCarbon = moment(endDateLocal);

            // Obtener el año y el número de semana
            let year = startDateCarbon.year();
            let weekNumber = startDateCarbon.isoWeek();
        
            // Obtener el número del día de la semana (0 para domingo, 1 para lunes, etc.)
            let dayOfWeek = startDateCarbon.isoWeekday();

            //Sacar horas y minutos en el formato correcto
            let startHoursLocal = startDateLocal.getHours();
            let startMinutesLocal = startDateLocal.getMinutes();
            let startTime = startHoursLocal.toString().padStart(2, '0') + ":" + startMinutesLocal.toString().padStart(2, '0') + ":" + "00";
            
            let endHoursLocal = endDateLocal.getHours();
            let endMinutesLocal = endDateLocal.getMinutes();
            let endTime = endHoursLocal.toString().padStart(2, '0') + ":" + endMinutesLocal.toString().padStart(2, '0') + ":" + "00";
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
                    calendar.refetchEvents();
                },
                error:function(error){
                    alert('No hay turnos en este horario');
                }
            });
          }
        });
        calendar.render();
        $('#availabilityForm').submit(function(e) {
            e.preventDefault();
            let selectedDate = $('#dateAvai').val();
    
            // Convertir la fecha seleccionada a un objeto Date
            let dateObject = new Date(selectedDate);

            // Obtener la fecha en formato Carbon
            let dateCarbon= moment(selectedDate);
            
            // Obtener el día de la semana (0 para domingo, 1 para lunes, etc.)
            let dayOfWeek = dateObject.getDay();
        
            let year = dateCarbon.year();
            let weekNumber = dateCarbon.isoWeek();
            
            let selectedTurns = [];
            $('input[name="typeTurn"]:checked').each(function() {
                selectedTurns.push($(this).val());
            });
            $.ajax({
                method: 'POST',
                url: '/create-availability',
                data: {
                    dayOfWeek: dayOfWeek,
                    weekNumber: weekNumber,
                    year: year,
                    typeTurn: selectedTurns
                },
                success: function(response) {
                    console.log(response.message);
                    $('#createAvailabilityModal').modal('hide');
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