<?php

namespace App\Http\Controllers;

use Cornford\Googlmapper\Facades\MapperFacade as Mapper;
use Response;

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

//        Mapper::map(35.7599644 , 51.40986264,['zoom' => 15, 'markers' => ['title' => 'My Location', 'animation' => 'DROP']]);

//        Mapper::map(35.7600092958542,51.409870348870754,['zoom' => 15, 'markers' => ['title' => 'My Location', 'animation' => 'DROP']]);

//        Mapper::map(35.7600092958542,51.409870348870754,['zoom' => 15, 'markers' => ['title' => 'My Location', 'animation' => 'DROP']]);


        return view('home');
    }
}
