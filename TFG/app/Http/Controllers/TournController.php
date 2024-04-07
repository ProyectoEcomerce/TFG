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

    public function fillTourns($id){
        $users= User::where('area_id', $id)->get();
        foreach($users as $user){
            $availabilities = $user->availability()->get();
            foreach ($availabilities as $availability){
                Tourn::firstOrCreate([
                    'n_day' => $availability->n_day,
                    'type_turn' => $availability->avaibility,
                    'user_id'=> $availability->user_id,
                    'week_id' => $availability->week_id
                ]);
            }
        }
        return response()->json(['message' => 'Turnos creados exitosamente']);
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
                    $events[]=[
                        'title'=> "Turno de " . $tourn->type_turn . " de " . $tourn->user->name,
                        'start'=> $tournDate->copy()->setTimeFromTimeString('08:00'),
                        'end'=>$tournDate->copy()->setTimeFromTimeString('12:00'),
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
