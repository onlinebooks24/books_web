@extends('layouts.admin_master')

@section('run_custom_css_file')
    <link rel="stylesheet" type="text/css" href="{{ asset('summernote/summernote.css')}}">
@endsection

@section('content')
<div class="row">
    <div class="alert alert-success">
        <form method="post" action="{{ route('admin_articles.store') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-group"> <!-- Name field -->
                <label class="control-label " for="name">Title</label>
                <input class="form-control" name="title" type="text" required />
            </div>

            <div class="form-group"> <!-- Name field -->
                <label class="control-label " for="name">URL Slug</label>
                <input class="form-control" name="slug" type="text" required />
            </div>
            <div class="form-group"> <!-- Name field -->
                <label class="control-label " for="name">Expired Slug</label>
                <input class="form-control" name="expired_slug" type="text" />
            </div>

            <div class="form-group"> <!-- Name field -->
                <label class="control-label " for="name">Keyword</label>
                <input class="form-control" name="keyword" type="text" required />
            </div>

            <div class="form-group"> <!-- Name field -->
                <label class="control-label " for="name">Meta Description</label>
                <textarea class="form-control" name="meta_description" type="text" required></textarea>
            </div>

            <input type="hidden" value="5" name="category_id" class="category_id_value">

            <div class="form-group category-box">
                <div>Select category here:</div>
                <select class="form-control category_select" data-value="1">
                    <option value="">Select Category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id}}" data-browse-node-id="{{ $category->browse_node_id }}" > {{ $category->name }} </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group"> <!-- Name field -->
                <label class="control-label " for="name">Thumbnail Alt Tag</label>
                <input class="form-control" name="thumbnail_alt_tag" type="text" placeholder="Image Alt Tag" required />
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

        $('.category-box').on('change', ".category_select" , function() {
            var parent_id = $(this).find(':selected').data('browse-node-id');
            var current_category_select = $(this).data('value');
            var category_select_count = $('.category_select').length;

            $('.category_id_value').val(this.value);

            if (current_category_select != category_select_count){
                $('.category_select').slice(current_category_select-category_select_count).remove();
                category_select_count = $('.category_select').length;
            }

            $.ajax("/category-json/" + parent_id, {
                success: function(data) {
                    category_select_count = category_select_count + 1;
                    if (typeof(data.length)  === "undefined"){

                    } else {
                        var option_value =
                                '<select class="form-control category_select" name="category_id" data-value="' + category_select_count + '">';
                        option_value += '<option value="" disabled selected>Select your option</option>';
                        $.each(data, function(i, item) {
                            option_value += '<option value="'+ item.id +'" data-browse-node-id="' + item.browse_node_id +'">' + item.name + '</option>';
                        });

                        option_value += '</select>' +
                                $('.category-box').append(option_value);
                    }
                }
            });
        })

    </script>
@endsection
