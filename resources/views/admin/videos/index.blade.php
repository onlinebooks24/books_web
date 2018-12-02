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
                    <th>Article Name</th>
                    <th>Video Name</th>
                    <th>Download Link</th>
                    <th>Youtube Link</th>
                    <th>Upload Youtube</th>
                </tr>
                </thead>
                <tbody>
                @foreach($videos as $video )
                    <tr>
                        <td>{{ $video->article->title }}</td>
                        <td>{{ $video->video_template->template_name }}</td>
                        <td><a href="{{ $video->video_link }}">Download</a></td>
                        <td>
                            @if(!empty($video->youtube_link))
                                <a href="https://www.youtube.com/watch?v={{ $video->youtube_link }}">Youtube</a>
                            @endif
                        </td>
                        <td><a href="{{ route('admin_videos.youtube_upload_show', $video->id) }}">Upload</a></td>
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