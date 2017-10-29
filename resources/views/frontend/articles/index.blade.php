@extends('layouts.master')

@section('title')
    Online Books Review
@endsection

@section('content')
@include('includes.header')
<div class="container">
  <div class="row">
      <div class="col-md-8 full-box">
         @foreach($articles as $article)
            <section>
                <div class="border-block">
                    <h2>
                        <a href="{{ route('articles.single' , [ 'slug' => $article->slug ])}}">{{ $article->title }}</a>
                    </h2>

                    <p><span class="glyphicon glyphicon-time"></span> Posted on {{ $article->created_at->format('m-d-Y') }} by <span class="author-name">{{ $article->user->name }}</span></p>
                    <hr>
                    @foreach($uploads as $upload)
                    @php if($upload->id == $article->thumbnail_id){ @endphp
                        <p align="center" class="thumbnail-image"><img class="img-responsive" src="{{ $upload->folder_path.'/'.$upload->name }}"></p>
                    @php } @endphp
                    @endforeach
                    <div class="top10">{!! Helper::readMoreHelper($article->body) !!}</div>
                    <div class="clearfix"></div>

                    <a class="btn btn-primary" style="float: right;" href="{{ route('articles.single' , [ 'slug' => $article->slug ])}}"> Read More <span class="glyphicon glyphicon-chevron-right"></span></a>
                    <div class="clearfix"></div>
                </div>
            </section>
         @endforeach
        <nav>
          <ul class="pager">
              @if($articles->currentPage() !== 1)
                <li class="previous"><a href="{{ $articles->previousPageUrl() }}"><span aria-hidden="true">&larr;</span> Newer</a></li>
              @endif
              @if($articles->currentPage() !== $articles->lastPage() && $articles->hasPages())
                <li class="next"><a href="{{ $articles->nextPageUrl() }}">Older <span aria-hidden="true">&rarr;</span></a></li>
              @endif
          </ul>
        </nav>
      </div>

      @include('includes.left_sidebar')
  </div>

    @include('includes.footer')
</div>

@endsection

