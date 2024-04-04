<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Models\Week;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AvailabilityController extends Controller
{
    public function getAvailability(){
        $availabilities= Availability::all();
        $events=[];
        foreach($availabilities as $availability){
        $year = $availability->week->year;
        $weekNumber = $availability->week->n_week;

        // Calcular la fecha del primer día de la semana segun el año
        $startOfWeek = Carbon::now()->setISODate($year, $weekNumber)->startOfWeek();

        // Calcular la fecha del día de la disponibilidad segun el día de la semana
        $availabilityDate = $startOfWeek->copy()->addDays($availability->n_day - 1);
            $events[]=[
                'title'=> $availability->avaibility,
                'start'=> $availabilityDate->copy()->setTimeFromTimeString('08:00'),
                'end'=>$availabilityDate->copy()->setTimeFromTimeString('12:00'),
                'id'=>$availability->id
            ];
        }
        return response()->json($events);
    }

    public function deleteAvailability($id){
        $availability=Availability::findOrFail($id);
        $availability->delete();
        return response()->json(['message'=>'Evento eliminado exitosamente']);
    }

    public function updateAvailability(Request $request, $id){
        $availability=Availability::findOrFail($id);
        $year = $request->year;
        $weekNumber = $request->weekNumber;
        $dayOfWeek = $request->dayOfWeek;
        $week = Week::updateOrCreate(
            ['year' => $year, 'n_week' => $weekNumber],
            ['year' => $year, 'n_week' => $weekNumber]
        );
        $availability->update([
            'n_day' => $dayOfWeek,// Sumar 1 ya que el índice de los días de la semana comienza en 0
            'week_id' => $week->id
        ]);
        return response()->json(['message'=>'El evento se ha modificado']);
    }
}
