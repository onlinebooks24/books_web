@extends('layouts.admin_master')

@section('run_custom_css_file')
    <link rel="stylesheet" type="text/css" href="{{ asset('summernote/summernote.css')}}">
@endsection

@section('content')
    <div class="bottom10 pull-right">
        <a class="btn btn-info" href="{{ route('admin_videos.index') }}">All Videos</a>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h2>Add new Video</h2>
            <form action="{{ route('admin_videos.youtube_upload', ['video_id' => $video->id]) }}" method="post">
                {{ csrf_field() }}

                <div class="form-group">
                    <label>Video Title</label>
                    <input type="text" name="video_title" value="{{ $video->article->title }}" class="form-control">
                </div>

                <div class="form-group">
                    <textarea class="form-control" placeholder="video_description" name="video_description">To get these book check here -
{{ route('articles.show' , [ 'slug' => $video->article->slug ])}}
                    </textarea>
                </div>

                <div class="form-group">
                    <label>Video Tags</label>
                    <input type="text" name="video_tags" placeholder="Write tag with comma" class="form-control">
                </div>

                <input type="submit" class="btn btn-success" value="submit">
            </form>
        </div>
    </div>
@endsection

@section('run_custom_jquery')

    <script>

        $(document).ready(function() {

            $('.summernote').summernote({
                height : '300px' ,
                placeholder : 'Enter Text Here...' ,
                popover: {
                    image: [
                        ['custom', ['imageAttributes']],
                        ['imagesize', ['imageSize100', 'imageSize50', 'imageSize25']],
                        ['float', ['floatLeft', 'floatRight', 'floatNone']],
                        ['remove', ['removeMedia']]
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
                }
            });


            $('#clear').on('click' , function(){
                $('.summernote').summernote('code', null);
            });

            $('.delete-product').click(function(){
                var product_id = $(this).data('product-id');
                $('.product-' + product_id).remove();
            })
        });

    </script>

@endsection

@section('run_custom_js_file')
    <script type="text/javascript" src="{{ asset('summernote/summernote.js')}}" ></script>
    <script  type="text/javascript"  src="{{ asset('summernote/summernote-image-attributes.js') }}" ></script>
@endsection