@extends('layouts.app')

@section('content')

    <div class="container">

    @if($errors->any())
        @foreach($errors->all() as $error)
                <div class="alert alert-danger" role="alert">
                    <h4>{{$error}}</h4>
                </div>
        @endforeach
    @endif
    <!-- Begin form -->
    <form class="text-center border border-light p-5" action="{{route('updateMovie')}}" method="post">
    {{csrf_field()}}

        <!-- Titel -->
        <input type="text" id="movieTitle" name="titel" value="{{$filmData[0]->titel}}" class="form-control mb-4" placeholder="Titel">

        <!-- Jaar -->
        <input type="text" id="movieJaar" name="jaar" value="{{$filmData[0]->jaar}}" class="form-control mb-4" placeholder="2019">
        <!-- film_id -->
        <input type="hidden" name="film_id" value="{{$filmId}}">
        <!-- Sign in button -->
        <button class="btn btn-info btn-block" type="submit">Save</button>


    </form>
    <!-- End form -->
    </div>
@endsection
