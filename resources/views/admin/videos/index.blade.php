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
                <h2>All Videos</h2>
            </div>
            <table id="mytable" class="table table-bordred table-striped">
                <thead>
                <tr>
                    <th>Article ID</th>
                    <th>Video Template ID</th>
                    <th>File Location</th>
                    <th>Youtube Link</th>
                </tr>
                </thead>
                <tbody>
                @foreach($videos as $video )
                    <tr>
                        <td>{{ $video->id }}</td>
                        <td>{{ $video->video_template->template_name }}</td>
                        <td>{{ $video->video_name }}</td>
                        <td>{{ $video->youtube_link }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {!! $videos->appends(\Input::except('page'))->render() !!}
        </div>
    </div>

@endsection

@section('run_custom_js_file')
@endsection

@section('run_custom_jquery')

@endsection