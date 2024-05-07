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
        $user = auth()->user();

        return view('auth.dashboard', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'name' => 'required|string|max:155',
                'surname' => 'required|string|max:255',
                'username' => 'required|string|unique:users,username,' . $id,
                'email' => 'required|string|unique:users,email,' . $id,
            ]);
            $updateUser = User::findOrFail($id);
            $updateUser->name = $request->name;
            $updateUser->surname = $request->surname;
            $updateUser->username = $request->username;
            $updateUser->email = $request->email;
            $updateUser->save();

            DB::commit();
            return back()->with('mensaje', 'Usuario editado exitosamente');
        } catch (\Exception $e) {
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
    public function uploadProfileImage(Request $request)
    {
        // Validar la imagen
        $request->validate([
            'profile_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // validaciones del tipo de imagenes
        ]);

        // Obtiene el usuario autenticado
        $user = Auth::user();

        // Procesa y almacena la imagen en el servidor
        if ($request->hasFile('profile_image')) {
            $profileImage = $request->file('profile_image');
            $imageName = time() . '.' . $profileImage->extension();
            $profileImage->move(public_path('uploads'), $imageName);

            // Actualiza la ruta de la imagen de perfil en la base de datos
            $user->profile_image = 'uploads/' . $imageName;
            $user->save();

            // Devolver la vista del dashboard con un mensaje de éxito
            return $this->show()->with('success', 'Imagen de perfil actualizada exitosamente.');
        }

        // Manejar errores si la imagen no se carga correctamente
        return $this->show()->with('error', 'Error al cargar la imagen de perfil.');
    }

}
