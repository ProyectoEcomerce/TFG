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
        return view('admin.adminUsers' , compact('users', 'areas'));
    }

    public function getAdmin(){
        return view('admin.admin');
    }

    public function updateUser(Request $request, $id){
        DB::beginTransaction();
        try{
            $request->validate([
                'name'=>'required|string|max:155',
                'surname'=>'required|string|max:255',
                'username' => 'required|string|unique:users,username,'.$id,
                'cargo'=>'required',
                'area'=>'required'
            ]);
            $updateUser=User::findOrFail($id);
            $updateUser->name=$request->name;
            $updateUser->surname=$request->surname;
            $updateUser->username=$request->username;
            $updateUser->cargo=$request->cargo;
            $updateUser->area_id=$request->area;
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

        $users = User::where('username', 'like', '%' . $searchText . '%')->get();

        return view('admin.adminUsers', compact('users', 'areas'));
    }

    public function areaFilter(Request $request){
        $areaId = $request->input('areaId');
        $areas= Area::all();

        $users = User::where('area_id', $areaId)->get();

        return view('admin.adminUsers', compact('users', 'areas'));
    }


    public function getAreas(){
        $areas= Area::orderBy('id', 'desc')->get();
        return view('admin.adminAreas', compact('areas'));
    }

    public function updateArea(Request $request, $id){
        DB::beginTransaction();
        try{
            $request->validate([
                'area'=>'required',
                'mañana_start_time'=>'required',
                'mañana_end_time'=>'required',
                'tarde_start_time'=>'required',
                'tarde_end_time'=>'required',
                'noche_start_time'=>'required',
                'noche_end_time'=>'required'
            ]);
            $updateArea=Area::findOrFail($id);
            $updateArea->area_name=$request->area;
            $updateArea->mañana_start_time=$request->mañana_start_time;
            $updateArea->mañana_end_time=$request->mañana_end_time;
            $updateArea->tarde_start_time=$request->tarde_start_time;
            $updateArea->tarde_end_time=$request->tarde_end_time;
            $updateArea->noche_start_time=$request->noche_start_time;
            $updateArea->noche_end_time=$request->noche_end_time;
            $updateArea->save();

            DB::commit();
            return back()->with('mensaje', 'Area editado exitosamente');
        }catch(\Exception $e){
            DB::rollBack();
            return back()->withErrors('No se pudo editar el area. Error: ' . $e->getMessage());
        }
    }

    public function createArea(Request $request){
        DB::beginTransaction();
        try{
            $request->validate([
                'area'=>'required',
                'mañana_start_time'=>'required',
                'mañana_end_time'=>'required',
                'tarde_start_time'=>'required',
                'tarde_end_time'=>'required',
                'noche_start_time'=>'required',
                'noche_end_time'=>'required'
            ]);
            $createArea=new Area();
            $createArea->area_name=$request->area;
            $createArea->mañana_start_time=$request->mañana_start_time;
            $createArea->mañana_end_time=$request->mañana_end_time;
            $createArea->tarde_start_time=$request->tarde_start_time;
            $createArea->tarde_end_time=$request->tarde_end_time;
            $createArea->noche_start_time=$request->noche_start_time;
            $createArea->noche_end_time=$request->noche_end_time;
            $createArea->save();

            DB::commit();
            return back()->with('mensaje', 'Area creada exitosamente');
        }catch(\Exception $e){
            DB::rollBack();
            return back()->withErrors('No se pudo crear el area. Error: ' . $e->getMessage());
        } 
    }
}
