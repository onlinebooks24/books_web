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
                <a class="btn btn-success" href="{{ route('admin_scheduler_jobs.create') }}">Add new notification</a>
            </div>
            <table id="mytable" class="table table-bordred table-striped">
                <thead>
                <tr>
                    <th>Task_description</th>
                    <th>Short_message</th>
                    <th>Task completed</th>
                    <th>Notification interval</th>
                    <th>Notification type</th>
                    <th>Deadline</th>
                    <th>Article Title</th>
                    <th>User</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                    @if(count($scheduler_jobs) > 0)
                        @foreach($scheduler_jobs as $scheduler_job)
                            <tr>
                                <td>{{ $scheduler_job->task_description }}</td>
                                <td>{{ $scheduler_job->short_message }}</td>
                                <td>{{ $scheduler_job->task_completed }}</td>
                                <td>{{ $scheduler_job->notification_interval }}</td>
                                <td>{{ $scheduler_job->notification_type->name }}</td>
                                <td>{{ Carbon\Carbon::parse($scheduler_job->deadline)->toFormattedDateString() }}</td>
                                <td>{{ $scheduler_job->article_id }}</td>
                                <td>
                                    @if(!empty($scheduler_job->article_id))
                                        {{ $scheduler_job->article->title }}
                                    @endif
                                </td>
                                <td>
                                    {{ $scheduler_job->user_id }}
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