@extends('layouts.plantilla')

@section('title', "Turnos")

@section('content')
<div class="container mt-5">
    <a href="#" data-bs-toggle="modal" data-bs-target="#fillTurnosModal" class="btn btn-success mb-5">Llenar con disponibilidades</a>
    <a href="#" data-bs-toggle="modal" data-bs-target="#deleteTurnosModal" class="btn btn-success mb-5">Eliminar intervalo turnos</a>
    <div class="d-flex justify-content-center">
        <a href="#" data-bs-toggle="modal" data-bs-target="#createTournModal" class="btn btn-success mb-5"><i class="fas fa-plus"></i> Crear nuevo turno</a>
    </div>
    <div class="card">
        <div class="card-body">
            <div id='calendar'></div>
        </div>
    </div>
</div>

<div class="modal fade" id="createTournModal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Crear turno</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </button>
        </div>
        <div class="modal-body">
          <form id="tournForm" method="POST">
            @csrf
            <label>Seleccionar usuario</label>
            <select id="nameUser" name="nameUser" class="form-control mb-2" required>
                @foreach($users as $user)
                    <option value="{{$user->name}}">{{$user->name}}</option>
                @endforeach
            </select>
            <label>Dia disponible</label>
            <input type="date" id="dateTourn" name="dateTourn" class="form-control mb-2" required>
            <label>Tipo de turno</label>
            <select name="typeTurn" id="typeTurn">
                <option value="manana">Mañana</option>
                <option value="tarde">Tarde</option>
                <option value="noche">Noche</option>
            </select>
            <button class="btn btn-secondary btn-block" type="submit" onclick="return confirm('¿Quieres crear este turno?')">
                Guarda Turno
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="fillTurnosModal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Selecciona el intervalo para llenar</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </button>
        </div>
        <div class="modal-body">
          <form id="fillTournForm" method="POST">
            @csrf
            <label>Inicio intervalo</label>
            <input type="date" id="startInterval" name="startInterval" class="form-control mb-2" required>
            <label>Final intervalo</label>
            <input type="date" id="endInterval" name="endInterval" class="form-control mb-2" required>
            <button class="btn btn-secondary btn-block" type="submit" onclick="return confirm('Esta acción llenara los turnos con todas las disponibilidades no duplicadas, ¿Quieres continuar?')">
                Llenar intervalo
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="deleteTurnosModal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Selecciona el intervalo para eliminar</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </button>
        </div>
        <div class="modal-body">
          <form id="deleteTournForm" method="POST">
            @csrf
            <label>Inicio intervalo</label>
            <input type="date" id="startIntervalDelete" name="startIntervalDelete" class="form-control mb-2" required>
            <label>Final intervalo</label>
            <input type="date" id="endIntervalDelete" name="endIntervalDelete" class="form-control mb-2" required>
            <button class="btn btn-secondary btn-block" type="submit" onclick="return confirm('Esta acción eliminara los turnos del intervalo seleccionado, ¿Quieres continuar?')">
                Eliminar turnos
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
                url:`/updateTourns/${eventId}`,
                data:{
                    year: year,
                    weekNumber: weekNumber,
                    dayOfWeek: dayOfWeek,
                    startHour: startTime,
                    endHour: endTime,
                    areaId: {{$area->id}}
                },
                success:function()
                {
                    console.log('Se ha movido el evento');
                    $('#fillTurnosModal').modal('hide');
                    calendar.refetchEvents();
                },
                error:function(error){
                    alert('No hay turnos en este horario');
                }
            });
          }
        });
        calendar.render();
        $('#fillTournForm').submit(function(e) {
            e.preventDefault();
            let startDate = $('#startInterval').val();
            let endDate = $('#endInterval').val();

            $.ajax({
                method: 'POST',
                url: '/fill-tourns/{{$area->id}}',
                data:{
                    startDate : startDate,
                    endDate : endDate
                },
                success: function(response) {
                    console.log(response.message);
                    $('#deleteTurnosModal').modal('hide');
                    calendar.refetchEvents();
                },
                error: function(xhr, status, error) {
                    console.error('Error al llenar los turnos', error);
                }
            });
        });
        $('#deleteTournForm').submit(function(e) {
            e.preventDefault();
            let startDate = $('#startIntervalDelete').val();
            let endDate = $('#endIntervalDelete').val();

            $.ajax({
                method: 'POST',
                url: '/deleteIntervaTourns/{{$area->id}}',
                data:{
                    startDate : startDate,
                    endDate : endDate
                },
                success: function(response) {
                    console.log(response.message);
                    $('#deleteTurnosModal').modal('hide');
                    calendar.refetchEvents();
                    
                },
                error: function(xhr, status, error) {
                    console.error('Error al llenar los turnos', error);
                }
            });
        });
        $('#tournForm').submit(function(e) {
            e.preventDefault();
            let selectedDate = $('#dateTourn').val();
    
            // Convertir la fecha seleccionada a un objeto Date
            let dateObject = new Date(selectedDate);

            // Obtener la fecha en formato Carbon
            let dateCarbon= moment(selectedDate);
            
            // Obtener el día de la semana (0 para domingo, 1 para lunes, etc.)
            let dayOfWeek = dateObject.getDay();
        
            let year = dateCarbon.year();
            let weekNumber = dateCarbon.isoWeek();
            
            let selectedTurns = $('#typeTurn').val();

            let userName = $('#nameUser').val();
            $.ajax({
                method: 'POST',
                url: '/create-tourn',
                data: {
                    dayOfWeek: dayOfWeek,
                    weekNumber: weekNumber,
                    year: year,
                    typeTurn: selectedTurns,
                    userName: userName
                },
                success: function(response) {
                    console.log(response.message);
                    $('#createTournModal').modal('hide');
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