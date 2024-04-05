<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Models\Week;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AvailabilityController extends Controller
{

    public function index()
    {
        return view('availability');
    }

    public function getAvailability(){
        $user = Auth::user();
        $availabilities= Availability::where('user_id', $user->id)->get();
        $area = $user->area; //Area al que pertenece el user
        $events=[];
        foreach($availabilities as $availability){
            $year = $availability->week->year;
            $weekNumber = $availability->week->n_week;
    
            // Calcular la fecha del primer día de la semana segun el año
            $startOfWeek = Carbon::now()->setISODate($year, $weekNumber)->startOfWeek();
    
            // Calcular la fecha del día de la disponibilidad segun el día de la semana
            $availabilityDate = $startOfWeek->copy()->addDays($availability->n_day - 1);

            $tournStart = null;
            $tournEnd = null;
            switch ($availability->avaibility) {
                case 'mañana':
                    $tournStart = $area->mañana_start_time;
                    $tournEnd = $area->mañana_end_time;
                    break;
                case 'tarde':
                    $tournStart = $area->tarde_start_time;
                    $tournEnd = $area->tarde_end_time;
                    break;
                case 'noche':
                    $tournStart = $area->noche_start_time;
                    $tournEnd = $area->noche_end_time;
                    break;
                default:
                    break;
            }
            Log::info($tournStart);
                $events[]=[
                    'title'=>"Turno de " . $availability->avaibility . " de " . $availability->user->name,
                    'start'=> $availabilityDate->copy()->setTimeFromTimeString($tournStart),
                    'end'=>$availabilityDate->copy()->setTimeFromTimeString($tournEnd),
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
        $dayOfWeek = $request->dayOfWeek == 0 ? 7 : $request->dayOfWeek ;
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
