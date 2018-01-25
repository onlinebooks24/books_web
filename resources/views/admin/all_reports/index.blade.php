@extends('layouts.admin_master')

@section('run_custom_css_file')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
            @endif
            <div>
                <h2>All Reports</h2>
            </div>

            <div class="text-center">
                <h2 class="alert alert-danger">Last You have published a article {{$last_article->created_at->diffForHumans()}}. Publish new article as early as possible.</h2>
            </div>

            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-6">
                    <h5>Sell:</h5>
                    <div class="alert alert-success">
                        <div class="bottom5">
                            Total whole sell: {{ $total_whole_sell * config('constants.dollar_rate') }} BDT
                        </div>
                        <span class="btn btn-success">
                            Total sell from article: {{ $total_sell_from_article * config('constants.dollar_rate') }} BDT
                        </span>
                        <span class="btn btn-success">
                            Total sell from non articles: {{ $total_sell_from_non_article * config('constants.dollar_rate') }} BDT
                        </span>
                    </div>


                    <h5>
                        Cost:
                    </h5>
                    <div class="alert alert-danger">
                        <div class="bottom5">Total Cost: {{ $total_costs }} BDT</div>
                        @foreach($individual_costs as $key => $value)
                            <span class="btn btn-danger">{{ $key }} cost: {{ $value }} BDT</span>
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
                                <div class="alert alert-info">{{ $key }}: {{ $value }}</div>
                            </div>
                        @endforeach
                    </div>

                    <h5>Who has written articles</h5>
                    <div class="row">
                        @foreach($individual_articles as $key => $value)
                            <div class="col-md-5 btn btn-warning top5 left5">
                                <div class="alert alert-info">
                                    {{ $key }}
                                    <div>
                                         Total Article: {{ $value }}
                                    </div>
                                    <div>
                                        No Selling Article: {{ $individual_no_sell[$key] }}
                                    </div>
                                    <div>
                                        Total Cost: {{ $individual_cost[$key] }}
                                    </div>
                                    <div>
                                        Total Revenue: {{ $individual_revenue[$key] * config('constants.dollar_rate') }}
                                    </div>
                                </div>
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