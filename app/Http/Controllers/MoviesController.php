<?php

namespace App\Http\Controllers;

use App\films;
use Illuminate\Http\Request;

class MoviesController extends Controller
{
    //

    public function index()
    {

    }

    public function toonFilms()
    {
        $film = films::find(1);
        $film->titel = 'The Godfather 2';
        //var_dump($film);

        $film->save();
        return view('movies',['films' =>$film]);
    }
}
