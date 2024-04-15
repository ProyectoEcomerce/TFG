<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show($id)
    {
        $user = User::findOrFail($id);
        $turnos = $user->tourns;

        return view('profile', compact('user', 'turnos'));
    }

    /**
     * Actualizar la imagen de perfil del usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
