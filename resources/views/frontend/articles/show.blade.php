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

    @include('includes.left_sidebar')
    </div> <!--row -->
    @include('includes.footer')
</div>
@endsection