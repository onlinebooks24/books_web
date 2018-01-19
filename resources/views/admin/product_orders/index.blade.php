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
        </div>
    </div>

@endsection

@section('run_custom_js_file')
@endsection

@section('run_custom_jquery')

@endsection