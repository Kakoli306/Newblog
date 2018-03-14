<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Profile;
use App\User;

use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        $profile = DB::table('users')
                  ->join('profiles', 'users.id', '=',
                      'profiles.user_id')
            ->select('users.*', 'profiles.*')
            ->where(['profiles.user_id' => $user_id])
            ->first();
        $posts = Post::all();
        $categories = Category::all();
       // return $posts;
       //return $profile->profile_image;
       // return $profile;
        //exit();
        return view('home',['profile' => $profile, 'posts' => $posts, 'categories' =>  $categories] );
    }
}
