<aside>
    <div class="col-md-4">
        <section class="text-center top10">
            <iframe src="//rcm-na.amazon-adsystem.com/e/cm?o=1&p=12&l=ur1&category=textbooks&banner=1NWP9RHBJNE6K3EB85R2&f=ifr&lt1=_blank&lc=pf4&linkID=5c3670f0cbd2ce4c440324d3ce43d417&t=onlinebooksre-20&tracking_id=onlinebooksre-20" width="300" height="250" scrolling="no" border="0" marginwidth="0" style="border:none;" frameborder="0"></iframe>

            <div class="top10">
                <iframe src="//rcm-na.amazon-adsystem.com/e/cm?o=1&p=12&l=ur1&category=kuft&banner=07V9YHKS4HY556H67002&f=ifr&lt1=_blank&lc=pf4&linkID=0f63a9139ca9fb51f254997f0daf46ee&t=onlinebooksre-20&tracking_id=onlinebooksre-20" width="300" height="250" scrolling="no" border="0" marginwidth="0" style="border:none;" frameborder="0"></iframe>
            </div>

            <div class="top10">
                <iframe src="//rcm-na.amazon-adsystem.com/e/cm?o=1&p=12&l=ur1&category=audible&banner=1C5G327FPA4S79WQ9Z82&f=ifr&lt1=_blank&lc=pf4&linkID=2a61ac098ef4356a716d458b310de730&t=onlinebooksre-20&tracking_id=onlinebooksre-20" width="300" height="250" scrolling="no" border="0" marginwidth="0" style="border:none;" frameborder="0"></iframe>
            </div>
        </section>

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
                    <input type="text" class="form-control" placeholder="Search">
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
            <div class="border-block">
                <h4>Popular Articles</h4>
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="list-unstyled">
                            @foreach($articles->reverse() as $article)
                                <div class="popular-post">
                                    <div class="row">
                                        <div class="col-md-2 nopadding">
                                            @if(!empty($article->uploads->first()))
                                                <p align="center"><img style="width: 58px; height: 45px" src="{{ $article->uploads->first()->folder_path.'/'.$article->uploads->first()->name }}"></p>
                                            @endif
                                        </div>

                                        <div class="col-md-10 pleft5">
                                            <div class="popular-post-title">
                                                <a href="{{ route('articles.single' , [ 'slug' => $article->slug ])}}">{{ $article->title }}</a>
                                            </div>
                                            <div class="popular-post-time">
                                                published on {{ $article->created_at->format('m-d-Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <!-- /.row -->
            </div>
        </section>

        <section>
            <!-- Blog Categories Well -->
            <div class="border-block">
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

    </div>
</aside>
