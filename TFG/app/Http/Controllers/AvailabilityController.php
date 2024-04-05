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
                case 'manana':
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

        $area= Auth::user()->area;

        $startHour = $request->startHour;
        $endHour = Carbon::parse($request->endHour);
        
        //Con la hora recibida comparamos si corresponde a algun turno
        switch ($startHour) {
            case $area->mañana_start_time:
                    $availability->update([
                        'n_day' => $dayOfWeek,
                        'avaibility' => 'manana',
                        'week_id' => Week::updateOrCreate(['year' => $year, 'n_week' => $weekNumber], ['year' => $year, 'n_week' => $weekNumber])->id
                    ]);
                    return response()->json(['message' => 'El evento se ha modificado']);

                break;
            case $area->tarde_start_time:
                    // Las horas coinciden con el turno de la tarde del área
                    $availability->update([
                        'n_day' => $dayOfWeek,
                        'avaibility' => 'tarde',
                        'week_id' => Week::updateOrCreate(['year' => $year, 'n_week' => $weekNumber], ['year' => $year, 'n_week' => $weekNumber])->id
                    ]);
                    return response()->json(['message' => 'El evento se ha modificado']);
                break;
            case $area->noche_start_time:
                    // Las horas coinciden con el turno de la noche del área
                    $availability->update([
                        'n_day' => $dayOfWeek,
                        'avaibility' => 'noche',
                        'week_id' => Week::updateOrCreate(['year' => $year, 'n_week' => $weekNumber], ['year' => $year, 'n_week' => $weekNumber])->id
                    ]);
                    return response()->json(['message' => 'El evento se ha modificado']);
                break;
            default:
                return response()->json(['message' => 'El evento no se puede modificar'], 400);
        }
    }
}
