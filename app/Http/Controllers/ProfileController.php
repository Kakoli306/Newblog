<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use App\Profile;
use Auth;


use Illuminate\Http\Request;

class ProfileController extends Controller
{
  public function profile()
  {
    return view('profiles.profile');
  }

  public function addProfile(Request $request){
    $this->validate($request,[
      'name'=> 'required',
      'designation' => 'required',
      'profile_image' => 'required'
    ]);
    $profiles = new Profile;
    $profiles->name = $request->input('name');
    $profiles->user_id = Auth::user()->id;
    $profiles->designation = $request->input('designation');

     if(Input::hasFile('profile_image')){
       $file = Input::file('profile_image');
       $file->move(public_path(). '/uploads',$file->getClientOriginalname());
       $url = URL::to("/") . '/public/uploads/'.
       $file->getClientOriginalname();
      // return $url;
//exit();
     }

    //return Auth::user();
    //exit();

  $profiles->profile_image =  $url;
  $profiles->save();
  return redirect('/home')->with('response','profile Added successfully');
  //return redirct('profile')->with('response', 'Profile Added successfully');
}
}
