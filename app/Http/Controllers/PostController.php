<?php

namespace App\Http\Controllers;

use App\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use App\Category;
use App\Post;
use App\like;
use App\dislike;
use Auth;
use DB;
use User;
use App\Comment;



class PostController extends Controller
{
    public function post()
    {
        $categories = Category::all();

       // exit();
        return view('posts.post', ['categories' => $categories ]);
  }

    public function addPost(Request $request)
    {
        // return $request->input('post_title');
        $this->validate($request, [
            'post_title' => 'required',
            'post_body' => 'required',
            'category_id' => 'required',
            'post_image' => 'required'
        ]);
        //return 'abc';
        $posts = new Post;
        $posts->post_title = $request->input('post_title');
        $posts->user_id = Auth::user()->id;
        $posts->post_body = $request->input('post_body');
        $posts->category_id = $request->input('category_id');

        if (Input::hasFile('post_image')) {
            $file = Input::file('post_image');
            $file->move(public_path() . '/posts/', $file->getClientOriginalname());
            $url = URL::to("/") . '/public/posts/' .
                $file->getClientOriginalname();
        }
        $posts->post_image = $url;
        $posts->save();
        return redirect('/home')->with('response', 'post Added successfully');
    }

    public function view($post_id){
      $posts = Post::where('id', '=',$post_id)->get();
      // For liking
      $likePost = Post::find($post_id);
      $likepress = like::where(['post_id' => $likePost->id])->count();
      $dislikepress = dislike::where(['post_id' => $likePost->id])->count();
    //  return   $likepress;
    //  exit();
        $categories = Category::all();
        $comments = DB::table('users')
            ->join('comments', 'users.id', '=', 'comments.user_id')
            ->join('posts', 'comments.post_id', '=', 'posts.id')
            ->select('users.name', 'comments.*')
            ->where(['posts.id' => $post_id])
            ->get();
        //return $comments;
       // exit();
      return view('posts.view',['posts'=> $posts, 'categories' =>$categories,'likepress' => $likepress,
    'dislikepress' =>$dislikepress, 'comments' => $comments ]);

    }


    public function edit($post_id){
      $categories = Category::all();
      //return $post_id;
      $posts = Post::find($post_id);
      //return $posts;
      //exit();
        $category = Category::find($posts->category_id);
      return view('posts.edit',['categories' => $categories,
      'posts' => $posts, 'category' => $category]);
    }

    public function editPost(Request $request,$post_id){
    //  return $post_id;
    $this->validate($request, [
        'post_title' => 'required',
        'post_body' => 'required',
        'category_id' => 'required',
        'post_image' => 'required'
    ]);
    //return 'abc';
    $posts = new Post;
    $posts->post_title = $request->input('post_title');
    $posts->user_id = Auth::user()->id;
    $posts->post_body = $request->input('post_body');
    $posts->category_id = $request->input('category_id');

    if (Input::hasFile('post_image')) {
        $file = Input::file('post_image');
        $file->move(public_path() . '/posts/', $file->getClientOriginalname());
        $url = URL::to("/") . '/public/posts/' .
            $file->getClientOriginalname();
    }
    $posts->post_image = $url;
    $data = array(
      'post_title' => $posts->post_title,
      'user_id' => $posts->user_id,
      'post_body' => $posts->post_body,
      'category_id' => $posts->category_id,
      'post_image' => $posts->post_image,
    );
    Post::where('id',$post_id )
    ->update($data);
    $posts->update();
    return redirect('/home')->with('response', 'post Updated successfully');
    }

    public function deletePost($post_id){
    //  return $post_id;
    post::where('id', $post_id)
    ->delete();
    return redirect('/home')->with('response', 'post deleted successfully');
    }

    public function category($cat_id){

      $categories = Category::all();
      $posts = DB::table('posts')
            ->join('categories', 'posts.category_id', '=', 'categories.id')
            ->select('posts.*', 'categories.*')
            ->where(['categories.id' => $cat_id])
            ->get();
    //  return $cat_id;

  //  return $posts;
  //  exit();
    return view('categories.categoriesposts',['categories'=> $categories, 'posts'=> $posts]);
    }

//like function description
    public function like($id){
    //  return $id;
    $loggedin_user = Auth::user()->id;
    $like_user = like::where(['user_id' => $loggedin_user,'post_id'=> $id])->first();

    if(empty($like_user->user_id)){
      $user_id = Auth::user()->id;
      $email = Auth::user()->email;
      $post_id = $id;
      $like = new like;
      $like->user_id = $user_id;
        $like->email = $email;
        $like->post_id = $post_id;
        $like->save();
        return redirect("/view/{$id}");

    }
    else{
      return redirect("/view/{$id}");
    }
    }

    public function dislike($id)
    {
        //  return $id;
        $loggedin_user = Auth::user()->id;
        $like_user = dislike::where(['user_id' => $loggedin_user, 'post_id' => $id])->first();

        if (empty($like_user->user_id)) {
            $user_id = Auth::user()->id;
            $email = Auth::user()->email;
            $post_id = $id;
            $like = new dislike;
            $like->user_id = $user_id;
            $like->email = $email;
            $like->post_id = $post_id;
            $like->save();
            return redirect("/view/{$id}");

        } else {
            return redirect("/view/{$id}");
        }
    }
    public function comment(Request $request,$post_id){
              //   return $post_id;
        $this->validate($request, [
            'comment' => 'required',

        ]);
        $comment = new Comment;
        $comment->user_id = Auth::user()->id;
        $comment->post_id = $post_id;
        $comment->comment = $request->input('comment');
        $comment->save();
        return redirect("/view/{$post_id}")->with('response','Comment added');
        }

        public function search(Request $request){
         //return $request->input('search');

            $user_id = Auth::user()->id;
            $profile = Profile::find($user_id);
            $keyword = $request->input('search');
            $posts = Post::where('post_title','like','%'.$keyword.'%')->get();
            //return $post;
           // exit();

            return view('posts.searchposts',['profile'=> $profile, 'posts'=>$posts]);

        }

}
