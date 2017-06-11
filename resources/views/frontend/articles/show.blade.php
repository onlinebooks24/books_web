@extends('layouts.master')
@section('title'){{ $article->title }} | Online Books Review @endsection
@section('meta_description'){{$article->meta_description}}@endsection
@section('meta_keyword'){{ $article->keyword }}@endsection

@section('content')
@include('includes.header')
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
                    <p>{!! $article->body !!}</p>
                    @if( count($products) > 0 )
                        @foreach($products as $key=>$product)
                            <div class="bottom20">
                                <h2 style="color: #337ab7; line-height:1.4">{{ ++$key }}. {{ $product->product_title }}</h2>
                            </div>
                            <p align="center">
                                <img src="{{ $product->image_url }}">
                                <br>
                            </p>
                            <div>{!! $product->product_description !!} </div>

                            <div class="affiliate_group">
                                <a class="amazon_button" rel="nofollow" href="{{ $product->amazon_link }}" target="_blank">View</a>
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

    @include('includes.left_sidebar')
    </div> <!--row -->
    @include('includes.footer')
</div>
@endsection