@extends('layouts.master')
@section('title'){{ $article->title }} | Online Books Review @endsection
@section('meta_description'){{$article->meta_description}}@endsection
@section('meta_keyword'){{ $article->keyword }}@endsection

@section('content')
@include('includes.header')

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
                                        <span class="entry-author__name">{{ $article->user->name }}</span>
                                    </a>
                                </div>
                            </li>
                            <li class="entry__meta-date">
                                {{ $article->created_at->format('m-d-Y') }}
                            </li>
                            <li>
                                <span>in</span>
                                <a href="categories.html" class="entry__meta-category">{{ $article->category->name }}</a>
                            </li>
                        </ul>
                    </div>

                    <div class="entry__img-holder">
                        @foreach($uploads as $upload)
                            @php if($upload->id == $article->thumbnail_id){ @endphp
                            <figure>
                                <img src="{{ $upload->folder_path.'/'.$upload->name }}" alt="" class="entry__img">
                            </figure>
                            @php } @endphp
                        @endforeach
                    </div>

                    <div class="entry__article-holder">

                        <!-- Share -->
                        <div class="entry__share">
                            <div class="entry__share-inner">
                                <div class="socials">
                                    <a href="#" class="social-facebook entry__share-social" aria-label="facebook"><i class="ui-facebook"></i></a>
                                    <a href="#" class="social-twitter entry__share-social" aria-label="twitter"><i class="ui-twitter"></i></a>
                                    <a href="#" class="social-google-plus entry__share-social" aria-label="google+"><i class="ui-google"></i></a>
                                    <a href="#" class="social-instagram entry__share-social" aria-label="instagram"><i class="ui-instagram"></i></a>
                                </div>
                            </div>
                        </div> <!-- share -->

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
                                                <div class="text-center top10">
                                                    <a class="amazon_button" rel="nofollow" href="{{ $product->amazon_link }}" target="_blank">View Book</a>
                                                </div>
                                            </div>

                                            <br>
                                        </div>
                                        <hr>
                                        <div class="clearfix"></div>
                                    @endforeach
                                @endif
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
                                    <input type="email" name="EMAIL" placeholder="Your email" required="">
                                    <input type="submit" class="btn btn-md btn-color" value="Subscribe">
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Related Posts -->
                    <div class="related-posts">
                        <h5 class="related-posts__title">You might like</h5>
                        <div class="row row-20">
                            <div class="col-md-4">
                                <article class="related-posts__entry entry">
                                    <a href="single-post.html">
                                        <div class="thumb-container">
                                            <img src="img/blog/grid_post_img_3.jpg" data-src="img/blog/grid_post_img_3.jpg" alt="" class="entry__img lazyload">
                                        </div>
                                    </a>
                                    <div class="related-posts__text-holder">
                                        <h2 class="related-posts__entry-title">
                                            <a href="single-post.html">VR and playable on a console makes it a great option to PC related VR headsets</a>
                                        </h2>
                                    </div>
                                </article>
                            </div>
                            <div class="col-md-4">
                                <article class="related-posts__entry entry">
                                    <a href="single-post.html">
                                        <div class="thumb-container">
                                            <img src="img/blog/grid_post_img_4.jpg" data-src="img/blog/grid_post_img_4.jpg" alt="" class="entry__img lazyload">
                                        </div>
                                    </a>
                                    <div class="related-posts__text-holder">
                                        <h2 class="related-posts__entry-title">
                                            <a href="single-post.html">NASA is best known for building rockets and spacecraft</a>
                                        </h2>
                                    </div>
                                </article>
                            </div>
                            <div class="col-md-4">
                                <article class="related-posts__entry entry">
                                    <a href="single-post.html">
                                        <div class="thumb-container">
                                            <img src="img/blog/grid_post_img_5.jpg" data-src="img/blog/grid_post_img_5.jpg" alt="" class="entry__img lazyload">
                                        </div>
                                    </a>
                                    <div class="related-posts__text-holder">
                                        <h2 class="related-posts__entry-title">
                                            <a href="single-post.html">Digital cameras are always changing, adding new features</a>
                                        </h2>
                                    </div>
                                </article>
                            </div>
                        </div>
                    </div> <!-- end related posts -->

                </article> <!-- end standard post -->



                <!-- Comments -->
                <div class="entry-comments mt-30">
                    <div class="fb-comments" data-href=" <?php echo('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); ?>" data-width="100%" data-numposts="5"></div>
                    <script>
                        (function(d, s, id) {
                            var js, fjs = d.getElementsByTagName(s)[0];
                            if (d.getElementById(id)) return;
                            js = d.createElement(s); js.id = id;
                            js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.8&appId=935252503278915";
                            fjs.parentNode.insertBefore(js, fjs);
                        }(document, 'script', 'facebook-jssdk'));
                    </script>
                    <script type="text/javascript" src="//platform-api.sharethis.com/js/sharethis.js#property=590d63c61554ce0011357601&product=sticky-share-buttons"></script>
                </div> <!-- end comments -->

            </div> <!-- end col -->

            <!-- Sidebar -->
            @include('includes.left_sidebar')

        </div> <!-- end row -->
    </div> <!-- end container -->
</section> <!-- end content -->

@include('includes.footer')

@endsection