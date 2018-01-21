@extends('layouts.master')
@section('title'){{ $article->title }} | Online Books Review @endsection
@section('meta_description'){{$article->meta_description}}@endsection
@section('meta_keyword'){{ $article->keyword }}@endsection

@section('content')
@include('includes.header')

    <div class="category_id" data-value="{{ $article->category->id }}"></div>
        <!-- Content -->
<section class="section-wrap pt-60 pb-20">
    <div class="container">
        <div class="row">

            <!-- post content -->
            <div class="col-md-8 blog__content mb-30">

                <!-- standard post -->
                <article class="entry">

                    <div class="single-post__entry-header  entry__header">

                        <h1 class="single-post__entry-title">
                            {{ $article->title }}
                        </h1>

                        <ul class="single-post__entry-meta entry__meta">
                            <li>
                                <div class="entry-author">
                                    <a href="#" class="entry-author__url">
                                        <img src="img/blog/author.png" class="entry-author__img" alt="">
                                        <span>by</span>
                                        <span class="entry-author__name">onlinebooksreview</span>
                                    </a>
                                </div>
                            </li>
                            <li class="entry__meta-date">
                                {{ $article->created_at->format('m-d-Y') }}
                            </li>
                            <li>
                                <span>in</span>
                                <a href="{{ route('category.post',['slug' => $article->category->slug ])}}" class="entry__meta-category">{{ $article->category->name }}</a>
                            </li>
                        </ul>
                    </div>

                    <div class="entry__img-holder text-center">
                        @foreach($uploads as $upload)
                            @php if($upload->id == $article->thumbnail_id){ @endphp
                            <figure>
                                <img src="{{ $upload->folder_path.'/'.$upload->name }}" alt="" class="img-responsive">
                            </figure>
                            @php } @endphp
                        @endforeach
                    </div>

                    <div class="entry__article-holder">
                        <div class="entry__article">
                            <div>
                                {!! $article->body !!}
                            </div>

                            <div>
                                @if( count($products) > 0 )
                                    @foreach($products as $key=>$product)
                                        <div class="bottom20">
                                            <h2 style="color: #337ab7; line-height:1.4">{{ ++$key }}. <a href="{{ $product->amazon_link }}" name="{{ $product->isbn }}" rel="nofollow" target="_blank">{{ $product->product_title }}</a></h2>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <a rel="nofollow" href="{{ $product->amazon_link }}" target="_blank">
                                                    <img alt="{{ $product->product_title }}" src="{{ $product->image_url }}">
                                                </a>
                                                <div class="top10">
                                                    <strong>Author:</strong> {{ $product->author_name }}
                                                </div>

                                                <div class="top5">
                                                    <strong>Published at:</strong>
                                                    {{ \Carbon\Carbon::parse($product->publication_date)->format('d/m/Y')}}
                                                </div>

                                                <div class="top5">
                                                    <strong>ISBN:</strong> {{ $product->isbn }}
                                                </div>
                                            </div>

                                            <div class="col-md-7">
                                                <div>{!! $product->product_description !!} </div>
                                                <div class="clearfix"></div>
                                                <div class="text-center">
                                                    <a class="amazon_button top5" rel="nofollow" href="{{ $product->amazon_link }}" target="_blank">View Book</a>
                                                </div>
                                            </div>

                                            <br>
                                        </div>
                                        <hr>
                                        <div class="clearfix"></div>

                                        @if($key == 7)
                                            <!-- OnlineBooksReview Article middle display -->
                                            <ins class="adsbygoogle"
                                                 style="display:block"
                                                 data-ad-client="ca-pub-1505016841070170"
                                                 data-ad-slot="2448383802"
                                                 data-ad-format="auto"></ins>
                                            <script>
                                                (adsbygoogle = window.adsbygoogle || []).push({});
                                            </script>
                                            <hr>
                                        @endif
                                    @endforeach
                                @endif
                            </div>

                            <div>
                               {{ $article->conclusion }}
                            </div>

                            @if(count($ordered_product_articles) > 0)
                            <div style="border: 1px dashed grey; padding: 10px">
                                <div>Also you can check these books:</div>
                                @foreach($ordered_product_articles as $item)
                                    <li>
                                        <a class="bottom5" href="http://www.amazon.com/dp/{{$item->product_number}}/?tag=onlinebooksre-20" target="_blank">{{ $item->title }}</a>
                                    </li>
                                @endforeach
                            </div>
                            <br>
                            @endif

                            <div>
                                Thanks for reading this post. If you have any opinion don't hesitate to comment here. Also please subscribe our newsletter to get more updates.
                            </div>

                            <!-- tags -->
                            <div class="entry__tags">
                                Tags: <a href="#" rel="tag">{{ $article->category->name }}</a>
                            </div> <!-- end tags -->

                        </div> <!-- end entry article -->
                    </div>

                    <!-- Newsletter -->
                    <div class="newsletter-wide widget widget_mc4wp_form_widget">
                        <div class="newsletter-wide__text">
                            <h4 class="widget-title">Subscribe for Neotech news and receive daily updates</h4>
                        </div>

                        <div class="newsletter-wide__form">
                            <form class="mc4wp-form" method="post">
                                <div class="mc4wp-form-fields">
                                    <i class="mc4wp-form-icon ui-email"></i>
                                    <input type="email" name="email" class="update_email" placeholder="Your email" required="">
                                    <input type="submit" class="btn btn-md btn-color btn-subscribe" value="Subscribe">
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Related Posts -->
                    <div class="related-posts">
                        <h5 class="related-posts__title">You might like</h5>
                        <div class="row row-20">
                            @foreach($related_articles as $related_article)
                                <div class="col-md-4">
                                <article class="related-posts__entry entry">
                                    <a href="{{ route('articles.show' , [ 'slug' => $related_article->slug ])}}">
                                        <div class="thumb-container">
                                            @foreach($uploads as $upload)
                                                @php if($upload->id == $related_article->thumbnail_id){ @endphp
                                                <img src="{{ $upload->folder_path.'/'.$upload->name }}" style="height: 120px" data-src="{{ $upload->folder_path.'/'.$upload->name }}" alt="" class="entry__img lazyload">
                                                @php } @endphp
                                            @endforeach
                                        </div>
                                    </a>
                                    <div class="related-posts__text-holder">
                                        <h2 class="related-posts__entry-title">
                                            <a href="{{ route('articles.show' , [ 'slug' => $related_article->slug ])}}">{{ $related_article->title }}</a>
                                        </h2>
                                    </div>
                                </article>
                            </div>
                            @endforeach
                        </div>
                    </div> <!-- end related posts -->

                </article> <!-- end standard post -->



                <!-- Comments -->
                <div class="entry-comments mt-30">
                    <div id="disqus_thread"></div>
                    <script>

                        /**
                         *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
                         *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables*/
                        /*
                         var disqus_config = function () {
                         this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable
                         this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
                         };
                         */
                        (function() { // DON'T EDIT BELOW THIS LINE
                            var d = document, s = d.createElement('script');
                            s.src = 'https://onlinebooksreview.disqus.com/embed.js';
                            s.setAttribute('data-timestamp', +new Date());
                            (d.head || d.body).appendChild(s);
                        })();
                    </script>
                    <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
                </div> <!-- end comments -->

            </div> <!-- end col -->

            <!-- Sidebar -->
            @include('includes.left_sidebar')
        </div> <!-- end row -->
    </div> <!-- end container -->
</section> <!-- end content -->

@include('includes.footer')

@endsection

@section('run_custom_js_file')
    <script id="dsq-count-scr" src="//onlinebooksreview.disqus.com/count.js" async></script>
    <script type="text/javascript" src="//platform-api.sharethis.com/js/sharethis.js#property=590d63c61554ce0011357601&product=sticky-share-buttons"></script>
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
@endsection

