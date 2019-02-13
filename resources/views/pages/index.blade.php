@extends('layouts.app')

@section('content')
    <div class="jumbotron">
        <h1 class="display-4">{{$title}}</h1>
        <p class="lead">{{$intro}}</p>
        <a class="btn btn-primary btn-lg" href="{{url('/')}}/register" role="button">Register</a>
    </div>
@endsection