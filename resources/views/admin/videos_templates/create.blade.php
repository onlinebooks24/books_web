@extends('layouts.admin_master')

@section('run_custom_css_file')
    <link rel="stylesheet" type="text/css" href="{{ asset('summernote/summernote.css')}}">
@endsection

@section('content')
    <div class="bottom10 pull-right">
        <a class="btn btn-info" href="{{ route('admin_videos_templates.index') }}">All videos templates</a>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h2>Add new template</h2>
            <form action="{{ route('admin_videos_templates.store') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group">
                    <input class="form-control" type="text" value="Template 1" placeholder="template_name" name="template_name">
                </div>
                <div class="form-group">
                    <label>book title html (shortcode: %article_title%)</label>
                    <textarea class="form-control summernote" placeholder="book title html" name="book_title_html"></textarea>
                </div>
                <div class="form-group">
                    <label>book_description_html (shortcode: %product_title% , %product_image_url% , %product_description%)</label>
                    <textarea class="form-control summernote" placeholder="book_description_html" name="book_description_html"></textarea>
                </div>
                <div class="form-group">
                    <label>book_conclusion_html</label>
                    <textarea class="form-control summernote" placeholder="book_conclusion_html" name="book_conclusion_html"></textarea>
                </div>
                <div class="form-group">
                    <label>Background Image (.jpeg)</label>
                    <input class="form-control" type="file" placeholder="background_image"  name="background_image">
                </div>
                <div class="form-group">
                    <label>Audio Name (.mp3)</label>
                    <input class="form-control" type="file" placeholder="audio_name"  name="audio_name">
                </div>
                <input type="submit" value="submit" class="btn btn-success bottom30">
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
            $('.summernote').summernote({
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

