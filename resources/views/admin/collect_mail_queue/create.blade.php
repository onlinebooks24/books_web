@extends('layouts.admin_master')

@section('content')
    <div class="bottom10 pull-right">
        <a class="btn btn-info" href="{{ route('admin_scheduler_jobs.index') }}">All schedule jobs</a>
    </div>

    <div class="row">
        <div class="col-md-12 bottom30">
            <h2>Add new notification</h2>
            <form action="{{ route('admin_scheduler_jobs.store') }}" method="post">
                {{ csrf_field() }}
                <div class="form-group">
                    <label>Task Description</label>
                    <input class="form-control" type="text" placeholder="task_description" name="task_description">
                </div>

                <div class="form-group">
                    <label>Article id</label>
                    <input class="form-control" type="text" placeholder="aritcle id"  name="article_id">
                    <div class="red">if u write article id here then we will send them our own template sms. if u can send customize sms by writting on short_message field</div>
                </div>

                <div class="form-group">
                    <label>Short Message</label>
                    <textarea class="form-control" type="text" placeholder="if you want to send customize sms, write here" name="short_message"></textarea>
                </div>

                <div class="form-group">
                    <label>User </label>
                    <select name="user_id" class="form-control">
                        <option value="" selected>Select your option</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <div class="red">Select user or write phone no</div>
                </div>

                <div class="form-group">
                    <label>Phone no</label>
                    <input class="form-control" type="text" placeholder="if you do not want to select username, write phone no here"  name="phone_no">
                </div>

                <div class="form-group">
                    <label>Deadline</label>
                    <input class="form-control" type="datetime-local" placeholder="deadline"  name="deadline">
                    <div>Current Time: {{  $current_time }}</div>
                </div>

                <div class="form-group">
                    <label>Notification Interval</label>
                    <input class="form-control" type="text" placeholder="notification_interval" name="notification_interval" value="24">
                    <div class="red">It will count by hour</div>
                </div>

                <div class="form-group">
                    <label>Task Completed</label>
                    <select name="task_completed" class="form-control">
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                    <div class="red">If task is completed we will not send notification again by cronjob</div>
                </div>

                <div class="form-group">
                    <label>Notification Type</label>
                    <select name="notification_type_id" class="form-control">
                        @foreach($notification_types as $notification_type)
                        <option value="{{ $notification_type->id }}">{{ $notification_type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <input type="submit" value="submit">
            </form>
        </div>
    </div>
@endsection
