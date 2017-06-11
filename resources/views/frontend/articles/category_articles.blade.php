@extends('layouts.master')

@section('title')
  {{ $categoryName }} | OnlineBooksReview
@endsection

@section('content')
@include('includes.header')
<div class="container">
  <div class="row">
      <div class="col-md-8 full-box">
         @if(!empty(Request::Segment(1)))
            <div class="alert alert-info">
              <strong>Category : </strong> {{ $categoryName }} . Show All Articles.
            </div>
         @endif
         @foreach($articles as $article)
             <h2>
                  <a href="{{ route('articles.single' , [ 'slug' => $article->slug ])}}">{{ $article->title }}</a>
              </h2>
              <p><span class="glyphicon glyphicon-time"></span> Posted on {{ $article->created_at->format('m-d-Y') }}  by <span class="author-name">{{$article->user->name }}</span></p>
              <!-- <hr>
              <img class="img-responsive" src="http://placehold.it/900x300" alt=""> -->
              <p>{!! str_limit($article->body,400) !!}</p>
              <a class="btn btn-primary" href="{{ route('articles.single' , [ 'slug' => $article->slug ])}}" style="float: right;">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>
              <div class="clearfix"></div>
         @endforeach

        <nav>
          <ul class="pager">
              @if($articles->currentPage() !== 1)
                <li class="previous"><a href="{{ $articles->previousPageUrl() }}"><span aria-hidden="true">&larr;</span> Older</a></li>
              @endif
              @if($articles->currentPage() !== $articles->lastPage() && $articles->hasPages())
                <li class="next"><a href="{{ $articles->nextPageUrl() }}">Newer <span aria-hidden="true">&rarr;</span></a></li>
              @endif
          </ul>
        </nav>

      </div>

      @include('includes.left_sidebar')
   </div>
    @include('includes.footer')
</div>
@endsection