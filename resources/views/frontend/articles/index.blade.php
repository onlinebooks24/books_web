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
            <div class="row">
                @foreach ($parent_categories as $parent_category)
                    <div class="col-md-2">
                        <p>{{ $parent_category->name }}</p>
                        <img class="img-thumbnail image_height" src="{{ asset('uploads/category_images/'.$parent_category->id. '.' . 'jpg') }}" alt="">
                    </div>
                @endforeach
            </div>
    </div>
</section>
<br>
<!-- Content -->
<div class="container">
    <div class="row">
        <div class="col-md-8 blog__content mb-30">
           <section class="content-section section-editors-choice">
              @foreach ($parent_with_articles as $parent_category_name=>$articles )
                <div class="section-title-wrap top20">
                    <h3 class="section-title">{{ $parent_category_name }}</h3>
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
                                                <h3 class="post-list-small__entry-title">
                                                    {{ str_limit(strip_tags($article->title), $limit = 80, $end = '...') }}
                                                </h3>
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

@section('run_custom_js_file')
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
@endsection