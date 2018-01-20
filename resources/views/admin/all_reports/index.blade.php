@extends('layouts.admin_master')

@section('run_custom_css_file')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
            @endif
            <div class="pull-left">
                <h2>All Reports</h2>
            </div>

            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('admin_site_costs.create') }}">Add New Cost</a>
            </div>

            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-6">
                    <h5>Sell and Cost</h5>
                    <div class="alert alert-info">
                        Total whole sell: {{ $total_whole_sell * config('constants.dollar_rate') }} BDT
                    </div>
                    <div class="alert alert-success">
                        Total sell from article: {{ $total_sell_from_article * config('constants.dollar_rate') }} BDT
                    </div>
                    <div class="alert alert-success">
                        Total sell from non articles: {{ $total_sell_from_non_article * config('constants.dollar_rate') }} BDT
                    </div>


                    <div class="alert alert-info">Total Cost: {{ $total_costs }} BDT</div>
                    @foreach($all_costs as $key=> $value)
                        <div class="alert alert-danger">{{ $key }} cost: {{ $value }} BDT</div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@endsection

@section('run_custom_js_file')
@endsection

@section('run_custom_jquery')

@endsection