@extends('layouts.admin_master')

@section('content')
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

	<div class="row bottom10">
		<div class="alert alert-info">
			@if (app('request')->input('search'))
			    <p>Search result for - <strong>{{ app('request')->input('search') }}</strong>
					<a class="btn btn-danger btn-sm" href="{{ route('admin_articles.index') }}">clear</a>
				</p>
			@else
				<strong>View All Articles</strong>
			@endif
		</div>
		<div class="col-md-12" id="article_search_block">
			<div class="row">
				<div class="col-md-4 article_search_block">
					<form class="article_search" action="{{ route('admin_articles.index') }}" method="GET">
						<div class="form-group">
							<input type="text" name="search" class="form-control" placeholder="Search..." required>
							<button class="btn btn-info" type="submit">Search</button>
						</div>
					</form>
				</div>
				<div class="col-md-8 article_search_block">
					<a class="btn btn-info pull-right" href="{{ route('admin_articles.create') }}">Add New Articles</a>
				</div>
			</div>
		</div>
		<section>
			{{ $articles->links() }}
		</section>
	</div>
	<div class="row alert alert-success">
    <div class="col-md-12">
		<div class="table-responsive">
		  <table id="mytable" class="table table-bordred table-striped">
			   <thead>
				   <th>id</th>
				   <th>Title</th>
				   <th>User</th>
				   <th>Created at</th>
				   <th>Count</th>
				   @if(Auth::user()->roleType->name == 'admin' || Auth::user()->roleType->name == 'manager')
				   <th>Orders</th>
				   @endif
				   @if(Auth::user()->roleType->name == 'admin' )
				   <th>Fee</th>
				   <th>Site Cost</th>
				   @endif
				   <th>Edit</th>
				   <th>Delete</th>
				   <th>Publish</th>
				   <th>Video</th>
				   <th>View</th>
			   </thead>
			  <tbody>
			    @if(count($articles))
					@foreach($articles as $key => $article)
					<tr>
						<td>{{ $article->id }}</td>
						<td>
						{{ $article->title }}
						@if(count($article->products) == 0)
							<span class="btn-sm btn-danger">Info</span>
						@endif
						</td>
						<td>{{ $article->user->name }}</td>
						<td>{{ Carbon\Carbon::parse($article->created_at)->toFormattedDateString() }}</td>
						<td>{{ $article->count }}</td>
						@if(Auth::user()->roleType->name == 'admin' || Auth::user()->roleType->name == 'manager' )
						<td>{{ count($article->product_orders) }}</td>
						@endif
						@if(Auth::user()->roleType->name == 'admin' )
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
						@endif
					
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
						<td><a target="_blank" href="{{ route('admin_videos.create' , [ 'article_id' => $article->id ])}}">Make Video</a></td>
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
				@else
					<tr>
						<td colspan="13" class="text-center">Data Not Found</td>
					</tr>
				@endif
			  </tbody>
		  </table>
		</div>
	</div>
	<section>
      {{ $articles->links() }}
    </section>
</div>
@endsection
