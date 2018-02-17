<style>
    .amazon_button {
        background-color: #f90;
        background-image: linear-gradient(to top, #ff9900 60%, #ffbb44 100%);
        border-radius: 0.4em;
        box-shadow: 1px 1px 2px #888888;
        color: #fff !important;
        display: inline-block;
        font-family: Arial;
        font-size: 89%;
        font-weight: bold;
        padding: 0.7em;
        text-align: center;
        text-decoration: none;
        width: 104px;
    }
</style>

<h1 style="text-align: center">Online Books Review</h1>
<hr>
<div>
    <h2>{{ $article->title }}</h2>
</div>
<div>
    {!! $article->body !!}
</div>

<div style="background: white; border: 1px solid grey">
    @if( count($products) > 0 )
        @foreach($products as $key=>$product)
            <div>
                <h4>{{ ++$key }}. <a href="{{ route('articles.show' , [ 'slug' => $article->slug ]). '?email=' . $email . '#' . $product->isbn }}" name="{{ $product->isbn }}" rel="nofollow" target="_blank">{{ $product->product_title }}</a></h4>
            </div>
            <div style="margin-top: -5px">
                <div>
                    <strong>Author:</strong> {{ $product->author_name }}
                </div>

                <div>
                    <strong>Published at:</strong>
                    {{ \Carbon\Carbon::parse($product->publication_date)->format('d/m/Y')}}
                </div>

                <div>
                    <strong>ISBN:</strong> {{ $product->isbn }}
                </div>
            </div>

            <div style="margin-top: 5px; margin-bottom: 10px">
                {!! str_limit(strip_tags($product->product_description), $limit = 320, $end = '...') !!}

                <div style="margin-top: 10px; text-align: center">
                    <a class="amazon_button" rel="nofollow" href="{{ route('articles.show' , [ 'slug' => $article->slug ]). '?email=' . $email . '#' . $product->isbn }}" target="_blank">View Book</a>
                </div>
            </div>
            <hr>
        @endforeach
    @endif
</div>

<p>
    <div>
        Thanks
    </div>
    <div>
        OnlineBooksReview Team
    </div>
    <div>
        <a href="https://www.onlinebooksreview">www.onlinebooksreview.com</a>
    </div>
</p>
<br>
<div style="font-size: 8px; color: grey; text-align: center; text-decoration: normal"><a href="#">Unsubscribe</a></div>

