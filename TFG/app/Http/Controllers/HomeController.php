<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $all_events= Availability::all();
        $events=[];
        foreach($all_events as $event){
            $events[]=[
                'title'=> $event->aviableavaibility,
                'start'=> '2024-04-03 08:00',
                'end'=>'2024-04-04 11:00'
            ];
        }
        return view('home', compact('events'));
    }
}
