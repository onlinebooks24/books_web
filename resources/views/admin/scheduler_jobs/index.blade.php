@extends('layouts.admin_master')

@section('run_custom_css_file')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
            @endif

            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('admin_scheduler_jobs.create') }}">Add new task</a>
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
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                    @if(count($scheduler_jobs) > 0)
                        @foreach($scheduler_jobs as $scheduler_job)
                            <tr>
                                <td>{{ $scheduler_job->product_number }}</td>
                                <td>{{ $scheduler_job->title }}</td>
                                <td>{{ Carbon\Carbon::parse($scheduler_job->shipment_date)->toFormattedDateString() }}</td>
                                <td>{{ $scheduler_job->ad_fees * config('constants.dollar_rate') }} BDT</td>
                                <td>{{ $scheduler_job->manually_inserted_on_article }}</td>
                                <td>
                                    @if(!empty($scheduler_job->article_id))
                                        {{ $scheduler_job->article->title }}
                                    @endif
                                </td>
                                <td>
                                    @if(empty($scheduler_job->article_id))
                                        <a href="{{ route('admin_scheduler_jobs.edit', $scheduler_job ) }}">Edit</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>

                @if(count($scheduler_jobs) > 0)
                    {!! $scheduler_jobs->appends(\Input::except('page'))->render() !!}
                @endif
        </div>
    </div>

@endsection

@section('run_custom_js_file')
@endsection

@section('run_custom_jquery')

@endsection