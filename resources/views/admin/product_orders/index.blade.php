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
                <div class="pull-left">
                    <h2>{{ app('request')->input('unlinked')? 'Unlinked' : 'All' }} product order Lists {{ $product_orders->total() }}</h2>
                </div>

                <div class="pull-right">
                    @if(empty(app('request')->input('unlinked')))
                        <a class="btn btn-success" href="{{ route('admin_product_orders.index') }}?unlinked=yes">View unlinked Product</a>
                    @else
                        <a class="btn btn-danger" href="{{ route('admin_product_orders.index') }}">View All Product</a>
                    @endif
                </div>
                <table id="mytable" class="table table-bordred table-striped">
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
                                <td>{{ $product_order->ad_fees * 80 }} BDT</td>
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
                {!! $product_orders->appends(\Input::except('page'))->render() !!}
        </div>
    </div>

@endsection

@section('run_custom_js_file')
@endsection

@section('run_custom_jquery')

@endsection