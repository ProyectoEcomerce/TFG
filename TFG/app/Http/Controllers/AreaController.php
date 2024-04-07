<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function getAreas(){
        $areas= Area::all();
        return view('home' , compact('areas'));
    }
}
