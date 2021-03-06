@extends('layouts.admin_master')

@section('run_custom_css_file')
    <link rel="stylesheet" type="text/css" href="{{ asset('summernote/summernote.css')}}">
@endsection

@section('content')
    <div class="row">
        <div class="alert alert-info">
            <strong>Update Articles</strong>
            <div class="pull-right top-5">
                Deadline: <span class="btn btn-warning">{{ empty($article->article_deadline)? 'Not Set' : Carbon\Carbon::parse($article->article_deadline)->format('m-d-Y') }}</span>
                <span>
                    <button type="button" class="btn-xm btn-primary" data-toggle="modal" data-target="#set_expire_date">
                        Update Deadline
                    </button>
                </span>
                Editor: <span class="btn btn-success editor-spend-time"></span>
                Admin: <span class="btn btn-info admin-spend-time"></span>
                Subadmin: <span class="btn btn-danger sub-admin-spend-time"></span>
                <div data-article-id="{{ $article->id }}" data-user-type="{{ Auth::user()->roleType->name }}" class="user-spend-time btn-sm btn-danger">00:00:00</div>
                <a target="_blank" class="btn btn-info" href="{{ route('articles.show' , [ 'slug' => $article->slug ])}}">View</a>
                @if(Auth::user()->roleType->name != 'editor')
                    <a class="btn btn-success" href="{{ route('admin_articles.publish_or_unpublished', $article->id)}}">
                        @if($article->status)
                            Unpublish Now
                        @else
                            Please Publish
                        @endif
                    </a>
                @endif

                @if(Auth::user()->roleType->name == 'editor')
                        @if($article->status || $article->waiting_for_approval)
                            <span class="red">
                            Submitted for Review
                            </span>
                        @else
                            <a class="btn btn-success" href="{{ route('admin_articles.submit_for_review', $article->id)}}">
                            Submit for Review
                            </a>
                        @endif
                @endif
            </div>
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
    <div class="row">
        <form action="{{ route('admin_articles.update' , $article->id)}}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="alert alert-success">
                <input name="_method" type="hidden" value="PUT">
                <div class="form-group"> <!-- Name field -->
                    <label class="control-label " for="name">Title</label>
                    <input class="form-control" name="title" type="text" value="{{ $article->title }}" />
                </div>

                <div class="form-group"> <!-- Name field -->
                    <label class="control-label " for="name">URL Slug</label>
                    <input class="form-control" name="slug" type="text" value="{{ $article->slug }}" />
                </div>

                @if(Auth::user()->roleType->name != 'editor')
                    <div class="form-group"> <!-- Name field -->
                        <label class="control-label " for="name">Expired Slug</label>
                        <input class="form-control" name="expired_slug" type="text" placeholder="write expired slug" value="{{ $article->expired_slug }}" />
                    </div>
                @endif

                <div class="form-group"> <!-- Name field -->
                    <label class="control-label " for="name">Keyword</label>
                    <input class="form-control" name="keyword" type="text" value="{{ $article->keyword }}" required />
                </div>

                <div class="form-group"> <!-- Name field -->
                    <label class="control-label " for="name">Meta Title</label>
                    <input class="form-control" name="meta_title" placeholder="write meta title" type="text" value="{{ $article->meta_title }}" />
                </div>

                <div class="form-group"> <!-- Name field -->
                    <label class="control-label " for="name">Meta Description</label>
                    <textarea class="form-control" name="meta_description" type="text" required>{{ $article->meta_description }}</textarea>
                </div>

                @if(Auth::user()->roleType->name != 'editor')
                    <div class="form-group">
                        <div>Select Username here:</div>
                        <select class="form-control" name="user_id" data-value="1">
                            <option value="">Select Username</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id}}" {{ $article->user_id == $user->id ? 'selected'  : '' }} > {{ $user->name }} </option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <input type="hidden" name="user_id" value="{{ $article->user_id }}">
                @endif

                <input type="hidden" value="{{ $article->category_id }}" name="category_id" class="category_id_value">

                <div class="top10 bottom5">Already you have selected <strong>{{ $article->category->name }}</strong>. If you want to change, please select again</div>

                <div class="form-group category-box">
                    <div>Select category here:</div>
                    <select class="form-control category_select" data-value="1">
                        <option value="">Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id}}" data-browse-node-id="{{ $category->browse_node_id }}" {{ $article->category_id == $category->id ? 'selected'  : '' }} > {{ $category->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-3">
                    <div class="form-group"> <!-- Name field -->
                        <label class="control-label " for="name">Thumbnail Alt Tag</label>
                        <input class="form-control" placeholder="write thumbnail alt tag" name="thumbnail_alt_tag"  value="{{ $article->thumbnail_alt_tag }}" type="text" />
                    </div>
                <div class="alert alert-success">
                    <h5>Upload Thumbnail Image</h5>
                    <input type="file" name="image">
                    <hr>
                    <h5>Previous Image</h5>
                    @if($image_exist)
                        <img class="img-responsive" src="{{$image_exist->folder_path.'/'.$image_exist->name}}" alt="{{ $article->thumbnail_alt_tag }}">
                    @endif
                </div>

                <div class="alert alert-warning">Total {{ count($uploads) }} images. You can view bottom of this page.</div>
            </div>
            <div class="col-sm-9">
                <div class="alert alert-success">
                    <div class="form-group"> <!-- Message field -->
                        <label class="control-label " for="message">Article Description</label>
                        <span class="article_description_count left30 btn-sm btn-danger">Edit something to get word count</span>
                        <span>(If you write info article please write around 900-1000 words)</span>

                        <textarea class="article_description summernote" name="body">{!! $article->body !!}</textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-md" style="width: 100%;"><span class="glyphicon glyphicon-ok-sign"></span> Update</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="text-center"><h2>Published Products</h2></div>
    @foreach($published_products as $key => $product)
        @include('admin/articles/product_details')
        <div class="clearfix"></div>
    @endforeach

    @if(count($deleted_products) > 0)
        <div class="text-center alert-danger"><h1>Deleted Products</h1></div>
        @foreach($deleted_products as $key => $product)
            @include('admin/articles/product_details')
            <div class="clearfix"></div>
        @endforeach
    @endif

    <div class="alert alert-success top30">
        <form action="{{ route('admin_articles.update' , $article->id)}}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input name="_method" type="hidden" value="PUT">
            <div class="form-group"> <!-- Message field -->
                <label class="control-label " for="message">Article Conclusion</label>
                <textarea class="summernote" name="conclusion">{!! $article->conclusion !!}</textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success btn-md" ><span class="glyphicon glyphicon-ok-sign"></span> Save</button>
            </div>
        </form>
    </div>

    <div class="alert alert-info">
        <form action="{{ route('admin_articles.product_add')}}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input name="_method" type="hidden" value="PUT">
            <input type="hidden" name="article_id" value="{{$article->id}}">
            <div>
                <label class="control-label " for="message">Add Product</label>
            </div>
            <div class="row">
                <div class="form-group col-md-3">
                    <input type="text" name="isbn" placeholder="ISBN NUMBER" class="form-control">
                </div>
            </div>

            <div class="clearfix"></div>
            <div class="form-group">
                <button type="submit" class="btn btn-warning btn-md" ><span class="glyphicon glyphicon-ok-sign"></span> Add Product</button>
            </div>
        </form>
    </div>

    <div class="row">
        @foreach($uploads as $key => $upload)
            <div class="col-md-6">
                <div>{{ $upload->name }}</div>
                <div>
                    <a target="_blank" href="{{ $upload->folder_path. $upload->name }}">
                        <img style="width:300px" src="{{ $upload->folder_path. $upload->name }}">
                    </a>
                </div>
                <div class="top10">
                    <td><p data-placement="top" data-toggle="tooltip" title="Delete"><button class="btn btn-danger" data-title="Delete" data-toggle="modal" data-target="#delete{{++$key}}" >Delete</button></p></td>
                </div>
            </div>

            <div class="modal fade" id="delete{{$key}}" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('admin_uploads.destroy' , $upload->id)}}" method="POST">
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
    </div>


    {{--modal to set expire date--}}
    <div class="modal fade" id="set_expire_date" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin_articles.set_article_deadline', $article->id ) }}" method="POST">
                    {{ csrf_field() }}
                    <input name="_method" type="hidden" value="DELETE">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
                        <h4 class="modal-title custom_align" id="Heading">Please set deadline when you will submit?</h4>
                        <div class="form-group top5">
                            <input type="date" class="form-control" name="article_deadline">
                        </div>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer ">
                        <button type="submit" class="btn btn-success" ><span class="glyphicon glyphicon-ok-sign"></span> Update</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection

@section('run_custom_jquery')
    @if((Auth::user()->roleType->name == 'editor') && ($article->article_deadline < Carbon\Carbon::now()))
        <script>
            $('#set_expire_date').modal();
        </script>
    @endif

    <script>
        $(".product_save").submit(function(event){
            event.preventDefault();

            $.ajax({
                url:'{!!URL::route('admin_articles.product_save')!!}',
                type:'POST',
                data:$(this).serialize(),
                success:function(result){
                    alert('success');
                    $("#response").text(result);
                    $('.please-save').addClass('hidden');
                }
            });
        });

        $('.product_delete').submit(function(event){
           event.preventDefault();
            $.ajax({
                url:'{!!URL::route('admin_articles.product_destroy')!!}',
                type:'POST',
                data:$(this).serialize()
            });
            $(this).addClass('hidden');
            var isbn = $(this).data('isbn');
            var product_box = '.product-box-' + isbn;
            $(product_box).addClass('hidden');
        });

        $('.view-product').click(function(){
            $(this).removeClass('btn-success').addClass('btn-info');
        })

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
        });

        $(".product_description").on("summernote.change", function (e) {   // callback as jquery custom event
            var product_id = $(this).data('product');

            $('.save-request-' + product_id).removeClass('hidden');
        });

        $(".article_description").on("summernote.change", function (e) {   // callback as jquery custom event
            var article_html = $(this).summernote('code');
            var article_plain_text = $("<p>" + article_html+ "</p>").text();
            var total_words = $.trim(article_plain_text).split(" ");
            $('.article_description_count').text(total_words.length + ' words');
        });

        $('.view-review-here').click(function(e){
            e.preventDefault();
            var review_isbn = $(this).data('review-isbn');
            $.ajax("/admin/admin_articles/product_review/" + review_isbn, {
                success: function(data) {
                    var product_box = '.product-box-' + review_isbn + ' ';

                    $( product_box + '.product-description').addClass('hidden');
                    $( product_box + '.product-review-box').removeClass('hidden');

                    $( product_box + '.view-description-here').removeClass('hidden');
                    $( product_box + '.view-review-here').addClass('hidden');

                    $( product_box + '.total-review-count').text(data.total_review_count);
                    $( product_box + '.total-rating').text(data.total_rating);

                    var total_rating_details = '';
                    $.each(data.total_rating_details, function (index, value) {
                        if(index == 0){
                            total_rating_details += '<div> 5 star: ' + value + '</div>';
                        } else if (index == 1) {
                            total_rating_details += '<div> 4 star: ' + value + '</div>';
                        } else if(index == 2){
                            total_rating_details += '<div> 3 star: ' + value + '</div>';
                        } else if(index == 3){
                            total_rating_details += '<div> 2 star: ' + value + '</div>';
                        } else if (index == 4){
                            total_rating_details += '<div> 1 star: ' + value + '</div>';
                        }
                    });

                    $( product_box + '.total-rating-details').html(total_rating_details);

                    var total_review_details = '';
                    $.each(data.total_review_details, function (index, value) {
                        index = index + 1;
                        if(index % 4 == 1){
                            total_review_details += '<div class="total-review-item"><div class="bottom5"><span class="btn-sm btn-danger">' + value + '</span></div>';
                        } else if (index % 4 == 2) {
                            total_review_details += '<div><strong>' + value + '</strong></div>';
                        } else if(index % 4 == 3){
                            total_review_details += '<div class="red">' + value + '</div>';
                        } else if(index % 4 == 0){
                            total_review_details += value;
                            total_review_details += '</div>' + '</br>';
                        }
                    });

                    $( product_box + '.total-review-details').html(total_review_details);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert("Error: " + errorThrown + ' Product ISBN: ' + review_isbn);
                }

            });
        })

        $('.view-description-here').click(function(e){
            e.preventDefault();
            var review_isbn = $(this).data('review-isbn');
            var product_box = '.product-box-' + review_isbn + ' ';

            $( product_box + '.product-description').removeClass('hidden');
            $( product_box + '.product-review-box').addClass('hidden');

            $( product_box + '.view-description-here').addClass('hidden');
            $( product_box + '.view-review-here').removeClass('hidden');

        });

        (function worker() {
            var user_type = $('.user-spend-time').data('user-type');
            user_type = user_type.replace(/_/g, '-');
            var article_id = $('.user-spend-time').data('article-id');
            $.ajax({
                url: '/admin/admin_articles/edit_time_tracker/' + article_id,
                success: function(data) {
                    $('.editor-spend-time').text(data.editor_spend_time);
                    $('.admin-spend-time').text(data.admin_spend_time);
                    $('.sub-admin-spend-time').text(data.sub_admin_spend_time);
                    var current_user_spend_time = $('.'+ user_type + '-spend-time').text();
                    $('.user-spend-time').text(current_user_spend_time);
                },
                complete: function() {
                    setTimeout(worker, 5000);
                }
            });
        })();

    </script>

@endsection

@section('run_custom_js_file')
    <script type="text/javascript" src="{{ asset('summernote/summernote.js')}}" ></script>
    <script  type="text/javascript"  src="{{ asset('summernote/summernote-image-attributes.js') }}" ></script>
@endsection