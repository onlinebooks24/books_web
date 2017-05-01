@extends('layouts.admin_master')

@section('content')
 <div class="row">
 	<div class="alert alert-info">
 		<strong>View All Category</strong>
 	</div>
 </div>
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
 <div class="row alert alert-success">
    <div class="col-md-6">
    <div class="table-responsive">         
	  <table id="mytable" class="table table-bordred table-striped">    
       <thead>
       
       <th>Category Name</th>
       <th>Edit</th>
       <th>Delete</th>
       </thead>
    <tbody>
    @php 
    	$i = 0;
    @endphp
    @foreach($categories as $category)
    <tr>
    <td>{{ $category->name }}</td>
    <td><p data-placement="top" data-toggle="tooltip" title="Edit"><button class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" data-target="#edit<?php echo $i ; ?>" ><span class="glyphicon glyphicon-pencil"></span></button></p></td>
    <td><p data-placement="top" data-toggle="tooltip" title="Delete"><button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete<?php echo  $i ; ?>" ><span class="glyphicon glyphicon-trash"></span></button></p></td>
    </tr>
     
	<div class="modal fade" id="edit<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		<form action="{{ route('category.update' , $category->id)}}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input name="_method" type="hidden" value="PUT">
		   <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
		    <h4 class="modal-title custom_align" id="Heading">Edit Category Name</h4>
		  </div>
		    <div class="modal-body">
		      <div class="form-group">
		    	<input class="form-control " type="text" name="name" value="{{ $category->name }}" >
		    </div>
		  </div>
		   <div class="modal-footer ">
		    <button type="submit" class="btn btn-warning btn-lg" style="width: 100%;"><span class="glyphicon glyphicon-ok-sign"></span> Update</button>
		  </div>
		  </form>
		 </div>
		<!-- /.modal-content --> 
		</div>
	  <!-- /.modal-dialog --> 
	</div>
    
    
    
	<div class="modal fade" id="delete<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		<form action="{{ route('category.destroy' , $category->id)}}" method="POST">
        {{ csrf_field() }}
        <input name="_method" type="hidden" value="DELETE">
		   <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
		    <h4 class="modal-title custom_align" id="Heading">Delete this entry</h4>
		  </div>
		      <div class="modal-body">
		   
		   <div class="alert alert-danger"><span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to delete this Record?</div>
		   
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

@php 
	$i++
@endphp 
@endforeach



  </tbody>
        
</table>
             
  </div>
    
</div>

<div class="col-md-6">
	<h4><strong>Add New  Category</strong></h4>
	<form method="post" action="{{ route('category.store') }}">
	{{ csrf_field() }}
	<div class="form-group">
		<input type="text" name="name" class="form-control" placeholder="New Category" required>
		<button type="submit" class="btn btn-info" style="float: right;margin-top: 10px">Create</button>
	</div>
	</form>
</div>
</div>

@endsection