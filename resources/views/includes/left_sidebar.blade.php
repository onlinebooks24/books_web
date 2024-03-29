<!-- Sidebar -->
<aside class="col-md-4 sidebar sidebar--right">
    <!-- Widget Popular Posts -->
    <div class="widget widget-popular-posts">
        <h4 class="widget-title sidebar__widget-title">Popular Posts</h4>
        <ul class="widget-popular-posts__list">
            @foreach($popular_articles as $key => $article)
                <li>
                    <article class="clearfix">
                        <div class="widget-popular-posts__img-holder">
                            <span class="widget-popular-posts__number">{{ $key + 1 }}</span>
                            <div class="thumb-container">
                                <a href="{{ route('articles.show' , [ 'slug' => $article->slug ])}}">
                                    @if(isset($article->thumbnail_image))
                                        <img data-src="{{ $article->thumbnail_image->folder_path . 'obr_thumb_250_250_' . $article->thumbnail_image->name }}" src="{{ $article->thumbnail_image->folder_path . 'obr_thumb_250_250_' . $article->thumbnail_image->name }}" alt="" class="lazyload">
                                    @endif
                                </a>
                            </div>
                        </div>
                        <div class="widget-popular-posts__entry">
                            <h3 class="widget-popular-posts__entry-title">
                                <a href="{{ route('articles.show' , [ 'slug' => $article->slug ])}}">{{ $article->title }}</a>
                            </h3>
                        </div>
                    </article>
                </li>
            @endforeach
        </ul>
    </div> <!-- end widget popular posts -->

    <!-- Widget Newsletter -->
    <div class="widget widget_mc4wp_form_widget">
        <h4 class="widget-title">Subscribe for our news and receive daily updates</h4>
        <form id="mc4wp-form-1" class="mc4wp-form" method="post">
            <div class="mc4wp-form-fields">
                <p>
                    <i class="mc4wp-form-icon ui-email"></i>
                    <input type="email" name="email" class="update_email" placeholder="Your email" required="">
                </p>
                <p>
                    <input type="submit" class="btn btn-md btn-color btn-subscribe" value="Subscribe">
                </p>
            </div>
        </form>
    </div> <!-- end widget newsletter -->

    @if(!Auth::check())
    <div class="widget top10 bottom20">
        <script async="async" data-cfasync="false" src="//ghostsinstance.com/ab9a6a90f70177d3a21c9959e19d9c1b/invoke.js"></script>
        <div id="container-ab9a6a90f70177d3a21c9959e19d9c1b"></div>
    </div>
    @endif

    <div class="widget">
        <h4 class="widget-title">Categories</h4>
        <div class="row">
            <div class="col-lg-12">
                <ul class="list-unstyled">
                    @foreach($categories as $category)
                        <li style="text-transform: capitalize;">
                            <a href="{{ route('category.post',['slug' => $category->slug ])}}">{{ $category->name }}</a>
                            @php $child_categories = \App\Models\Category::where(['parent_id' => $category->browse_node_id, 'category_status' => true])->get(); @endphp
                            <ul>
                                @foreach($child_categories as $child_category)
                                    <li class="left5"><a style="color: #49545E" href="{{ route('category.post',['slug' => $child_category->slug ])}}">{{ $child_category->name }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <!-- Widget socials -->
    <div class="widget widget-socials">
        <h4 class="widget-title">Keep up with Online Books Review</h4>
        <ul class="socials">
            <li>
                <a class="social-facebook" href="https://facebook.com/onlinebooksreview" title="facebook" target="_blank">
                    <i class="ui-facebook"></i>
                    <span class="socials__text">Facebook</span>
                </a>
            </li>
            <li>
                <a class="social-twitter" href="https://twitter.com/onlinebooks24" title="twitter" target="_blank">
                    <i class="ui-twitter"></i>
                    <span class="socials__text">Twitter</span>
                </a>
            </li>
            <li>
                <a class="social-google-plus" href="https://plus.google.com/b/110233331450185953116/" title="google" target="_blank">
                    <i class="ui-google"></i>
                    <span class="socials__text">Google+</span>
                </a>
            </li>
            <li>
                <a class="social-pinterest" href="https://www.pinterest.com/onlinebooksr/" title="pinterest" target="_blank">
                    <i class="ui-pinterest"></i>
                    <span class="socials__text">Pinterest</span>
                </a>
            </li>
            <li>
                <a class="social-linkedin" href="https://www.linkedin.com/company-beta/13346322" title="linkedin" target="_blank">
                    <i class="ui-linkedin"></i>
                    <span class="socials__text">Linkedin</span>
                </a>
            </li>
        </ul>
    </div> <!-- end widget socials -->

    <!-- Widget Banner -->
    <div class="widget widget_media_image">
        <div class="text-center top10">
            <iframe src="//rcm-na.amazon-adsystem.com/e/cm?o=1&p=12&l=ur1&category=textbooks&banner=1NWP9RHBJNE6K3EB85R2&f=ifr&lt1=_blank&lc=pf4&linkID=5c3670f0cbd2ce4c440324d3ce43d417&t=onlinebooksre-20&tracking_id=onlinebooksre-20" width="300" height="250" scrolling="no" border="0" marginwidth="0" style="border:none;" frameborder="0"></iframe>

            <div class="top10">
                <iframe src="//rcm-na.amazon-adsystem.com/e/cm?o=1&p=12&l=ur1&category=kuft&banner=07V9YHKS4HY556H67002&f=ifr&lt1=_blank&lc=pf4&linkID=0f63a9139ca9fb51f254997f0daf46ee&t=onlinebooksre-20&tracking_id=onlinebooksre-20" width="300" height="250" scrolling="no" border="0" marginwidth="0" style="border:none;" frameborder="0"></iframe>
            </div>

            <div class="top10">
                <iframe src="//rcm-na.amazon-adsystem.com/e/cm?o=1&p=12&l=ur1&category=audible&banner=1C5G327FPA4S79WQ9Z82&f=ifr&lt1=_blank&lc=pf4&linkID=2a61ac098ef4356a716d458b310de730&t=onlinebooksre-20&tracking_id=onlinebooksre-20" width="300" height="250" scrolling="no" border="0" marginwidth="0" style="border:none;" frameborder="0"></iframe>
            </div>
        </div>
    </div> <!-- end widget banner -->

    <div class="widget">
        <h4 class="widget-title">Buy Kindle Ebook Reader</h4>
        <div>
            <a href="https://www.amazon.com/dp/B00OQVZDJM/?tag=onlinebooksre-20" target="_blank">
                <img src="https://images-na.ssl-images-amazon.com/images/I/71qVoGNiLcL._SX522_.jpg">
            </a>
        </div>
    </div>
</aside> <!-- end sidebar -->
