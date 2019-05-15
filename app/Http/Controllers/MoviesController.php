<?php

namespace App\Http\Controllers;

use App\films;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class MoviesController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function toonFilms()
    {
        //using model
        //$film = films::find(1);

        //using DB facade
        $films = DB::select('select * from tbl_films');
        return view('movies',['films' =>$films]);
    }

    /**
     * @param $filmId
     * Haalt info op van film
     * toont de editMovie view
     */
    public function editMovie($filmId)
    {
        //leg de parameters vast om mee te geven
        $ar_params = array('film_id' => $filmId);
        //bouw de query op met placeholder :film_id
        $filmData = DB::select("SELECT * FROM tbl_films WHERE film_id=:film_id",$ar_params);
        //de DB::select methode retourneert een array vanb objecten
        //het resultaat kunnen we dan dmv de $vars meegeven aan de view
        $vars = ['filmId' => $filmId,'filmData' => $filmData];
        return view('editMovie',$vars);

    }

    /**
     * update de movie in de db
     * redirect naar movies view met
     * bericht
     */
    public function updateMovie(Request $request)
    {
        $ar_rules = array('titel' => 'required','jaar'=>'required|integer|between:1888,2020');
        $request->validate($ar_rules);
    }
}
