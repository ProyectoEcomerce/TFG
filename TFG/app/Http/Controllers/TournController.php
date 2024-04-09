<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Availability;
use App\Models\Tourn;
use App\Models\User;
use App\Models\Week;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TournController extends Controller
{
    public function index($id)
    {
        $area = Area::findOrFail($id);
        $users= User::where('area_id', $id)->get();
        return view('tourns', compact('area', 'users'));
    }

    public function fillTourns(Request $request, $id){
        DB::beginTransaction();
        try{
            $startDate = $request->startDate;
            $endDate = $request->endDate;
        
            // Convertir las fechas de inicio y fin a objetos Carbon
            $startCarbon = Carbon::parse($startDate);
            $endCarbon = Carbon::parse($endDate);

            $users= User::where('area_id', $id)->get();
            $availabilities = [];

            //Buscamos las disponibilidades de los usuarios que coincidan con el intervalo
            foreach ($users as $user) {
                $userAvailabilities = $user->availability()
                ->whereHas('week', function ($query) use ($startCarbon, $endCarbon) {
                    $query->whereBetween('year', [$startCarbon->year, $endCarbon->year])
                          ->whereBetween('n_week', [$startCarbon->isoWeek(), $endCarbon->isoWeek()]);
                })
                ->get();

            if ($userAvailabilities->isNotEmpty()) {
                $availabilities[$user->id] = $userAvailabilities->toArray();
            }

            }
            //Iteramos por las disponibilidades asociadas a cada usuario y luego por sus propias disponibilidades
            foreach ($availabilities as $userId => $userAvailabilities) {
                foreach ($userAvailabilities as $availability) {
                    Tourn::firstOrCreate([
                        'n_day' => $availability['n_day'],
                        'type_turn' => $availability['avaibility'],
                        'user_id'=> $availability['user_id'],
                        'week_id' => $availability['week_id']
                    ]);
                }
            }
            DB::commit();
            return response()->json(['message' => 'Turnos creados exitosamente']);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(['message' => 'No se han podido crear los turnos']);
        }
    }

    public function createTourn(Request $request){
        DB::beginTransaction();
        try{
            $user= User::where('name', $request->userName)->first();
            $week=Week::firstOrCreate([
                'n_week'=>$request->weekNumber,
                'year'=>$request->year
            ]);
            $newTourn = new Tourn();
            $newTourn->n_day =$request->dayOfWeek == 0 ? 7 : $request->dayOfWeek;
            $newTourn->type_turn= $request->typeTurn;
            $newTourn->user_id = $user->id;
            $newTourn->week_id =$week->id;
            $newTourn->save();

            DB::commit();
            return response()->json(['message' => 'Turnos creados exitosamente']);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(['message' => 'No se han podido crear los turnos']);
        }
    }

    public function getTourns($id){
        $users= User::where('area_id', $id)->get();
        $area= Area::findOrFail($id);
        $events=[];

        foreach($users as $user){
            $tourns = $user->tourns()->get();
            foreach($tourns as $tourn){
                $year = $tourn->week->year;
                $weekNumber = $tourn->week->n_week;
        
                // Calcular la fecha del primer día de la semana segun el año
                $startOfWeek = Carbon::now()->setISODate($year, $weekNumber)->startOfWeek();
        
                // Calcular la fecha del día de la disponibilidad segun el día de la semana
                $tournDate = $startOfWeek->copy()->addDays($tourn->n_day - 1);

                $tournStart = null;
                $tournEnd = null;
                $tournDateEnd=null;
                switch ($tourn->type_turn) {
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
                        if (Carbon::parse($tournEnd)->greaterThan(Carbon::parse('00:00'))) {
                            $tournDateEnd = $tournDate->copy(); // Creas una copia de la fecha de inicio
                            $tournDateEnd->addDay(); // Le sumas un día a la fecha de inicio
                        }
                        break;
                    default:
                        break;
                }
                    $events[]=[
                        'title'=> "Turno de " . $tourn->type_turn . " de " . $tourn->user->name,
                        'start'=> $tournDate->copy()->setTimeFromTimeString($tournStart),
                        'end' => $tournDateEnd ? $tournDateEnd->copy()->setTimeFromTimeString($tournEnd) : $tournDate->copy()->setTimeFromTimeString($tournEnd),
                        'id'=>$tourn->id
                    ];
            }
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

        $area= Area::findOrFail($request->areaId);

        $startHour = $request->startHour;
        $endHour = Carbon::parse($request->endHour);
        
        switch ($startHour) {
            case $area->mañana_start_time:
                    $tourn->update([
                        'n_day' => $dayOfWeek,
                        'type_turn' => 'manana',
                        'week_id' => Week::updateOrCreate(['year' => $year, 'n_week' => $weekNumber], ['year' => $year, 'n_week' => $weekNumber])->id
                    ]);
                    return response()->json(['message' => 'El evento se ha modificado']);

                break;
            case $area->tarde_start_time:
                    // Las horas coinciden con el turno de la tarde del área
                    $tourn->update([
                        'n_day' => $dayOfWeek,
                        'type_turn' => 'tarde',
                        'week_id' => Week::updateOrCreate(['year' => $year, 'n_week' => $weekNumber], ['year' => $year, 'n_week' => $weekNumber])->id
                    ]);
                    return response()->json(['message' => 'El evento se ha modificado']);
                break;
            case $area->noche_start_time:
                    // Las horas coinciden con el turno de la noche del área
                    $tourn->update([
                        'n_day' => $dayOfWeek,
                        'type_turn' => 'noche',
                        'week_id' => Week::updateOrCreate(['year' => $year, 'n_week' => $weekNumber], ['year' => $year, 'n_week' => $weekNumber])->id
                    ]);
                    return response()->json(['message' => 'El evento se ha modificado']);
                break;
            default:
                return response()->json(['message' => 'El evento no se puede modificar'], 400);
        }
    }
}
