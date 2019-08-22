@extends('layouts.master')

@section('title')
    Article Search Results
@endsection

@section('content')
    @include('includes.header')

    <!-- Content -->
    <section class="section-wrap pb-0">
        <div class="container">
            <div class="row">

                <!-- Posts -->
                <div class="col-md-8 blog__content mb-30">

                    <h3>Search Keyword: {{ $query }}</h3>

                    @if($articles->count() > 0)
                        @foreach($articles as $article)
                            <article class="entry post-list">
                                <div class="entry__img-holder post-list__img-holder">
                                    <a href="{{ route('articles.show' , [ 'slug' => $article->slug ])}}">
                                        <div class="thumb-container">
                                            @if(isset($article->thumbnail_image))
                                                <img data-src="{{ $article->thumbnail_image->folder_path  . 'obr_thumb_250_250_' .  $article->thumbnail_image->name }}" src="{{ $article->thumbnail_image->folder_path . 'obr_thumb_250_250_' . $article->thumbnail_image->name }}" class="entry__img lazyload" alt="{{ $article->title }}" />
                                            @endif
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
                                            <li class="entry__meta-author">
                                                by <a href="#">onlinebooksreview</a>
                                            </li>
                                            <li>
                                                <span>in</span>
                                                <a href="{{ route('category.post',['slug' => $article->category->slug ])}}" class="entry__meta-category">{{ $article->category->name }}</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="entry__excerpt">
                                        <p>{{ str_limit(strip_tags($article->body), $limit = 120, $end = '...') }}</p>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    @else

                        <h3>Not Found</h3>

                    @endif

                <!-- Pagination -->


                    <nav>
                        <ul class="pager top10">
                            @if($articles->currentPage() !== 1)
                                <div class="pagination clearfix">
                                    <div class="pagination__link left">
                                        <a href="{{ $articles->previousPageUrl() }}" class="btn btn-lg btn-color">
                                            <span>Newer</span>
                                        </a>
                                    </div>
                                </div>
                            @endif

                            @if($articles->currentPage() !== $articles->lastPage() && $articles->hasPages())
                                <div class="pagination clearfix top-25">
                                    <div class="pagination__link right top-30">
                                        <a href="{{ $articles->nextPageUrl() }}" class="btn btn-lg btn-color">
                                            <span>Older</span>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </ul>
                    </nav>
                </div> <!-- end posts -->

                @include('includes.left_sidebar')

            </div>
        </div>
    </section> <!-- end content -->

    @include('includes.footer')

@endsection

@section('run_custom_js_file')
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
@endsection