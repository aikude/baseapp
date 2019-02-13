@extends('layouts.app')

@section('content')
    <h1>{{$post->title}}</h1>
    @if ($post->cover_image)
        <img class="w-100 mb-3" src="{{url('/')}}/storage/cover_images/{{$post->cover_image}}" alt="{{$post->title}}">
    @endif
    <div class="postcontent">
        {!!$post->body!!}
    </div>
    <hr>
    <small>Written on: {{$post->created_at}} by {{$post->user->name}}</small>
    @if (!Auth::guest() && Auth::user()->id == $post->user_id)
        <hr>
        <a href="{{url('/')}}/posts/{{$post->id}}/edit" class="btn btn-default">Edit</a>

        {!! Form::open(['action' => ['PostsController@destroy', $post->id], 'method' => 'POST', 'class' => 'pull-right']) !!}
            {{Form::hidden('_method', 'DELETE')}}
            {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
        {!! Form::close() !!}
    @endif
@endsection