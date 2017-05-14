@extends('layouts.master')

@section('title')
    Online Books Review
@endsection

@section('content')
@include('includes.header')
<div class="container">
  <div class="row">
      <div class="col-md-8 full-box">
         @foreach($posts as $post)

             <div class="border-block">
                 <h2>
                      <a href="{{ route('post.single' , [ 'slug' => $post->slug ])}}">{{ $post->title }}</a>
                  </h2>
                  <p><span class="glyphicon glyphicon-time"></span> Posted on {{ $post->created_at->format('m-d-Y') }} by <span style="color: blue;text-transform: capitalize;">{{ $post->user->name }}</span></p>
                 <hr>
                 <!-- <hr>
                  <img class="img-responsive" src="http://placehold.it/900x300" alt=""> -->
                  <div>{!! str_limit($post->body,400) !!} </div>
                  <div class="clearfix"></div>

                  <a class="btn btn-primary" style="float: right;" href="{{ route('post.single' , [ 'category_name' => $post->category->name , 'slug' => $post->slug ])}}"> Read More <span class="glyphicon glyphicon-chevron-right"></span></a>
                  <div class="clearfix"></div>
              </div>
         @endforeach
        <nav>
          <ul class="pager">
              @if($posts->currentPage() !== 1)
                <li class="previous"><a href="{{ $posts->previousPageUrl() }}"><span aria-hidden="true">&larr;</span> Newer</a></li>
              @endif
              @if($posts->currentPage() !== $posts->lastPage() && $posts->hasPages())
                <li class="next"><a href="{{ $posts->nextPageUrl() }}">Older <span aria-hidden="true">&rarr;</span></a></li>
              @endif
          </ul>
        </nav>
      </div>

      @include('includes.left_sidebar')
  </div>

    @include('includes.footer')
</div>

@endsection

