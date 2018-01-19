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
                <h2>All Site Costs</h2>
            </div>

            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('admin_site_costs.create') }}">Add New Cost</a>
            </div>
            <table id="mytable" class="table table-bordred table-striped">
                <thead>
                <tr>
                    <th>Description</th>
                    <th>Site Cost Type</th>
                    <th>Amount</th>
                    <th>When Paid</th>
                    <th>Article</th>
                </tr>
                </thead>
                <tbody>
                @foreach($site_costs as $site_cost)
                    <tr>
                        <td>{{ $site_cost->description }}</td>
                        <td>{{ $site_cost->site_cost_type->name }}</td>
                        <td>{{ $site_cost->amount }} BDT</td>
                        <td>{{ $site_cost->when_paid }} </td>
                        <td>
                            @if(!empty($site_cost->article))
                                {{ $site_cost->article->title }}
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {!! $site_costs->appends(\Input::except('page'))->render() !!}
        </div>
    </div>

@endsection

@section('run_custom_js_file')
@endsection

@section('run_custom_jquery')

@endsection