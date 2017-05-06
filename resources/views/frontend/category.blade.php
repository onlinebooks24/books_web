@extends('layouts.master')

@section('title')
  {{ $categoryName }} | OnlineBooksReview
@endsection

@section('content')
@include('includes.header')
<div class="container">
  <div class="row">
      <div class="col-md-8"> 
         @if(!empty(Request::Segment(1)))
            <div class="alert alert-info">
              <strong>Category : </strong> {{ $categoryName }} . Show All Posts.
            </div>
         @endif
         @foreach($posts as $post)
             <h2>
                  <a href="{{ route('post.single' , [ 'category_name' => $post->category->name , 'slug' => $post->slug ])}}">{{ $post->title }}</a>
              </h2>
              <p><span class="glyphicon glyphicon-time"></span> Posted on {{ $post->created_at->format('m-d-Y') }}  by <span style="color: blue;text-transform: capitalize;">{{$post->user->name }}</span></p>
              <!-- <hr>
              <img class="img-responsive" src="http://placehold.it/900x300" alt=""> -->
              <hr>
              <p>{!! str_limit($post->body,400) !!}</p> 
              <a class="btn btn-primary" href="{{ route('post.single' , [ 'category_name' => $post->category->name , 'slug' => $post->slug ])}}" style="float: right;">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>
              <div class="clearfix"></div>
              <hr>
         @endforeach

        <nav>
          <ul class="pager">
              @if($posts->currentPage() !== 1)
                <li class="previous"><a href="{{ $posts->previousPageUrl() }}"><span aria-hidden="true">&larr;</span> Older</a></li>
              @endif
              @if($posts->currentPage() !== $posts->lastPage() && $posts->hasPages())
                <li class="next"><a href="{{ $posts->nextPageUrl() }}">Newer <span aria-hidden="true">&rarr;</span></a></li>
              @endif
          </ul>
        </nav>

      </div>

      @include('includes.left_sidebar')  
      @include('includes.footer')
</div>
@endsection