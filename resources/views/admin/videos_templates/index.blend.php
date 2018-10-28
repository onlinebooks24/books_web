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
                <h2>All Site Costs</h2>
            </div>

            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('admin_site_costs.create') }}">Add New Cost</a>
            </div>
            <table id="mytable" class="table table-bordred table-striped">
                <thead>
                <tr>
                    <th>Description</th>
                    <th>Site Cost Type</th>
                    
                </tr>
                </thead>
                <tbody>
                @foreach($videos_templates as $video_template )
                    <tr>
                        <td>{{ $video_template->id }}</td>
                        <td>{{ $video_template->template_name }}</td>
                        
                    </tr>
                @endforeach
                </tbody>
            </table>
            {!! $video_template->appends(\Input::except('page'))->render() !!}
        </div>
    </div>

@endsection

@section('run_custom_js_file')
@endsection

@section('run_custom_jquery')

@endsection