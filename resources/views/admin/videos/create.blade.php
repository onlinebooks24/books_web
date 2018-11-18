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
            <form action="{{ route('admin_videos.store') }}" method="post">
                {{ csrf_field() }}
                <div class="form-group">
                    <label>Article Title (shortcode: %article_title%)</label>
                    <textarea name="html_description[]" class="summernote">{{ str_replace("%article_title%", $article->title , $videos_template->book_title_html) }}</textarea>
                </div>

                <div class="form-group">
                    <label>Article Title Duration</label>
                    <input type="text" name="duration[]" value="4" class="form-control">
                </div>
                <input class="form-control" type="hidden" value="{{ $article->id }}" placeholder="article_id" name="article_id">
                <div class="form-group">
                    <select class="form-control" name="video_template_id">
                        @foreach($videos_templates as $videos_template)
                            <option value="{{ $videos_template->id }}">{{ $videos_template->template_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <input class="form-control" type="text" placeholder="video_name" name="video_name">
                </div>
                <div class="form-group">
                    <input class="form-control" type="text" placeholder="youtube_link" name="youtube_link">
                </div>
                @foreach($products as $key => $product)
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group">
                                <label>Product Image and Title (shortcode: %product_title% , %product_image_url% )</label>
                                <textarea name="html_description[]" class="summernote">{{ str_replace(["%product_title%", "%product_image_url%"], [$product->product_title, $product->image_url] , $videos_template->book_image_html) }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Product Image Duration</label>
                                <input type="text" name="duration[]" value="5" class="form-control">
                            </div>
                            <div class="alert alert-warning">
                                <div class="form-group"> <!-- Message field -->
                                    <label class="control-label " for="message">{{ $key + 1 }}. Product Description (shortcode: %product_description%)</label>
                                    <textarea class="summernote product_description" data-product="{{$product->id}}" name="html_description[]">{{ str_replace(['%product_description%'], [$product->product_description] , $videos_template->book_description_html) }}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Product Duration</label>
                                <input type="text" name="duration[]" value="5" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                @endforeach

                <div class="alert alert-warning">
                    <div class="form-group"> <!-- Message field -->
                        <label class="control-label " for="message">{{ $key + 1 }}. Article Conclusion</label>
                        <textarea class="summernote" name="html_description[]">{{ $videos_template->book_conclusion_html }}</textarea>
                    </div>
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
        });

    </script>

@endsection

@section('run_custom_js_file')
    <script type="text/javascript" src="{{ asset('summernote/summernote.js')}}" ></script>
    <script  type="text/javascript"  src="{{ asset('summernote/summernote-image-attributes.js') }}" ></script>
@endsection