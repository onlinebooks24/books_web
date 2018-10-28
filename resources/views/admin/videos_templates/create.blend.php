@extends('layouts.admin_master')

@section('content')
    <div class="bottom10 pull-right">
        <a class="btn btn-info" href="{{ route('admin_site_costs.index') }}">All site costs</a>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h2>Add new cost</h2>
            <form action="{{ route('admin_site_costs.store') }}" method="post">
                {{ csrf_field() }}
                <div class="form-group">
                    <input class="form-control" type="text" placeholder="description" name="description">
                </div>
                <div class="form-group">
                    <select name="site_cost_type_id" class="form-control" >
                        @foreach($site_cost_types as $site_cost_type)
                            <option value="{{ $site_cost_type->id }}">{{ $site_cost_type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <input class="form-control" type="text" placeholder="amount" name="amount">
                </div>
                <div class="form-group">
                    <input class="form-control" type="date" placeholder="when_paid" name="when_paid">
                </div>
                <div class="form-group">
                    <input class="form-control" type="text" placeholder="article_id" name="article_id">
                </div>
                <div class="form-group">
                    <input class="form-control" type="text" placeholder="user_id" value="1"  name="user_id">
                </div>
                <input type="submit" value="submit">
            </form>
        </div>
    </div>
@endsection
