@extends('layouts.admin_master')

@section('content')
    <div class="bottom10 pull-right">
        <a class="btn btn-info" href="{{ route('admin_videos.index') }}">All Videos</a>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h2>Add new Video</h2>
            <form action="{{ route('admin_videos.store') }}" method="post">
                {{ csrf_field() }}
                <div class="form-group">
                    <input class="form-control" type="text" placeholder="article_id" name="article_id">
                </div>
                <div class="form-group">
                    <input class="form-control" type="text" placeholder="video_template_id" name="video_template_id">
                </div>
                <div class="form-group">
                    <input class="form-control" type="text" placeholder="file_location" name="file_location">
                </div>
                <div class="form-group">
                    <input class="form-control" type="text" placeholder="youtube_link" name="youtube_link">
                </div>
                <input type="submit" value="submit">
            </form>
        </div>
    </div>
@endsection
