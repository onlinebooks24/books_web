@extends('layouts.admin_master')

@section('run_custom_css_file')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
            @endif

            <form action="{{ route('admin_product_orders.store') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="file" name="product_orders_file" placeholder="Please upload only amazon xml">
                <input type="submit">
            </form>

                <h2>Product order Lists</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product Number</th>
                            <th>Title</th>
                            <th>Shipment date</th>
                            <th>Ad Fees</th>
                            <th>Manually inserted</th>
                            <th>Article</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($product_orders as $product_order)
                            <tr>
                                <td>{{ $product_order->product_number }}</td>
                                <td>{{ $product_order->title }}</td>
                                <td>{{ Carbon\Carbon::parse($product_order->shipment_date)->toFormattedDateString() }}</td>
                                <td>{{ $product_order->ad_fees }}</td>
                                <td>{{ $product_order->manually_inserted_on_article }}</td>
                                <td>
                                    @if(!empty($product_order->article_id))
                                        {{ $product_order->article->title }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <section>
                    <nav>
                        <ul class="pager">
                            @if($product_orders->currentPage() !== 1)
                                <li class="previous"><a href="{{ $product_orders->previousPageUrl() }}"><span aria-hidden="true">&larr;</span>Newer</a></li>
                            @endif
                            @if($product_orders->currentPage() !== $product_orders->lastPage() && $product_orders->hasPages())
                                <li class="next"><a href="{{ $product_orders->nextPageUrl() }}">Older <span aria-hidden="true">&rarr;</span></a></li>
                            @endif
                        </ul>
                    </nav>
                </section>
        </div>
    </div>

@endsection

@section('run_custom_js_file')
@endsection

@section('run_custom_jquery')

@endsection