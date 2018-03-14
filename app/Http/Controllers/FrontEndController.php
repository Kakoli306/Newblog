<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class FrontEndController extends Controller
{
    //

    public function index(){

        $post=DB::table('posts')->first();
        return view('posts.front')->with('post',$post);
    }
}
