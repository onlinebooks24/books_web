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
                                        <span class="entry-author__name">Ross Green</span>
                                    </a>
                                </div>
                            </li>
                            <li class="entry__meta-date">
                                21 October, 2017
                            </li>
                            <li>
                                <span>in</span>
                                <a href="categories.html" class="entry__meta-category">devices</a>
                            </li>
                        </ul>
                    </div>

                    <div class="entry__img-holder">
                        <figure>
                            <img src="img/blog/single_post_featured_img.jpg" alt="" class="entry__img">
                            <figcaption>A photo collection samples</figcaption>
                        </figure>
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
                                Tags: <a href="#" rel="tag">mobile</a><a href="#" rel="tag">gadgets</a><a href="#" rel="tag">satelite</a>
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
                    <h5 class="entry-comments__title">3 comments</h5>

                    <ul class="comment-list">
                        <li class="comment">
                            <div class="comment-body">
                                <div class="comment-avatar">
                                    <img alt="" src="img/blog/comment_1.png">
                                </div>
                                <div class="comment-text">
                                    <h6 class="comment-author">Joeby Ragpa</h6>
                                    <div class="comment-metadata">
                                        <a href="#" class="comment-date">July 17, 2017 at 12:48 pm</a>
                                    </div>
                                    <p>This template is so awesome. I didn’t expect so many features inside. E-commerce pages are very useful, you can launch your online store in few seconds. I will rate 5 stars.</p>
                                    <a href="#" class="comment-reply">Reply</a>
                                </div>
                            </div>

                            <ul class="children">
                                <li class="comment">
                                    <div class="comment-body">
                                        <div class="comment-avatar">
                                            <img alt="" src="img/blog/comment_2.png">
                                        </div>
                                        <div class="comment-text">
                                            <h6 class="comment-author">Alexander Samokhin</h6>
                                            <div class="comment-metadata">
                                                <a href="#" class="comment-date">July 17, 2017 at 12:48 pm</a>
                                            </div>
                                            <p>This template is so awesome. I didn’t expect so many features inside. E-commerce pages are very useful, you can launch your online store in few seconds. I will rate 5 stars.</p>
                                            <a href="#" class="comment-reply">Reply</a>
                                        </div>
                                    </div>
                                </li> <!-- end reply comment -->
                            </ul>

                        </li> <!-- end 1-2 comment -->

                        <li>
                            <div class="comment-body">
                                <div class="comment-avatar">
                                    <img alt="" src="img/blog/comment_3.png">
                                </div>
                                <div class="comment-text">
                                    <h6 class="comment-author">Chris Root</h6>
                                    <div class="comment-metadata">
                                        <a href="#" class="comment-date">July 17, 2017 at 12:48 pm</a>
                                    </div>
                                    <p>This template is so awesome. I didn’t expect so many features inside. E-commerce pages are very useful, you can launch your online store in few seconds. I will rate 5 stars.</p>
                                    <a href="#" class="comment-reply">Reply</a>
                                </div>
                            </div>
                        </li> <!-- end 3 comment -->

                    </ul>
                </div> <!-- end comments -->


                <!-- Comment Form -->
                <div id="respond" class="comment-respond">
                    <h5 class="comment-respond__title">Post a comment</h5>
                    <p class="comment-respond__subtitle">Your email address will not be published. Required fields are marked*</p>
                    <form id="form" class="comment-form" method="post" action="#">
                        <p class="comment-form-comment">
                            <label for="comment">Comment</label>
                            <textarea id="comment" name="comment" rows="5" required="required"></textarea>
                        </p>

                        <div class="row row-20">
                            <div class="col-lg-4">
                                <label for="name">Name*</label>
                                <input name="name" id="name" type="text">
                            </div>
                            <div class="col-lg-4">
                                <label for="email">Email*</label>
                                <input name="email" id="email" type="email">
                            </div>
                            <div class="col-lg-4">
                                <label for="email">Website</label>
                                <input name="website" id="website" type="text">
                            </div>
                        </div>

                        <p class="comment-form-submit">
                            <input type="submit" class="btn btn-lg btn-color btn-button" value="Post Comment" id="submit-message">
                        </p>

                    </form>
                </div> <!-- end comment form -->

            </div> <!-- end col -->

            <!-- Sidebar -->
            <aside class="col-md-4 sidebar sidebar--right">

                <!-- Widget Popular Posts -->
                <div class="widget widget-popular-posts">
                    <h4 class="widget-title sidebar__widget-title">Popular Posts</h4>
                    <ul class="widget-popular-posts__list">
                        <li>
                            <article class="clearfix">
                                <div class="widget-popular-posts__img-holder">
                                    <span class="widget-popular-posts__number">1</span>
                                    <div class="thumb-container">
                                        <a href="single-post.html">
                                            <img data-src="img/blog/popular_post_1.jpg" src="img/blog/popular_post_1.jpg" alt="" class="lazyload">
                                        </a>
                                    </div>
                                </div>
                                <div class="widget-popular-posts__entry">
                                    <h3 class="widget-popular-posts__entry-title">
                                        <a href="single-post.html">How to get better Apple Watch battery life</a>
                                    </h3>
                                </div>
                            </article>
                        </li>
                        <li>
                            <article class="clearfix">
                                <div class="widget-popular-posts__img-holder">
                                    <span class="widget-popular-posts__number">2</span>
                                    <div class="thumb-container">
                                        <a href="single-post.html">
                                            <img data-src="img/blog/popular_post_2.jpg" src="img/blog/popular_post_2.jpg" alt="" class="lazyload">
                                        </a>
                                    </div>
                                </div>
                                <div class="widget-popular-posts__entry">
                                    <h3 class="widget-popular-posts__entry-title">
                                        <a href="single-post.html">8 Hidden Costs of Starting and Running a Business</a>
                                    </h3>
                                </div>
                            </article>
                        </li>
                        <li>
                            <article class="clearfix">
                                <div class="widget-popular-posts__img-holder">
                                    <span class="widget-popular-posts__number">3</span>
                                    <div class="thumb-container">
                                        <a href="single-post.html">
                                            <img data-src="img/blog/popular_post_3.jpg" src="img/blog/popular_post_3.jpg" alt="" class="lazyload">
                                        </a>
                                    </div>
                                </div>
                                <div class="widget-popular-posts__entry">
                                    <h3 class="widget-popular-posts__entry-title">
                                        <a href="single-post.html">The iPhone of Drones Is Being Built by This Teenager</a>
                                    </h3>
                                </div>
                            </article>
                        </li>
                        <li>
                            <article class="clearfix">
                                <div class="widget-popular-posts__img-holder">
                                    <span class="widget-popular-posts__number">4</span>
                                    <div class="thumb-container">
                                        <a href="single-post.html">
                                            <img data-src="img/blog/popular_post_4.jpg" src="img/blog/popular_post_4.jpg" alt="" class="lazyload">
                                        </a>
                                    </div>
                                </div>
                                <div class="widget-popular-posts__entry">
                                    <h3 class="widget-popular-posts__entry-title">
                                        <a href="single-post.html">Check Out This Video of Apple's New Futuristic Campus, Shot by a Drone</a>
                                    </h3>
                                </div>
                            </article>
                        </li>
                        <li>
                            <article class="clearfix">
                                <div class="widget-popular-posts__img-holder">
                                    <span class="widget-popular-posts__number">5</span>
                                    <div class="thumb-container">
                                        <a href="single-post.html">
                                            <img data-src="img/blog/popular_post_5.jpg" src="img/blog/popular_post_5.jpg" alt="" class="lazyload">
                                        </a>
                                    </div>
                                </div>
                                <div class="widget-popular-posts__entry">
                                    <h3 class="widget-popular-posts__entry-title">
                                        <a href="single-post.html">The New Media Moguls of Southeast Asia</a>
                                    </h3>
                                </div>
                            </article>
                        </li>
                    </ul>
                </div> <!-- end widget popular posts -->

                <!-- Widget Newsletter -->
                <div class="widget widget_mc4wp_form_widget">
                    <h4 class="widget-title">Subscribe for Neotech news and receive daily updates</h4>
                    <form id="mc4wp-form-1" class="mc4wp-form" method="post">
                        <div class="mc4wp-form-fields">
                            <p>
                                <i class="mc4wp-form-icon ui-email"></i>
                                <input type="email" name="EMAIL" placeholder="Your email" required="">
                            </p>
                            <p>
                                <input type="submit" class="btn btn-md btn-color" value="Subscribe">
                            </p>
                        </div>
                    </form>
                </div> <!-- end widget newsletter -->

                <!-- Widget socials -->
                <div class="widget widget-socials">
                    <h4 class="widget-title">Keep up with Neotech</h4>
                    <ul class="socials">
                        <li>
                            <a class="social-facebook" href="#" title="facebook" target="_blank">
                                <i class="ui-facebook"></i>
                                <span class="socials__text">Facebook</span>
                            </a>
                        </li>
                        <li>
                            <a class="social-twitter" href="#" title="twitter" target="_blank">
                                <i class="ui-twitter"></i>
                                <span class="socials__text">Twitter</span>
                            </a>
                        </li>
                        <li>
                            <a class="social-google-plus" href="#" title="google" target="_blank">
                                <i class="ui-google"></i>
                                <span class="socials__text">Google+</span>
                            </a>
                        </li>
                        <li>
                            <a class="social-instagram" href="#" title="instagram" target="_blank">
                                <i class="ui-instagram"></i>
                                <span class="socials__text">Instagram</span>
                            </a>
                        </li>
                    </ul>
                </div> <!-- end widget socials -->

                <!-- Widget Banner -->
                <div class="widget widget_media_image">
                    <a href="#">
                        <img src="img/blog/placeholder_300.jpg" alt="">
                    </a>
                </div> <!-- end widget banner -->

            </aside> <!-- end sidebar -->

        </div> <!-- end row -->
    </div> <!-- end container -->
</section> <!-- end content -->

<div class="container">
    <div class="row">
        <article>
            <div class="col-md-8">
                <div class="border-block">
                    <h1>
                        {{ $article->title }}
                    </h1>
                    <p><span class="glyphicon glyphicon-time"></span> Posted on {{ $article->created_at->format('m-d-Y') }}  by <span class="author-name">{{$article->user->name }}</span></p>
                    <!-- <hr>
                    <img class="img-responsive" src="http://placehold.it/900x300" alt=""> -->
                    <hr>
                    @foreach($uploads as $upload)
                        @php if($upload->id == $article->thumbnail_id){ @endphp
                        <p align="center" class="thumbnail-image"><img class="img-responsive" src="{{ $upload->folder_path.'/'.$upload->name }}"></p>
                        @php } @endphp
                    @endforeach
                    <p>{!! $article->body !!}</p>
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
            </div>
        </article>

    </div> <!--row -->
</div>
{{--@include('includes.left_sidebar')--}}

@include('includes.footer')

@endsection