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
                    <h5>Sell:</h5>
                    <div class="alert alert-info">
                        Total whole sell: {{ $total_whole_sell * config('constants.dollar_rate') }} BDT
                    </div>
                    <div class="alert alert-success">
                        Total sell from article: {{ $total_sell_from_article * config('constants.dollar_rate') }} BDT
                    </div>
                    <div class="alert alert-success">
                        Total sell from non articles: {{ $total_sell_from_non_article * config('constants.dollar_rate') }} BDT
                    </div>

                    <h5>
                        Cost:
                    </h5>
                    <div class="alert alert-info">
                        <div class="bottom5">Total Cost: {{ $total_costs }} BDT</div>
                        @foreach($individual_costs as $key => $value)
                            <span class="btn btn-success">{{ $key }} cost: {{ $value }} BDT</span>
                        @endforeach
                    </div>

                    @foreach($all_costs as $key=> $value)
                        <div class="alert alert-danger">{{ $key }} cost: {{ $value }} BDT</div>
                    @endforeach
                </div>
                <div class="col-md-6">
                    <h5>Article publish ratio</h5>
                    <div class="row">
                        @foreach($total_articles as $key => $value)
                            <div class="col-md-6">
                                <div class="alert alert-success">{{ $key }}: {{ $value }}</div>
                            </div>
                        @endforeach
                    </div>

                    <h5>Who has written articles</h5>
                    <div class="row">
                        @foreach($individual_articles as $key => $value)
                            <div class="col-md-5 btn btn-warning top5 left5">
                                <div>{{ $key }}: {{ $value }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('run_custom_js_file')
@endsection

@section('run_custom_jquery')

@endsection