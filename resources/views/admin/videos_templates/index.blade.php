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
                <h2>All Videos Templates</h2>
            </div>

            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('admin_videos_templates.create') }}">Add New Template</a>
            </div>
            <table id="mytable" class="table table-bordred table-striped">
                <thead>
                <tr>
                    <th>Template Name</th>
                    <th>Introduction</th>
                    <th>End</th>
                    <th>Book Picture</th>
                    <th>Book Description</th>
                    <th>Background Image</th>
                    <th>Audio Location</th>
                </tr>
                </thead>
                <tbody>
                @foreach($videos_templates as $video_template )
                    <tr>
                        <td>{{ $video_template->template_name }}</td>
                        <td>{{ $video_template->introduction }}</td>
                        <td>{{ $video_template->end }}</td>
                        <td>{{ $video_template->book_picture }}</td>
                        <td>{{ $video_template->book_description }}</td>
                        <td>{{ $video_template->background_image }}</td>
                        <td>{{ $video_template->audio_location }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {!! $videos_templates->appends(\Input::except('page'))->render() !!}
        </div>
    </div>

@endsection

@section('run_custom_js_file')
@endsection

@section('run_custom_jquery')

@endsection