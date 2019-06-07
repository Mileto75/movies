<?php

namespace App\Http\Controllers;

use App\acteurs;
use App\films;
use App\Regisseur;
use http\Exception\BadQueryStringException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


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
     * toont de newMovie view
     */
    public function newMovie()
    {
        return view('newMovie');
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
        //haal de film op met regisseur
        //dmv koppeltabel tbl_films_regisseur
        $films_met_regisseur = DB::table('tbl_films')
            ->select('regisseur_id','titel','jaar','name','fname')
            ->join('tbl_films_regisseur','tbl_films.film_id',
                '=',
                'tbl_films_regisseur.film_id')
            ->join('tbl_regisseurs','reg_id','=','regisseur_id')
            ->where('tbl_films.film_id','=',$filmId)
            ->get();


        //haal alle regisseurs op
        $regisseurs = DB::table('tbl_regisseurs')->get();

        $vars = ['filmId' => $filmId,'filmData' => $films_met_regisseur,'regisseurs' => $regisseurs];
        return view('editMovie',$vars);

    }

    /**
     * voegt een nieuwe film toe
     * @param Request $request
     */
    public function insertMovie(Request $request)
    {
        $ar_rules = array('titel' => 'required','jaar'=>'required|integer|between:1888,2020');
        $request->validate($ar_rules);

        $titel  = $request->input('titel');
        $jaar   = $request->input('jaar');
        //bind de parameters voor de insert
        $ar_param = array("titel" => $titel, 'jaar' => $jaar);
        //roep de DB facade op met de parameters
        $result = DB::insert('insert into tbl_films (titel,jaar) VALUES (:titel,:jaar)',$ar_param);


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
        $filmId = $request->input('film_id');
        $titel  = $request->input('titel');
        $jaar   = $request->input('jaar');
        $regisseur_id = $request->input('movieRegisseur_id');
        //bind de parameters voor de update filmvariabelen jtitel en jaar
        $ar_param = array("titel" => $titel, 'jaar' => $jaar);
        //we gebruiken een try catch block => properder oplossing
        try
        {
            //update de tabel tbl_films
            DB::table('tbl_films')
                ->where('film_id', '=', $filmId)
                ->update($ar_param);

            //update de tbl_films_regisseur
            DB::table('tbl_films_regisseur')
                    ->where('film_id', '=', $filmId)
                    ->update(array('reg_id' => $regisseur_id));

        }
        catch(QueryException $exception)
        {
            //indien een fout stuur terug naar filmlijst met passende flas session var
            $message = "Er heeft zich een probleem voorgedaan";
            $request->session()->flash('message',$message);
            //redirect naar de pagina met films
            return redirect()->route('toonFilms');
        }
        //updates gelukt
        $message = "Update gelukt!";
        //flash session aanmaken
        $request->session()->flash('message',$message);
        //redirect naar de pagina met films
        return redirect()->route('toonFilms');
    }

    /**
     * Deletes a movie through DB facade
     * @param Request $request
     */
    public function deleteMovie($filmId)
    {

        //bind the parameters
        $ar_params = array('film_id' => $filmId);
        //call the DB facade delete method
        $result = DB::delete('DELETE FROM tbl_films WHERE film_id=:film_id',$ar_params);
        if($result)
        {
            $message = "Movie deleted";
        }
        else
        {
            $message = "Er heeft zich een probleem voorgedaan, probeer opnieuw!";
        }

        return redirect( route('toonFilms'));
 }

    /**
     * test de database CRUD acties met QueryBuilder
     */
    public function queryBuilderTester()
    {
        /**
         * SELECT QUERIES
         */
        //Select alle films
        $films = DB::table('tbl_films')->get();
        //overlopen met foreach:
        foreach ($films as $film)
        {
            echo $film->titel."<br>";
        }
        //Select enkel de titel van alle films
        $films = DB::table('tbl_films')->select('titel')->get();
        //overlopen met foreach:
        foreach ($films as $film)
        {
            echo $film->titel."<br>";
        }
        //Where clausule
        $films = DB::table('tbl_films')->where('titel','LIKE','The%')->get();
        //overlopen met foreach:
        foreach ($films as $film)
        {
            echo $film->titel."<br>";
        }
        //Where clausule met meerdere termen
        //gebruik een multidimensionale array
        $ar_whereParams = array(array("titel","LIKE","The%"),
                                array("jaar","LIKE","19%")
                               );
        //querybuilder oproepen
        $films = DB::table('tbl_films')->where($ar_whereParams)->get();
        //overlopen met foreach:
        foreach ($films as $film)
        {
            echo $film->titel."<br>";
        }

        /**
         * UPDATE QUERY
         */
        //update methode neem velden mee in array
        //let op het gebruik van de WHERE clausule
        $result = DB::table('tbl_films')
                        ->where('titel','=','The Godfather')
                        ->update(array('titel'=>'TheGodfather1'));
         if($result)
         {
             echo "update uitgevoerd!";
         }

         /**
          * INSERT QUERY
          */
         //insert methode neemt velden mee in array
         $result = DB::table('tbl_films'
                            )->insert(array('titel' => 'Jaws','jaar'=>'2019'));
         if($result)
         {
             echo "film toegevoegd<br>";
         }

         /**
          * DELETE query
          */
         //delete methode, let ook hier op gebruik van WHERE
         $result = DB::table('tbl_films')
                            ->where('titel','=','Jaws')
                            ->delete();
         if($result)
         {
             echo "Film verwijderd!<br>";
         }

         /**
          * Query builder Joins
          */
         //we willen de film met regisseur en acteurs
        $films_met_regisseur = DB::table('tbl_films')
                                        ->select('titel','jaar','name','fname')
                                        ->join('tbl_films_regisseur','tbl_films.film_id',
                                            '=',
                                            'tbl_films_regisseur.film_id')
                                        ->join('tbl_regisseurs','reg_id','=','regisseur_id')
                                        ->get();
        //overlopen met foreach:
        foreach ($films_met_regisseur as $film)
        {
            echo "$film->titel,$film->fname $film->name<br>";
        }

    }

    /**
     * test de databbase CRUD acties met Eloquent Model
     */
    public function modelTester()
    {
        //haal record met id 1 op
        $regisseur = Regisseur::find('1');
        //var_dump($regisseur->name);
        //haal record op basis van naam
        /*$regisseur = Regisseur::where('name','Coppola')->get();
        foreach ($regisseur as $reg)
        {
            echo $reg->name."<br>";
        }*/
        /*$acteurs = acteurs::all();
        foreach ($acteurs as $acteur)
        {
            echo $acteur->name."<br>";
        }*/
        $acteur_met_films = acteurs::find(1);

        echo "$acteur_met_films->fname $acteur_met_films->name speelt in:<br/>";
        foreach ($acteur_met_films->films as $films)
        {
            echo $films->titel."<br>";
        }
    }
}
