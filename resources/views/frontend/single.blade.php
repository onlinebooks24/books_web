@extends('layouts.master')

@section('title')
{{ $post->title }} | OnlineBooksReview
@endsection

@section('content')
@include('includes.header')
<div class="container">
    <div class="row">
        <div class="col-md-8">
           <div class="well">
               <h1>
                 {{ $post->title }}
               </h1>
               <p><span class="glyphicon glyphicon-time"></span> Posted on {{ $post->created_at->format('m-d-Y') }}  by <span style="color: blue;text-transform: capitalize;">{{$post->user->name }}</span></p>
               <!-- <hr>
               <img class="img-responsive" src="http://placehold.it/900x300" alt=""> -->
               <hr>
               <p>{!! $post->body !!}</p>
           </div>

        
        <div class="fb-comments" data-href=" <?php echo('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); ?>" data-width="100%" data-numposts="5"></div>

        <div id="fb-root"></div>
        <script>
        (function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.8&appId=935252503278915";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
        </script>

        </div>
        
    @include('includes.left_sidebar')  
    @include('includes.footer')
</div>
@endsection