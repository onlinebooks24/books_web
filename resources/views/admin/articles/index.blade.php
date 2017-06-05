@extends('layouts.admin_master')

@section('run_custom_css_file')
<link rel="stylesheet" type="text/css" href="{{ asset('summernote/summernote.css')}}">
@endsection

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
       
       <th>Title</th>
       <th>Edit</th>
       <th>Delete</th>
       </thead>
    <tbody>
    @php 
    	$i = 0;
    @endphp
    @foreach($articles as $article)
    <tr>
    <td>{{ $article->title }}</td>
		<td><a href="{{ route('admin_articles.edit', $article->id) }}"><button class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-pencil"></span></button></a></td>
    <td><p data-placement="top" data-toggle="tooltip" title="Delete"><button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete<?php echo  $i ; ?>" ><span class="glyphicon glyphicon-trash"></span></button></p></td>
    </tr>
    
	<div class="modal fade" id="delete<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
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

		@php 
			$i++
		@endphp 
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

@section('run_custom_js_file')
<script type="text/javascript" src="{{ asset('summernote/summernote.js')}}"></script>
<script  type="text/javascript"  src="{{ asset('summernote/summernote-image-attributes.js') }}"></script>
@endsection

@section('run_custom_jquery')
<script type="text/javascript">
	$(document).ready(function() {

        $('#summernote').summernote({
			height : '300px' ,
            placeholder : 'Enter Text Here...' ,
            popover: {
                image: [
                    ['custom', ['imageAttributes']],
                    ['imagesize', ['imageSize100', 'imageSize50', 'imageSize25']],
                    ['float', ['floatLeft', 'floatRight', 'floatNone']],
                    ['remove', ['removeMedia']],
                ],
            },
            lang: 'en-US',
            imageAttributes:{
                imageDialogLayout:'default', // default|horizontal
                icon:'<i class="note-icon-pencil"/>',
                removeEmpty:false // true = remove attributes | false = leave empty if present
            },
            displayFields:{
                imageBasic:true,  // show/hide Title, Source, Alt fields
                imageExtra:false, // show/hide Alt, Class, Style, Role fields
                linkBasic:true,   // show/hide URL and Target fields for link
                linkExtra:false   // show/hide Class, Rel, Role fields for link
            },
        });
		 

	  $('#clear').on('click' , function(){
	  	$('#summernote').summernote('code', null);
	  });


	});
</script>
@endsection