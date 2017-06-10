@extends('layouts.admin_master')

@section('run_custom_css_file')
    <link rel="stylesheet" type="text/css" href="{{ asset('summernote/summernote.css')}}">
@endsection

@section('content')
    <div class="row">
        <div class="alert alert-info">
            <strong>Update Articles</strong>
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
        <div class="alert alert-success">
            <form action="{{ route('admin_articles.update' , $article->id)}}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input name="_method" type="hidden" value="PUT">
                <div class="form-group"> <!-- Name field -->
                    <label class="control-label " for="name">Title</label>
                    <input class="form-control" name="title" type="text" value="{{ $article->title }}" />
                </div>

                <div class="form-group"> <!-- Name field -->
                    <label class="control-label " for="name">Keyword</label>
                    <input class="form-control" name="keyword" type="text" value="{{ $article->keyword }}" required />
                </div>

                <div class="form-group"> <!-- Name field -->
                    <label class="control-label " for="name">Meta Description</label>
                    <textarea class="form-control" name="meta_description" type="text" required>{{ $article->meta_description }}</textarea>
                </div>

                <div class="form-group">
                    <label class="control-label">Select Category</label>
                    <select class="form-control" name="category_id">
                        <option value="">Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id}}" {{ $article->category_id == $category->id ? 'selected'  : '' }} > {{ $category->name }} </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group"> <!-- Message field -->
                    <label class="control-label " for="message">Article Description</label>
                    <textarea class="summernote" name="body">{!! $article->body !!}</textarea>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-warning btn-lg" style="width: 100%;"><span class="glyphicon glyphicon-ok-sign"></span> Update</button>
                </div>

            </form>
        </div>
    </div>

    @php $i = 1 ; @endphp
    @foreach($products as $product)
        <div class="row">
            <div class="alert alert-success">
                <form action="" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input name="_method" type="hidden" value="PUT">
                    <input name="product_id" type="hidden" value="{{ $product->id }}">
                    <div class="form-group"> <!-- Name field -->
                        <label class="control-label " for="name"><span style="color: red;" >@php echo $i; $i++; @endphp</span> Title</label>
                        <input class="form-control" name="title" type="text" value="{{ $product->product_title }}" disabled/>
                    </div>

                    <div class="form-group"> <!-- Message field -->
                        <label class="control-label " for="message">Product Description</label>
                        <textarea class="summernote product_description" name="product_description">{!! $product->product_description !!}</textarea>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="product_save btn btn-warning btn-lg" style="width: 100%;"><span class="glyphicon glyphicon-ok-sign"></span> Update</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

@endsection

@section('run_custom_js_file')
    <script type="text/javascript" src="{{ asset('summernote/summernote.js')}}" ></script>
    <script  type="text/javascript"  src="{{ asset('summernote/summernote-image-attributes.js') }}" ></script>
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

        $('.product_save').on('submit', function (e) {
            e.preventDefault();
            var product_description = $('.product_description').html();
            $.ajax({
                type: "POST",
                url: '{!!URL::route('admin_articles.product_save')!!}',
                data: {product_description : product_description},
                dataType: "json",
                success: function( msg ) {
                    $(".product_description").html(msg);
                }
            });
        });


    </script>
@endsection