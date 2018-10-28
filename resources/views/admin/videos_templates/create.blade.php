@extends('layouts.admin_master')

@section('content')
    <div class="bottom10 pull-right">
        <a class="btn btn-info" href="{{ route('admin_site_costs.index') }}">All site costs</a>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h2>Add new cost</h2>
            <form action="{{ route('admin_videos_templates.store') }}" method="post">
                {{ csrf_field() }}
                <div class="form-group">
                    <input class="form-control" type="text" value="Template 1" placeholder="template_name" name="template_name">
                </div>
                <div class="form-group">
                    <input class="form-control" type="text" value="3" placeholder="introduction" name="introduction">
                </div>
                <div class="form-group">
                    <input class="form-control" type="text" value="3" placeholder="end" name="end">
                </div>
                <div class="form-group">
                    <input class="form-control" type="text" value="4" placeholder="book_picture" name="book_picture">
                </div>
                <div class="form-group">
                    <input class="form-control" type="text" value="6" placeholder="book_description"  name="book_description">
                </div>
                <div class="form-group">
                    <input class="form-control" type="text" placeholder="background_image"  name="background_image">
                </div>
                <div class="form-group">
                    <input class="form-control" type="text" placeholder="audio_location"  name="audio_location">
                </div>
                <input type="submit" value="submit">
            </form>
        </div>
    </div>
@endsection
