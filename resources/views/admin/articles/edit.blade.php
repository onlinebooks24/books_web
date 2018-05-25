@extends('layouts.admin_master')

@section('run_custom_css_file')
    <link rel="stylesheet" type="text/css" href="{{ asset('summernote/summernote.css')}}">
@endsection

@section('content')
    <div class="row">
        <div class="alert alert-info">
            <strong>Update Articles</strong>
            <div class="pull-right top-5">
                <a target="_blank" class="btn btn-info" href="{{ route('articles.show' , [ 'slug' => $article->slug ])}}">View</a>
                @if(Auth::user()->roleType->name == 'admin')
                <a class="btn btn-success" href="{{ route('admin_articles.publish_or_unpublished', $article->id)}}">
                    @if($article->status)
                        Unpublish Now
                    @else
                        Please Publish
                    @endif
                </a>
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

                <div class="form-group"> <!-- Name field -->
                    <label class="control-label " for="name">Keyword</label>
                    <input class="form-control" name="keyword" type="text" value="{{ $article->keyword }}" required />
                </div>

                <div class="form-group"> <!-- Name field -->
                    <label class="control-label " for="name">Meta Description</label>
                    <textarea class="form-control" name="meta_description" type="text" required>{{ $article->meta_description }}</textarea>
                </div>

                @if(Auth::user()->roleType->name == 'admin')
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
                <div class="alert alert-success">
                    <h5>Upload Thumbnail Image</h5>
                    <input type="file" name="image">
                    <hr>
                    <h5>Previous Image</h5>
                    @if($image_exist)
                        <img class="img-responsive" src="{{$image_exist->folder_path.'/'.$image_exist->name}}">
                    @endif
                </div>

                <div class="alert alert-warning">Total {{ count($uploads) }} images. You can view bottom of this page.</div>
            </div>
            <div class="col-sm-9">
                <div class="alert alert-success">
                    <div class="form-group"> <!-- Message field -->
                        <label class="control-label " for="message">Article Description</label>
                        <textarea class="summernote" name="body">{!! $article->body !!}</textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-md" style="width: 100%;"><span class="glyphicon glyphicon-ok-sign"></span> Update</button>
                    </div>
                </div>
            </div>
        </form>
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
                    <form action="" method="post" enctype="multipart/form-data" class="product_save">
                        {{ csrf_field() }}
                        <input name="_method" type="hidden" value="PUT">
                        <input name="product_id" type="hidden" value="{{ $product->id }}">
                        <div class="form-group"> <!-- Name field -->
                            <label class="control-label " for="name"><span style="color: red;" >{{ ++$key }}. </span> Title</label>
                            <input class="form-control" name="title" type="text" value="{{ $product->product_title }}" disabled/>
                        </div>

                        <div class="form-group"> <!-- Message field -->
                            <label class="control-label " for="message">Product Description</label>
                            <textarea class="summernote product_description" data-product="{{$product->id}}" name="product_description">{!! $product->product_description !!}</textarea>
                        </div>

                        <div class="form-group">
                            <span>Created at: </span>
                            <span>
                                <input class="form-control bottom5" name="created_at" type="text" value="{{ $product->created_at }}" />
                            </span>
                            <button type="submit" class="product_save btn btn-warning btn-md" ><span class="glyphicon glyphicon-ok-sign"></span> Save</button>
                            <div class="top5 hidden please-save save-request-{{$product->id}}"><span class="btn-sm btn-danger">Please Save</span></div>
                        </div>
                    </form>

                    @if(Auth::user()->roleType->name == 'admin')
                        <div class="pull-right top-30">
                            <form action="" method="post" enctype="multipart/form-data" class="product_delete">
                                {{ csrf_field() }}
                                <input name="_method" type="hidden" value="PUT">
                                <input type="hidden" name="product_id" value="{{$product->id}}">
                                <input type="hidden" name="product_order" value="{{ $key }}">
                                <button type="submit" class="btn btn-danger btn" ><span class="glyphicon glyphicon-remove-sign"></span> Delete</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    @endforeach

    <div class="alert alert-success">
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

@endsection

@section('run_custom_jquery')

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
                data:$(this).serialize(),
                success:function(result){
                }
            });
            $(this).addClass('hidden');

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

    </script>

@endsection

@section('run_custom_js_file')
    <script type="text/javascript" src="{{ asset('summernote/summernote.js')}}" ></script>
    <script  type="text/javascript"  src="{{ asset('summernote/summernote-image-attributes.js') }}" ></script>
@endsection