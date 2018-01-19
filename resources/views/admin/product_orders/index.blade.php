@extends('layouts.admin_master')

@section('run_custom_css_file')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('admin_product_orders.store') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="file" name="product_orders_file">
                <input type="submit">
            </form>
        </div>
    </div>

@endsection

@section('run_custom_js_file')
@endsection

@section('run_custom_jquery')

@endsection