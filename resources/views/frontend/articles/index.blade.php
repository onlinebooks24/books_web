@extends('layouts.master')

@section('title')
    Online Books Review - Get review of best books
@endsection

@section('content')
@include('includes.header')

    <!-- Hero Slider -->
<section class="hero">
    <div id="owl-hero" class="owl-carousel owl-theme">

        <div class="hero__slide">
            <article class="hero__slide-entry entry">
                <div class="thumb-bg-holder" style="background-image: url(img/blog/featured_img_1.jpg)">
                    <a href="single-post.html" class="thumb-url"></a>
                    <div class="bottom-gradient"></div>
                </div>

                <div class="thumb-text-holder">
                    <a href="categories.html" class="entry__meta-category entry__meta-category--label">Startups</a>
                    <h2 class="thumb-entry-title">
                        <a href="single-post.html">Technology's impact on marketing</a>
                    </h2>
                </div>
            </article>
        </div>

        <div class="hero__slide">
            <article class="hero__slide-entry entry">
                <div class="thumb-bg-holder" style="background-image: url(img/blog/featured_img_2.jpg)">
                    <a href="single-post.html" class="thumb-url"></a>
                    <div class="bottom-gradient"></div>
                </div>

                <div class="thumb-text-holder">
                    <a href="categories.html" class="entry__meta-category entry__meta-category--label">Startups</a>
                    <h2 class="thumb-entry-title">
                        <a href="single-post.html">the Age of Artificial Intelligence</a>
                    </h2>
                </div>
            </article>
        </div>

        <div class="hero__slide">
            <article class="hero__slide-entry entry">
                <div class="thumb-bg-holder" style="background-image: url(img/blog/featured_img_3.jpg)">
                    <a href="single-post.html" class="thumb-url"></a>
                    <div class="bottom-gradient"></div>
                </div>

                <div class="thumb-text-holder">
                    <a href="categories.html" class="entry__meta-category entry__meta-category--label">Startups</a>
                    <h2 class="thumb-entry-title">
                        <a href="single-post.html">The Most Powerful Thing You Can Do Is Be Yourself</a>
                    </h2>
                </div>
            </article>
        </div>

    </div> <!-- end owl -->
</section> <!-- end hero slider -->

<!-- Ad Banner 728 -->
<div class="text-center">
    <a href="#">
        <img src="img/blog/placeholder_728.jpg" alt="">
    </a>
</div>


<!-- Content -->
<section class="section-wrap pb-0">
    <div class="container">
        <div class="row">

            <!-- Posts -->
            <div class="col-md-8 blog__content mb-30">

                <h3 class="section-title">Latest Posts</h3>

                @foreach($articles as $article)
                    <article class="entry post-list">
                        <div class="entry__img-holder post-list__img-holder">
                            <a href="{{ route('articles.show' , [ 'slug' => $article->slug ])}}">
                                <div class="thumb-container">
                                    @foreach($uploads as $upload)
                                        @php if($upload->id == $article->thumbnail_id){ @endphp
                                        <img data-src="{{ $upload->folder_path.'/'.$upload->name }}" src="{{ $upload->folder_path.'/'.$upload->name }}" class="entry__img lazyload" alt="{{ $article->title }}" />
                                        @php } @endphp
                                    @endforeach
                                </div>
                            </a>
                        </div>

                        <div class="entry__body post-list__body">
                            <div class="entry__header">
                                <a href="{{ route('category.post',['slug' => $article->category->slug ])}}" class="entry__meta-category">{{ $article->category->name }}</a>
                                <h2 class="entry__title">
                                    <a href="{{ route('articles.show' , [ 'slug' => $article->slug ])}}">{{ $article->title }}</a>
                                </h2>
                                <ul class="entry__meta">
                                    <li class="entry__meta-date">
                                        {{ $article->created_at->format('m-d-Y') }}
                                    </li>
                                    <li class="entry__meta-author">
                                        by <a href="#">{{ $article->user->name }}</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="entry__excerpt">
                                <p>{{ str_limit(strip_tags($article->body), $limit = 120, $end = '...') }}</p>
                            </div>
                        </div>
                    </article>
                @endforeach

            </div> <!-- end posts -->

            @include('includes.left_sidebar')

        </div>
    </div>
</section> <!-- end content -->

@include('includes.footer')

@endsection

