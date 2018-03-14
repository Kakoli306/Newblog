@extends('layouts.app')

<style type="text/css">
    .avatar-empty{
        border-radius: 100%;
        max-width: 100px;
    }
</style>


@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      @if(count($errors) > 0)
      @foreach($errors->all() as $error)
       <div class="alert alert-danger">{{$error}}</div>
       @endforeach
       @endif

       @if(session('response'))
       <div class="alert alert-success">{{ session('response')}}</div>
       @endif
      <div class="panel panel default text search">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-4">Dashboard</div>
                <div class="col-md-8">
                    <form method="POST" action="{{url ("/search") }}">
                        {{csrf_field()}}
                        <div class="input-group">
                            <input type="text" name="search" class="form-control"
                            placeholder="Search for...">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-default">
                                    GO
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>


        <div class="panel-body">

              @if(!empty($profile))
                  <img src="{{ $profile->profile_image }}"
                       class="avatar-empty" alt="">
                  @else
                  <img src="{{ url('images/avatar-empty.png') }}"
                       class="avatar-empty" alt="">
                  @endif

                  @if(!empty($profile))
                      <p class="lead"> {{ $profile->name }} </p>
                      @else
                     <p></p>
                  @endif

                  @if(!empty($profile))
                      <p class="lead"> {{ $profile->designation }}</p>
                      @else
                     <p></p>
                  @endif
          </div>

            @foreach($categories->all() as $cat)\
                <h4>{{ $cat->category }}</h4>
                @endforeach

          <div class="col-md-8">
              @if(count($posts) > 0)
                  @foreach($posts->all() as $post)
                      <h4>{{$post->post_title}}</h4>
                      <img src="{{$post->post_image}}" alt="">

                      <p>{{substr($post->post_body,0,150)}}</p>

                      <ul class="nav nav-pills">
                        <li role="presentation">
                          <a href="{{url("/view/{$post->id}") }}">
                        <i class="fa fa-download">VIEW</i>
                            </a>
                          </li>

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

                  @else
                         <p>No posts available</p>
                      </ul>

                  @endif

                
          </div>
        </div>
        <div>
      <div>
  </div>
</div>
      </div>
    </div>
  </div>
</div>
@endsection
