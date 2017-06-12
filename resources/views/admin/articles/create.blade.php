@extends('layouts.admin_master')

@section('run_custom_css_file')
    <link rel="stylesheet" type="text/css" href="{{ asset('summernote/summernote.css')}}">
@endsection

@section('content')
<div class="row">
    <div class="alert alert-success">
        <form method="post" action="{{ route('admin_articles.store') }}">
            {{ csrf_field() }}
            <div class="form-group"> <!-- Name field -->
                <label class="control-label " for="name">Title</label>
                <input class="form-control" name="title" type="text" required />
            </div>

            <div class="form-group"> <!-- Name field -->
                <label class="control-label " for="name">Keyword</label>
                <input class="form-control" name="keyword" type="text" required />
            </div>

            <div class="form-group"> <!-- Name field -->
                <label class="control-label " for="name">Meta Description</label>
                <textarea class="form-control" name="meta_description" type="text" required></textarea>
            </div>

            <div class="form-group">
                <label class="control-label">Select Category</label>
                <select class="form-control" name="category_id" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <div class="alert alert-success">
                    <h5>Upload Thumbnail Image</h5>
                    <input type="file" name="image">
                </div>
            </div>
            <div class="form-group"> <!-- Message field -->
                <label class="control-label " for="message">Message</label>
                <textarea class="form-control" id="summernote" name="body" required></textarea>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary ">Submit</button>
                <button type="button" class="btn btn-danger pull-right" id="clear">Clear</button>
            </div>

        </form>
    </div>
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
