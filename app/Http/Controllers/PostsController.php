<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Post;

function slugify($str, $delimiter = '-'){

    $slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
    return $slug;
}

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    function __construct(){
        $this->middleware('auth', ['except' => ['index', 'show']]);

        //add any data that should be accessible on all pages
        $this->pagedata = [];
    }
    
    public function index()
    {
        //$posts = Post::all();
        //$posts = Post::orderBy('created_at', 'desc')->take(10)->get();
        
        $posts = Post::orderBy('created_at', 'desc')->paginate(10);
        $this->pagedata['posts'] = $posts;
        
        return view('posts.index')->with($this->pagedata);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create')->with($this->pagedata);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'cover_image' => 'image|nullable|max:1999'
        ]);

        // Handle file uploads
        $filenameToStore = '';
        if($request->hasFile('cover_image')){
            $fileExtension = $request->file('cover_image')->getClientOriginalExtension();
            $filenameToStore = slugify($request->title) . "_" . time() . "." . $fileExtension;

            // Upload image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $filenameToStore);
        }

        $post = new Post;
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->user_id = auth()->user()->id;
        $post->cover_image = $filenameToStore;
        $post->save();

        $this->pagedata['success'] = 'Post created';

        return redirect('/posts')->with($this->pagedata);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        $this->pagedata['post'] = $post;
        return view('posts.show')->with($this->pagedata);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);
        if(auth()->user()->id !== $post->user_id) return redirect('/posts')->with('error', 'Unauthorized');

        $this->pagedata['post'] = $post;
        return view('posts.edit')->with($this->pagedata);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required'
        ]);

        // Handle file uploads
        $filenameToStore = '';
        if($request->hasFile('cover_image')){
            $fileExtension = $request->file('cover_image')->getClientOriginalExtension();
            $filenameToStore = slugify($request->title) . "_" . time() . "." . $fileExtension;

            // Upload image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $filenameToStore);
        }

        $post = Post::find($id);
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        if($filenameToStore) $post->cover_image = $filenameToStore;
        $post->save();

        $this->pagedata['success'] = 'Post updated';

        return redirect('/posts')->with($this->pagedata);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        if(auth()->user()->id !== $post->user_id) return redirect('/posts')->with('error', 'Unauthorized');

        if($post->cover_image) Storage::delete('public/cover_images/' . $post->cover_image);
        $post->delete();
        
        $this->pagedata['success'] = 'Post deleted';

        return redirect('/posts')->with($this->pagedata);

    }
}
