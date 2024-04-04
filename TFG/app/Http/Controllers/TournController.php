<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Models\Tourn;
use App\Models\Week;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TournController extends Controller
{
    public function index()
    {
        return view('tourns');
    }

    public function fillTourns(){
        $availabilities= Availability::all();

        foreach ($availabilities as $availability){
            Tourn::firstOrCreate([
                'n_day' => $availability->n_day,
                'type_turn' => $availability->avaibility,
                'user_id'=> $availability->user_id,
                'week_id' => $availability->week_id
            ]);
        }
        return response()->json(['message' => 'Turnos creados exitosamente']);
    }

    public function getTourns(){
        $tourns= Tourn::all();
        $events=[];
        foreach($tourns as $tourn){
        $year = $tourn->week->year;
        $weekNumber = $tourn->week->n_week;

        // Calcular la fecha del primer día de la semana segun el año
        $startOfWeek = Carbon::now()->setISODate($year, $weekNumber)->startOfWeek();

        // Calcular la fecha del día de la disponibilidad segun el día de la semana
        $availabilityDate = $startOfWeek->copy()->addDays($tourn->n_day - 1);
            $events[]=[
                'title'=> $tourn->type_turn,
                'start'=> $availabilityDate->copy()->setTimeFromTimeString('08:00'),
                'end'=>$availabilityDate->copy()->setTimeFromTimeString('12:00'),
                'id'=>$tourn->id
            ];
        }
        return response()->json($events);
    }

    public function deleteTourn($id){
        $tourn=Tourn::findOrFail($id);
        $tourn->delete();
        return response()->json(['message'=>'Evento eliminado exitosamente']);
    }

    public function updateTourn(Request $request, $id){
        $tourn=Tourn::findOrFail($id);
        $year = $request->year;
        $weekNumber = $request->weekNumber;
        $dayOfWeek = $request->dayOfWeek == 0 ? 7 : $request->dayOfWeek ;
        $week = Week::updateOrCreate(
            ['year' => $year, 'n_week' => $weekNumber],
            ['year' => $year, 'n_week' => $weekNumber]
        );
        $tourn->update([
            'n_day' => $dayOfWeek,// Sumar 1 ya que el índice de los días de la semana comienza en 0
            'week_id' => $week->id
        ]);
        return response()->json(['message'=>'El evento se ha modificado']);
    }
}
