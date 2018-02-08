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
                    <th>id</th>
                </tr>
                </thead>
                <tbody>
                    @if(count($collect_mail_queues) > 0)
                        @foreach($collect_mail_queue as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
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