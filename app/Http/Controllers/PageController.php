<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Category;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
     public function __construct()
    {
      /*  $this->middleware('auth',['only'=>['create','store']]);*/
      $this->middleware('auth',['except'=>['index','show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts=Post::orderBy('title','desc')->get();
        return view('post.index',compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()//form pya tk method
    {
        $categories=Category::all();
        //dd($categories);
       return view('post.create',compact('categories'));//array ko pyn htae payy or data payyyyy

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);

        //validation
        $request->validate([
            'title'=>'required|min:5',
            'content'=>'required|min:10',
            'photo'=>'required|mimes:jpeg,png',
            'category'=>'required'
        ]);
        //file upload
        if($request->hasfile('photo')){
            $photo=$request->file('photo');
            //$name=$photo->getClientOriginalName();//request ka pr lr tk photo name ko catch
            $name=time().'.'.$photo->getClientOriginalExtension();//no twy nk pw
            $photo->move(public_path().'/storage/image/',$name);
            $photo='/storage/image/'.$name;

        }else{
            $photo='';
        }



        //data insert
        $post=new Post();
        $post->title=request('title');
        $post->body=request('content');
        $post->image=$photo;
        $post->category_id=request('category');
        
        $post->user_id=Auth::id();
        //$post->status=true;
        $post->save();
        //Redirect
        return redirect()->route('firstpage');



    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post=Post::find($id);//find ka condition sis payy,looping ma lo, takyoung pl htwet
        //$post=Post::where('status',1)->first();
       return view('post.detail',compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post=Post::find($id);//old data twy pw ag
        $categories=Category::all();
        return view('post.edit',compact('categories','post'));
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
       //dd($request);
         //validation
        $request->validate([
            'title'=>'required|min:5',
            'content'=>'required|min:10',
            'photo'=>'sometimes|required|mimes:jpeg,png',
            'category'=>'required'
        ]);
        //file upload
        if($request->hasfile('photo')){
            $photo=$request->file('photo');
            //$name=$photo->getClientOriginalName();//request ka pr lr tk photo name ko catch
            $name=time().'.'.$photo->getClientOriginalExtension();//no twy nk pw
            $photo->move(public_path().'/storage/image/',$name);
            $photo='/storage/image/'.$name;

        }else{
            $photo=request('oldphoto');
        }



        //data update
        //$post=new Post($id);//update lote mhr moz id htae pyy yaa
        $post=Post::find($id);
        $post->title=request('title');
        $post->body=request('content');
        $post->image=$photo;
        $post->category_id=request('category');
       
        $post->user_id=Auth::id();
        //$post->status=true;
        $post->save();
        //Redirect
        return redirect()->route('firstpage');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post=Post::find($id);
        $post->delete();
        return redirect()->route('firstpage');

    }
}
