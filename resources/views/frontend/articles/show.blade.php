@extends('layouts.master')
@section('title'){{ empty($article->meta_title) ? $article->title : $article->meta_title }}@endsection
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

                            <li>
                                <span>in</span>
                                <a href="{{ route('category.post',['slug' => $article->category->slug ])}}" class="entry__meta-category">{{ $article->category->name }}</a>
                            </li>
                        </ul>
                    </div>

                    <div class="entry__img-holder text-center">
                        <figure>
                            @if(isset($article->thumbnail_image))
                                <img src="{{ $article->thumbnail_image->folder_path . $article->thumbnail_image->name }}" alt="{{ $article->thumbnail_alt_tag }}" class="img-responsive">
                            @endif
                        </figure>
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
                                            <h2 style="color: #337ab7; line-height:1.4">{{ ++$key }}. <a href="{{ $product->amazon_link }}" class="amazon-link"  data-product-title="{{ $product->product_title }}" name="{{ $product->isbn }}" rel="nofollow" target="_blank">{{ $product->product_title }}</a></h2>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <a rel="nofollow" href="{{ $product->amazon_link }}" class="amazon-link" data-product-title="{{ $product->product_title }}" target="_blank">
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
                                                    <a class="amazon_button top5 amazon-link"  data-product-title="{{ $product->product_title }}" rel="nofollow" href="{{ $product->amazon_link }}" target="_blank">View Book</a>
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
                               {!! $article->conclusion !!}
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
                </article> <!-- end standard post -->


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
                                            @if(isset($related_article->thumbnail_image))
                                                <img src="{{ $related_article->thumbnail_image->folder_path . 'obr_thumb_250_250_' . $related_article->thumbnail_image->name }}" style="height: 120px" data-src="{{ $related_article->thumbnail_image->folder_path . 'obr_thumb_250_250_' . $related_article->thumbnail_image->name }}" alt="" class="entry__img lazyload">
                                            @endif
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

            </div> <!-- end col -->

            <!-- Sidebar -->
            @include('includes.left_sidebar')
        </div> <!-- end row -->
    </div> <!-- end container -->
</section> <!-- end content -->

@include('includes.footer')

@endsection

@section('run_custom_js_file')
    <!--<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5b322acaa95624c1"></script>-->
    {{--<script type="text/javascript" src="//platform-api.sharethis.com/js/sharethis.js#property=590d63c61554ce0011357601&product=sticky-share-buttons"></script>--}}
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
@endsection


@section('run_custom_jquery')
   <script>
       $.ajax({
           url: "/get_location",
           dataType:"json",
       }).done(function(ipinfo)
       {
//                var latLong = ipinfo.loc.split(",");
//                var current_location_lat = latLong[0];
//                var current_location_lon = latLong[1];

           var country_code = ipinfo.country_code;
           if(country_code == 'GB' || country_code == 'CA'){
               $('.amazon-link').each(function(i, obj) {
                   var amazon_title = $(this).data('product-title');
                   var product_link = '';
                   if (country_code == 'GB'){
                       product_link = "https://www.amazon.co.uk/s?k=" + amazon_title.replace(/ /g, '+') + "&tag=onlinebook0d3-21";
                   } else if (country_code == 'CA'){
                       product_link = "https://www.amazon.ca/s?k=" + amazon_title.replace(/ /g, '+') + "&tag=onlinebook0af-20";
                   }
                   $(this).attr("href", product_link);
               });
           }
       });
   </script>

@endsection

