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
                <a class="btn btn-success" href="{{ route('admin_collect_mail_queues.create') }}">Add new queue</a>
            </div>
            <table id="mytable" class="table table-bordred table-striped">
                <thead>
                <tr>
                    <th>topic</th>
                    <th>category_id</th>
                    <th>article_id</th>
                    <th>custom_mail_template</th>
                    <th>run_count</th>
                    <th>last_time_run</th>
                    <th>run_cron_job</th>
                    <th>limit_cron_job_attempt</th>
                </tr>
                </thead>
                <tbody>
                    @if(count($collect_mail_queues) > 0)
                        @foreach($collect_mail_queues as $item)
                            <tr>
                                <td>{{ $item->topic }}</td>
                                <td>{{ $item->category_id }}</td>
                                <td>{{ $item->article_id }}</td>
                                <td>{{ $item->custom_mail_template }}</td>
                                <td>{{ $item->run_count }}</td>
                                <td>{{ $item->last_time_run }}</td>
                                <td>{{ $item->run_cron_job }}</td>
                                <td>{{ $item->limit_cron_job_attempt }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>

                @if(count($collect_mail_queues) > 0)
                    {!! $collect_mail_queues->appends(\Input::except('page'))->render() !!}
                @endif
        </div>
    </div>

@endsection

@section('run_custom_js_file')
@endsection

@section('run_custom_jquery')

@endsection