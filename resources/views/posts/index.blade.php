@extends('layouts.app')

@section('content')
    <h1>Posts</h1>
    @if(count($posts))
        @foreach ($posts as $post)
            <div class="card card-body bg-light mb-3">
                <div class="row">
                    <div class="col-md-4 col-sm-4">
                    @if ($post->cover_image)
                        <img class="w-100" src="{{url('/')}}/storage/cover_images/{{$post->cover_image}}" alt="{{$post->title}}">
                    @endif
                    </div>
                    <div class="col-md-8 col-sm-8">
                        <h3><a href="{{url('/')}}/posts/{{$post->id}}">{{$post->title}}</a></h3>
                        <small>Written on: {{$post->created_at}} by {{$post->user->name}}</small>
                    </div>
                </div>
            </div>
        @endforeach
        {{$posts->links()}}
    @else
        No posts found
    @endif
@endsection