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
                    <label>Article Title</label>
                    <textarea name="html_description[]" class="summernote">{{ str_replace("%article_title%", $article->title , $videos_template->book_title_html) }}</textarea>
                    <input class="form-control" type="text" value="{{ $article->title }}" placeholder="article_title" name="article_title">
                </div>
                <div class="form-group">
                    <input class="form-control" type="text" value="{{ $article->id }}" placeholder="article_id" name="article_id">
                </div>
                <div class="form-group">
                    <select class="form-control" name="video_template_id">
                        @foreach($videos_templates as $videos_template)
                            <option value="{{ $videos_template->id }}">{{ $videos_template->template_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <input class="form-control" type="text" placeholder="file_location" name="file_location">
                </div>
                <div class="form-group">
                    <input class="form-control" type="text" placeholder="youtube_link" name="youtube_link">
                </div>
                @foreach($products as $key => $product)
                    <div class="row">
                        <div class="col-md-3">
                            <div class="alert alert-warning">
                                <a name="{{$key + 1}}" target="_blank" href="http://www.amazon.com/dp/ASIN/{{$product->isbn}}">
                                    <img src="{{ $product->image_url }}">
                                </a>
                            </div>
                            <label>ISBN:</label>
                            <div>
                                {{ $product->isbn }}
                            </div>
                            <label>Author Name:</label>
                            <div>
                                {{ $product->author_name }}
                            </div>
                            <label>Publication Date:</label>
                            <div>
                                {{ $product->publication_date }}
                            </div>

                            <div class="top10"><a href="https://www.amazon.com/product-reviews/{{ $product->isbn }}/ref=cm_cr_arp_d_viewopt_srt?sortBy=recent&pageNumber=1" target="_blank" class="btn-sm btn-success view-product" >View Review</a></div>
                        </div>
                        <div class="col-md-9">
                            <div class="alert alert-warning">
                                    <div class="form-group"> <!-- Name field -->
                                        <label class="control-label " for="name"><span style="color: red;" >{{ ++$key }}. </span> Title</label>
                                        <input class="form-control" name="title" type="text" value="{{ $product->product_title }}" disabled/>
                                    </div>

                                    <div class="form-group"> <!-- Message field -->
                                        <label class="control-label " for="message">Product Description</label>
                                        <textarea class="summernote product_description" data-product="{{$product->id}}" name="html_description[]">{{ str_replace("%main_content%", $product->product_description , $videos_template->book_description_html) }}</textarea>
                                    </div>

                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                @endforeach
                <input type="submit" value="submit">
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