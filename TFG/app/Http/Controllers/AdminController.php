<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function getUsers(){
        $users= User::orderBy('id', 'desc')->get();
        $areas= Area::all();
        return view('admin' , compact('users', 'areas'));
    }

    public function updateUser(Request $request, $id){
        DB::beginTransaction();
        try{
            $request->validate([
                'area'=>'required'
            ]);
            $updateUser=User::findOrFail($id);
            $updateUser->area_id=$request->id;
            $updateUser->save();

            DB::commit();
            return back()->with('mensaje', 'Usuario editado exitosamente');
        }catch(\Exception $e){
            DB::rollBack();
            return back()->withErrors('No se pudo editar el usuario. Error: ' . $e->getMessage());
        }
    }

    public function filterUsers(Request $request){
        $searchText = $request->input('searchText');
        $areas= Area::all();

        $users = User::where('name', 'like', '%' . $searchText . '%')->get();

        return view('admin', compact('users', 'areas'));
    }

    public function areaFilter(Request $request){
        $areaId = $request->input('areaId');
        $areas= Area::all();

        $users = User::where('area_id', $areaId)->get();

        return view('admin', compact('users', 'areas'));
    }
}
