@extends('layouts.app')

@section('content')

    <div class="container">

        @if(session('message'))
        <div class="alert alert-info" role="alert">
            <h4>{{session('message')}}</h4>
        </div>
        @endif
        <table class="table table-responsive">
        <thead class="thead-dark">
        <tr>
            <th>Titel</th>
            <th>Jaar</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>

        @foreach($films as $film)
            <tr>
                <td>{{$film->titel}}</td>
                <td>{{$film->jaar}}</td>
                <td><a href="{{route("editMovie",['filmId' => $film->film_id])}}">Edit</a>
                    |
                    <a href="{{route("deleteMovie",['filmId' => $film->film_id])}}">Delete</a>
                </td>
            </tr>
        @endforeach
        </tbody>

    </table>
    </div>
@endsection
