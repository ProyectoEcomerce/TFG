<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function getAvailability(){
        $availabilities= Availability::all();
        $events=[];
        foreach($availabilities as $availability){
            $events[]=[
                'title'=> $availability->avaibility,
                'start'=> '2024-04-03 08:00',
                'end'=>'2024-04-04 11:00',
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
        $availability->update([
            'start'=>Carbon::parse($request->input('start_date'))->setTimeZone('UTC'),
            'end'=>Carbon::parse($request->input('end_date'))->setTimeZone('UTC'),
        ]);
        return response()->json(['message'=>'El evento se ha modificado']);
    }
}
