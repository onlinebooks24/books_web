@extends('layouts.admin_master')

@section('content')
    <div class="bottom10 pull-right">
        <a class="btn btn-info" href="{{ route('admin_collect_mail_queues.index') }}">All Mail Queues</a>
    </div>

    <div class="row">
        <div class="col-md-6 bottom30">
            <h2>Add new notification</h2>
            <form action="{{ route('admin_collect_mail_queues.store') }}" method="post">
                {{ csrf_field() }}
                <div class="form-group">
                    <label>topic</label>
                    <input class="form-control" type="text" placeholder="topic" name="topic">
                </div>

                <div class="form-group">
                    <label>category_id</label>
                    <input class="form-control" type="text" placeholder="category_id" name="category_id">
                </div>

                <div class="form-group">
                    <label>article_id</label>
                    <input class="form-control" type="text" placeholder="article_id" name="article_id">
                </div>

                <div class="form-group">
                    <label>custom_mail_template</label>
                    <textarea class="form-control" type="text" placeholder="custom mail template" name="custom_mail_template"></textarea>
                </div>

                <div class="form-group">
                    <label>run_count</label>
                    <input class="form-control" type="text" value="0" placeholder="run_count" name="run_count">
                </div>

                <div class="form-group">
                    <label>run_cron_job</label>
                    <input class="form-control" type="text" value="1" placeholder="run_cron_job" name="run_cron_job">
                </div>

                <div class="form-group">
                    <label>when_cron_job_have_to_run</label>
                    <input class="form-control" type="datetime-local" placeholder="when_cron_job_have_to_run" name="when_cron_job_have_to_run">
                </div>

                <div class="form-group">
                    <label>limit_cron_job_attempt</label>
                    <input class="form-control" type="text" placeholder="limit cronjob attempt per day" value="100" name="limit_cron_job_attempt">
                </div>

                <input type="submit" value="submit">
            </form>
        </div>
    </div>
@endsection
