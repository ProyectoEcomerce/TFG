<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function getUsers(){
        $users= User::orderBy('id', 'desc')->get();
        $areas= Area::all();
        return view('admin' , compact('users', 'areas'));
    }

    public function editUser($id){
        $user= User::findOrFail($id);
        return view('editUser', compact('user'));
    }
}
