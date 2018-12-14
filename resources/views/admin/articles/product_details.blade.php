<div class="row">
    <div class="col-md-3">
        <div class="alert alert-{{ $product->deleted? 'danger':'warning' }}">
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
        <div class="alert alert-{{ $product->deleted? 'danger':'warning' }}">
            <form action="" method="post" enctype="multipart/form-data" class="product_save">
                {{ csrf_field() }}
                <div class="product-box-{{ $product->isbn }}">

                    <input name="_method" type="hidden" value="PUT">
                    <input name="product_id" type="hidden" value="{{ $product->id }}">
                    <div class="form-group"> <!-- Name field -->
                        <label class="control-label " for="name"><span style="color: red;" >{{ ++$key }}. </span> Title</label>
                        <input class="form-control" name="title" type="text" value="{{ $product->product_title }}" disabled/>
                    </div>

                    <div class="form-group product-description"> <!-- Message field -->
                        <label class="control-label " for="message">Product Description</label>
                        @if($product->deleted)
                            <div class="content-box">{!! $product->product_description !!}</div>
                        @else
                            <textarea class="summernote product_description" data-product="{{$product->id}}" name="product_description">{!! $product->product_description !!}</textarea>
                        @endif
                    </div>

                    <div class="product-review-box hidden">
                        <div>
                            <b>Total Review Count:</b> <span class="total-review-count"></span>
                        </div>
                        <div>
                            <b>Total Rating:</b> <span class="total-rating"></span>
                        </div>

                        <div>
                            <b>Total Rating details:</b> <span class="total-rating-details"></span>
                        </div>

                        <div class="top5">
                            <b>Total Review details:</b> <div class="total-review-details"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <span>Created at: </span>
                            <span>
                                <input class="form-control bottom5" name="created_at" type="text" value="{{ $product->created_at }}" />
                            </span>
                        <button type="submit" class="product_save btn btn-warning btn-md" ><span class="glyphicon glyphicon-ok-sign"></span> Save</button>
                        <div class="top5 hidden please-save save-request-{{$product->id}}"><span class="btn-sm btn-danger">Please Save</span></div>

                        @if(Auth::user()->roleType->name != 'editor')
                            <div class="col-md-offset-7 top-25">
                                <div><a href="#" data-review-isbn="{{$product->isbn}}" class="btn btn-success view-review-here hidden" >View Review Here</a></div>
                                <div><a href="#" data-review-isbn="{{$product->isbn}}" class="btn btn-info view-description-here hidden" >View Description</a></div>
                            </div>
                        @endif
                    </div>
                </div>
            </form>

            @if(Auth::user()->roleType->name != 'editor')
                <div class="pull-right top-30">
                    <form action="" method="post" enctype="multipart/form-data" data-isbn="{{ $product->isbn }}" class="product_delete">
                        {{ csrf_field() }}
                        <input name="_method" type="hidden" value="PUT">
                        <input type="hidden" name="product_id" value="{{$product->id}}">
                        <input type="hidden" name="product_order" value="{{ $key }}">
                        <button type="submit" class="btn btn-danger btn"><span class="glyphicon glyphicon-remove-sign"></span> Delete</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>