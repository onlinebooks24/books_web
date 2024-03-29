@extends('layouts.master')

@section('title')
    Online Books Review - Get review of best books
@endsection

@section('content')
@include('includes.header')

    
<section>
    <div class="container">
      <br>
        <h2 class="text-center">Books By Category</h2><br>
            <div class="row text-center">
                @foreach ($parent_categories as $key => $parent_category)
                    <div class="col-md-2 bottom15">
                        <a style="color: #49545E" target="_blank" href="{{ route('category.post',['slug' => $parent_category->slug ])}}">
                            <div>
                                 <img style="width: 50px; height: 50px" class="img-thumbnail image_height" src="{{ asset('img/category_images/'.$key. '.' . 'png') }}" alt="">
                            </div>
                            {{ $parent_category->name }}
                        </a>
                    </div>
                @endforeach
            </div>
    </div>
</section>
<hr>
<br>
<!-- Content -->
<div class="container">
    <div class="row">
        <div class="col-md-8 blog__content mb-30">
           <section class="content-section section-editors-choice">
              @foreach ($parent_with_articles as $parent_category_name => $articles )
               @php
                   $slug = strtolower($parent_category_name);
                   $slug = str_replace('&', 'and', $slug);
                   $slug = str_replace(' ', '-', $slug);
               @endphp

                <div class="section-title-wrap top20">
                    <h2 class="section-title"><a href="{{ route('category.post',['slug' => $slug ])}}">{{ $parent_category_name }}</a></h2>
                </div>
                <div class="row">
                    @foreach ($articles as $key => $article)
                        <div class="col-md-6">
                            <ul class="post-list-small">
                                <li class="post-list-small__item bottom15">
                                    <article class="post-list-small__entry">
                                        <a href="{{ route('articles.show' , [ 'slug' => $article->slug ])}}" class="clearfix">
                                            <div class="post-list-small__img-holder ">
                                                <div class="thumb-container">
                                                    @if(isset($article->thumbnail_image->folder_path))
                                                        <img style="height: 200px; width: 350px" src="{{ $article->thumbnail_image->folder_path  .  $article->thumbnail_image->name }}" class="post-list-small__img" alt="No Photo">
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="post-list-small__body">
                                                <h4 class="post-list-small__entry-title">
                                                    {{ str_limit(strip_tags($article->title), $limit = 90, $end = '...') }}
                                                </h4>
                                                <div class="entry__excerpt">
                                                    <p>{{ str_limit(strip_tags($article->body), $limit = 120, $end = '...') }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    </article>
                                </li>
                            </ul>
                        </div>
                    @endforeach

                    <div class="clearfix"></div>
                    <div class="col-md-12">
                        <div class="text-center">
                            <a class="btn btn-lg btn-color" href="{{ route('category.post',['slug' => $slug ])}}">View All</a>
                        </div>
                    </div>
                </div>
                <hr>
               @endforeach
            </section>
        </div>
        @include('includes.left_sidebar')
    </div>
</div>
@include('includes.footer')

@endsection
