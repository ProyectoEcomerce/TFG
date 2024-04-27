<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function show()
    {
        $user= auth()->user();

        return view('auth.dashboard', compact('user'));
    }

    public function updateUser(Request $request, $id){
        DB::beginTransaction();
        try{
            $request->validate([
                'name'=>'required|string|max:155',
                'surname'=>'required|string|max:255',
                'username' => 'required|string|unique:users,username,'.$id,
                'email'=>'required|string|unique:users,email,'.$id,
            ]);
            $updateUser=User::findOrFail($id);
            $updateUser->name=$request->name;
            $updateUser->surname=$request->surname;
            $updateUser->username=$request->username;
            $updateUser->email=$request->email;
            $updateUser->save();

            DB::commit();
            return back()->with('mensaje', 'Usuario editado exitosamente');
        }catch(\Exception $e){
            DB::rollBack();
            return back()->withErrors('No se pudo editar el usuario. Error: ' . $e->getMessage());
        }
    }


    /**
     * Actualizar la imagen de perfil del usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadProfileImage(Request $request): RedirectResponse
    {
        // Validar la imagen
        $request->validate([
            'profile_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
        ]);

        // Obtener el usuario autenticado
        $user = Auth::user();

        // Procesar y almacenar la imagen en el servidor
        if ($request->hasFile('profile_image')) {
            $profileImage = $request->file('profile_image');
            $imageName = time() . '.' . $profileImage->extension();
            $profileImage->move(public_path('uploads'), $imageName);

            // Actualizar la ruta de la imagen de perfil en la base de datos
            $user->profile_image = 'uploads/' . $imageName;
            $user->save();

            return redirect()->route('dashboard')->with('success', 'Imagen de perfil actualizada exitosamente.');
        }

        return redirect()->route('dashboard')->with('error', 'Error al cargar la imagen de perfil.');
    }

}
