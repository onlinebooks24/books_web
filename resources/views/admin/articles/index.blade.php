@extends('layouts.admin_master')

@section('content')
	<div class="bottom10 pull-right">
		<a class="btn btn-info" href="{{ route('admin_articles.create') }}">Add New Articles</a>
	</div>
	<div class="clearfix"></div>
	<div class="row">
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

	<div class="row">
		<div class="alert alert-info">
			<strong>View All Articles</strong>
		</div>
	</div>
	<div class="row alert alert-success">
    <div class="col-md-12">
    <div class="table-responsive">         
	  <table id="mytable" class="table table-bordred table-striped">    
       <thead>
       <th>id</th>
       <th>Title</th>
       <th>User</th>
       <th>Count</th>
       <th>Orders</th>
       <th>Fee</th>
       <th>Site Cost</th>
	   <th>who is</th>
	   <th>Created at</th>
	   <th>Edit</th>
       <th>Delete</th>
       <th>Publish</th>
       <th>View</th>
       </thead>
    <tbody>

    @foreach($articles as $key => $article)
	<tr>
		<td>{{ $article->id }}</td>
		<td>{{ $article->title }}</td>
		<td>{{ $article->user->name }}</td>
		<td>{{ Carbon\Carbon::parse($article->created_at)->toFormattedDateString() }}</td>
		<td>{{ $article->count }}</td>
		<td>{{ count($article->product_orders) }}</td>
		<td>
			<div class="hidden">{{ $fee = 0 }}</div>
			@if(!empty($article->product_orders))
				@foreach($article->product_orders as $item)
					<div class="hidden">{{ $fee += $item->ad_fees }}</div>
				@endforeach
			@endif

			{{ $fee * config('constants.dollar_rate') }} BDT
		</td>

		<td>
			<div class="hidden">{{ $cost = 0 }}</div>
			@if(!empty($article->site_costs))
				@foreach($article->site_costs as $item)
					<div class="hidden">{{ $cost += $item->amount }}</div>
				@endforeach
			@endif

			{{ $cost }} BDT
		</td>
		<td>
			@if(!empty($article->site_costs))
				@foreach($article->site_costs as $item)
					{{ $item->description }}
				@endforeach
			@endif
		</td>
		<td><a href="{{ route('admin_articles.edit', $article->id) }}"><button class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-pencil"></span></button></a></td>
		<td><p data-placement="top" data-toggle="tooltip" title="Delete"><button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete{{++$key}}" ><span class="glyphicon glyphicon-trash"></span></button></p></td>
		<td>
			<a href="{{ route('admin_articles.publish_or_unpublished', $article->id)}}">
				@if($article->status)
					Unpublish now
				@else
					Publish it
				@endif
			</a>
		</td>
		<td><a target="_blank" href="{{ route('articles.show' , [ 'slug' => $article->slug ])}}">View</a></td>
	</tr>

	<div class="modal fade" id="delete{{$key}}" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		<form action="{{ route('admin_articles.destroy' , $article->id)}}" method="POST">
		{{ csrf_field() }}
		<input name="_method" type="hidden" value="DELETE">
		   <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
			<h4 class="modal-title custom_align" id="Heading">Delete this entry</h4>
		  </div>
			  <div class="modal-body">

		   <div class="alert alert-danger"><span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to delete this Record? </div>

		  </div>
			<div class="modal-footer ">
			<button type="submit" class="btn btn-success" ><span class="glyphicon glyphicon-ok-sign"></span> Yes</button>
			<button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> No</button>
		  </div>
		  </form>
		</div>
	<!-- /.modal-content -->
		</div>
	  <!-- /.modal-dialog -->
	</div>

	@endforeach



		  </tbody>
		        
		</table>
		             
		  </div>
		    
		</div>

	<section>
      <nav>
        <ul class="pager">
            @if($articles->currentPage() !== 1)
              <li class="previous"><a href="{{ $articles->previousPageUrl() }}"><span aria-hidden="true">&larr;</span>Newer</a></li>
            @endif
            @if($articles->currentPage() !== $articles->lastPage() && $articles->hasPages())
              <li class="next"><a href="{{ $articles->nextPageUrl() }}">Older <span aria-hidden="true">&rarr;</span></a></li>
            @endif
        </ul>
      </nav>
    </section>
	</div>
@endsection
