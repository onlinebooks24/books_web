@extends('layouts.admin_master')

@section('run_custom_css_file')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 bottom30">
            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
            @endif


            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('admin_product_orders.index') }}">View All Product Orders</a>
            </div>

            <h4>Edit product order</h4>
            <div class="row">
                <div class="col-md-8">
                    <form action="{{ route('admin_product_orders.update', $product_order) }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input name="_method" type="hidden" value="PUT">
                        <div class="form-group">
                            <label>Product Number</label>
                            <input type="text" name="product_number" class="form-control" placeholder="product_number" value="{{ $product_order->product_number }}">
                        </div>

                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" placeholder="title" value="{{ $product_order->title }}">
                        </div>

                        <div class="form-group">
                            <label>Product id</label>
                            <input type="text" name="product_id" class="form-control" placeholder="product_id" value="{{ $product_order->product_id }}">
                        </div>

                        <div class="form-group">
                            <label>Shipment date</label>
                            <input type="text" name="shipment_date" class="form-control" placeholder="shipment_date" value="{{ $product_order->shipment_date }}">
                        </div>

                        <div class="form-group">
                            <label>ad_fees</label>
                            <input type="text" name="ad_fees" class="form-control" placeholder="ad_fees" value="{{ $product_order->ad_fees }}">
                        </div>

                        <div class="form-group">
                            <label>Manually inserted on article</label>
                            <input type="text" name="manually_inserted_on_article" class="form-control" placeholder="manually_inserted_on_article" value="1">
                        </div>
                        <div class="form-group">
                            <label>Article id</label>
                            <input type="text" name="article_id" class="form-control article-id-field" placeholder="article_id" value="{{ $product_order->article_id }}">
                        </div>
                        <div>
                            <div>
                                Suggestion:
                            </div>
                            @foreach($suggestion_articles as $suggestion_article)
                            <li>{{ $suggestion_article->id }} : <a target="_blank" class="suggestion-article" data-article-id="{{ $suggestion_article->id }}" href="#">{{ $suggestion_article->title }}</a></li>
                            @endforeach
                        </div>
                        <br/>
                        <div class="form-group">
                            <label>Product type</label>
                            <input type="text" name="product_type" class="form-control" placeholder="product_type" value="{{ $product_order->product_type }}">
                        </div>

                        <input type="submit" value="update" class="submit-form">
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('run_custom_js_file')
@endsection

@section('run_custom_jquery')
    <script>
        $('.suggestion-article').click(function (e) {
            e.preventDefault();
            var article_id = $(this).data('article-id');
            $('.article-id-field').val(article_id);
            $('.submit-form').click();
        });
    </script>
@endsection