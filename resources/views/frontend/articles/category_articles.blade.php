@extends('layouts.master')

@section('meta_description')
    {{ $category->name }} | OnlineBooksReview
@endsection

@section('title')
  {{ $category->name }} | OnlineBooksReview
@endsection

@section('content')
@include('includes.header')

<div class="category_id" data-value="{{ $category->id }}"></div>

<section class="section-wrap pt-50 pb-30">
    <div class="container">
        @if(!empty(Request::Segment(1)))
            <div class="alert alert-info">
                <strong>Category : </strong> {{ $category->name }} . Show All Articles.
            </div>
        @endif
        <div class="row">

            <!-- Posts -->
            <div class="col-md-8 blog__content mb-30">
                <h3 class="section-title">Category Posts</h3>

                <div class="row">
                    @foreach($articles as $article)
                        <div class="col-lg-6">
                            <article class="entry">
                                <div class="entry__img-holder">
                                    <a href="{{ route('articles.show' , [ 'slug' => $article->slug ])}}">
                                        <div class="thumb-container">
                                            @if(isset($article->thumbnail_image))
                                            <img data-src="{{ $article->thumbnail_image->folder_path . $article->thumbnail_image->name }}" src="{{ $article->thumbnail_image->folder_path . $article->thumbnail_image->name }}" class="entry__img lazyload" alt="{{ $article->title }}" />
                                            @endif
                                        </div>
                                    </a>
                                </div>

                                <div class="entry__body">
                                    <div class="entry__header">
                                        <a href="{{ route('category.post',['slug' => $article->category->slug ])}}" class="entry__meta-category">{{ $article->category->name }}</a>
                                        <h2 class="entry__title">
                                            <a href="{{ route('articles.show' , [ 'slug' => $article->slug ])}}">{{ $article->title }}</a>
                                        </h2>
                                        <ul class="entry__meta">
                                            <li class="entry__meta-date">
                                                21 October, 2017
                                            </li>
                                            <li class="entry__meta-author">
                                                by <a href="#">DeoThemes</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="entry__excerpt">
                                        <p>
                                            {{ str_limit(strip_tags($article->body), 200) }}
                                        </p>
                                    </div>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>

            </div> <!-- end posts -->

            <!-- Sidebar -->
            @include('includes.left_sidebar')
        </div>
    </div>
</section> <!-- end content -->


@endsection