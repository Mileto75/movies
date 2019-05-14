@extends('layouts.app')

@section('content')
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
                <td><a href="{{route("editMovie",['filmId' => $film->film_id])}}">Edit</a> </td>
            </tr>
        @endforeach
        </tbody>

    </table>
@endsection
