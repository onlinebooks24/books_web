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

            <div class="row">
                <div class="col-md-12">
                    <div>
                        Total whole sell: {{ $total_whole_sell * 80 }} BDT
                    </div>
                    <div>
                        Total sell from article: {{ $total_sell_from_article * 80 }} BDT
                    </div>
                    <div>
                        Total sell from non articles: {{ $total_sell_from_non_article * 80 }} BDT
                    </div>
                    <div>
                        Total cost on articles: {{ $total_sell_from_non_article }} BDT
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