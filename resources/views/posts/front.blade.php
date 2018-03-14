@extends('layouts.app')

@section('content')


<div class="col-md-8">

    @foreach($posts as $post)
        <h4>{{$post->post_title}}</h4>
        <img src="{{$post->post_image}}" alt="">

        <p>{{substr($post->post_body,0,150)}}</p>

        <ul class="nav nav-pills">
            <li role="presentation">
                <a href="{{url("/view/{$post->id}") }}">
                    <i class="fa fa-download">VIEW</i>
                </a>
            </li>

            <li role="presentation">
                <a href="{{url("/comment/{post->id}") }}">
                    <i class="fas fa-download">Comment</i>
                </a>
            </li>

            <form method="POST" action='{{url("/comment/{$post->id}")}}'>
                {{csrf_field()}}
                <div class="form-group">
                        <textarea id="comment" rows="6" class="form-control" name="comment"
                                  required autofocus></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class=" btn btn-success">
                        POST COMMENT
                    </button>
                </div>
            </form>

            @if(Auth::id() == 1)
                <li role="presentation">
                    <a href="{{url("/edit/{$post->id}") }}">
                        <i class="fas fa-download">EDIT</i>
                    </a>
                </li>



                <li role="presentation">
                    <a href="{{url("/delete/{$post->id}") }}">
                        <i class="fas fa-download">DELETE</i>
                    </a>
                </li>
            @endif




            <hr/>
            @endforeach


        </ul>


</div>

@endsection