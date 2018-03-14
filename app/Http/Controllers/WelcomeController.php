<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class WelcomeController extends Controller
{
    public function index(){
        $post=DB::table('posts')->get();

        return view('posts.front')->with('posts',$post);

    }

}
