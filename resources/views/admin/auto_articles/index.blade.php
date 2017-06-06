@extends('layouts.admin_master')

@section('run_custom_css_file')
@endsection

@section('content')
    <div class="bottom10 pull-right">
        <a class="btn btn-info" href="{{ route('admin_articles.create') }}">Add New Articles</a>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12">
            @if(Session::has('success'))
                <div class="alert alert-warning">
                    {{Session::get('success')}}
                </div>
            @endif
            @if(count($errors) > 0)
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        {{$error}}
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="col-md-12">
            <form action="">
                <input type="text" name="keyword">
                <input type="submit" value="submit">
            </form>
        </div>
    </div>

@endsection

@section('run_custom_js_file')
@endsection

@section('run_custom_jquery')

@endsection