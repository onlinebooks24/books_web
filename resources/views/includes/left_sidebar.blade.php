<aside>
    <div class="col-md-4">
        <section>
            <!-- Side Widget Well  -->
            <div class="border-block">
                <h4>Be with us</h4>
                <div class="fb-page" data-href="https://www.facebook.com/onlinebooksreview/" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/onlinebooksreview/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/onlinebooksreview/">Online Books Review</a></blockquote></div>
                <div id="fb-root"></div>
                <script>(function(d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0];
                        if (d.getElementById(id)) return;
                        js = d.createElement(s); js.id = id;
                        js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.9&appId=193869374063299";
                        fjs.parentNode.insertBefore(js, fjs);
                    }(document, 'script', 'facebook-jssdk'));</script>
            </div>
        </section>

        <section>
            <!-- Blog Search Well -->
            <div class="border-block">
                <h4>Article Search</h4>
                <div class="input-group">
                    <input type="text" class="form-control">
            <span class="input-group-btn">
                <button class="btn btn-default" type="button">
                    <span class="glyphicon glyphicon-search"></span>
                </button>
            </span>
                </div>
                <!-- /.input-group -->
            </div>
        </section>

        <section>
            <!-- Blog Categories Well -->
            <div class="border-block hidden">
                <h4>Article Categories</h4>
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="list-unstyled">
                            @foreach($categories as $category)
                                <li style="text-transform: capitalize;"><a href="{{ route('category.post',['slug' => $category->slug ])}}">{{ $category->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <!-- /.row -->
            </div>
        </section>

        <section>
            <div class="border-block">
                <h4>Popular Articles</h4>
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="list-unstyled">
                            @foreach($articles->reverse() as $article)
                                <div class="popular-post">
                                    <div class="popular-post-title">
                                        <a href="{{ route('articles.single' , [ 'slug' => $article->slug ])}}">{{ $article->title }}</a>
                                    </div>
                                    <div class="popular-post-time">
                                        published on {{ $article->created_at->format('m-d-Y') }}
                                    </div>
                                </div>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <!-- /.row -->
            </div>
        </section>

    </div>
</aside>
