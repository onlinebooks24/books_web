<?php


Auth::routes();

Route::group(['middleware' => ['web']] , function() {

	Route::group(['middleware' => 'auth' , 'prefix' => 'admin'] , function() {
		Route::get('/' , [
			'uses' => 'Admin\AdminController@index',
			'as' => 'admin.index'
		]);
		Route::get('/logout' , [
			'uses' => 'Admin\AdminController@getLogout',
			'as' => 'admin.logout'
		]);
		Route::resource('/admin_category','Admin\AdminCategoryController');
		Route::any('/admin_articles/product_save',[
			'uses' => 'Admin\AdminArticlesController@product_save',
			'as' => 'admin_articles.product_save'
		]);
		Route::any('/admin_articles/product_add/',[
			'uses' => 'Admin\AdminArticlesController@product_add',
			'as' => 'admin_articles.product_add'
		]);
		Route::any('/admin_articles/product_destroy/',[
			'uses' => 'Admin\AdminArticlesController@product_destroy',
			'as' => 'admin_articles.product_destroy'
		]);
		Route::any('/admin_articles/publish_or_unpublished/{id}',[
			'uses' => 'Admin\AdminArticlesController@publish_or_unpublished',
			'as' => 'admin_articles.publish_or_unpublished'
		]);
		Route::any('/admin_articles/set_article_deadline/{id}',[
			'uses' => 'Admin\AdminArticlesController@set_article_deadline',
			'as' => 'admin_articles.set_article_deadline'
		]);
		Route::any('/admin_articles/submit_for_review/{id}',[
			'uses' => 'Admin\AdminArticlesController@submit_for_review',
			'as' => 'admin_articles.submit_for_review'
		]);
		Route::any('/admin_articles/review_article/',[
			'uses' => 'Admin\AdminArticlesController@review_article',
			'as' => 'admin_articles.review_article'
		]);
		Route::any('/admin_articles/product_review/{isbn}',[
			'uses' => 'Admin\AdminArticlesController@product_review',
			'as' => 'admin_articles.product_review'
		]);
		Route::any('/admin_articles/edit_time_tracker/{article_id}',[
			'uses' => 'Admin\AdminArticlesController@edit_time_tracker',
			'as' => 'admin_articles.edit_time_tracker'
		]);
		Route::resource('/admin_articles','Admin\AdminArticlesController');
		Route::resource('/admin_uploads','Admin\AdminUploadsController');
		Route::resource('/admin_auto_articles','Admin\AdminAutoArticlesController');
		Route::resource('/admin_product_orders','Admin\AdminProductOrdersController');
		Route::resource('/admin_site_costs','Admin\AdminSiteCostsController');
		Route::resource('/admin_scheduler_jobs','Admin\AdminSchedulerJobsController');
		Route::resource('/admin_collect_mail_queues','Admin\AdminCollectMailQueuesController');
		Route::resource('/admin_temporary_email','Admin\AdminTemporaryEmailController');

		Route::any('/admin_articles/admin_videos/youtube_upload/{video_id}',[
			'uses' => 'Admin\AdminVideosController@youtubeUploadShow',
			'as' => 'admin_videos.youtube_upload_show'
		]);

		Route::post('/admin_articles/admin_videos/youtube_upload/{video_id}',[
			'uses' => 'Admin\AdminVideosController@youtubeUpload',
			'as' => 'admin_videos.youtube_upload'
		]);

		Route::resource('/admin_videos','Admin\AdminVideosController');
		Route::resource('/admin_videos_templates','Admin\AdminVideosTemplatesController');

		Route::any('/admin_all_reports/',[
			'uses' => 'Admin\AdminAllReportsController@index',
			'as' => 'admin_all_reports.index'
		]);
	});

	Route::get('/', [
		'uses' => 'ArticleController@index',
		'as' => 'blog.index'
	]);


	Route::get('/articles/{slug}', [
		'uses' => 'ArticleController@show',
		'as' => 'articles.show'
	]);

	Route::resource('articles' , 'ArticleController');


	Route::get('/categories/{slug}' , [
		'uses' => 'ArticleController@getCategoryPost',
		'as' => 'category.post'
	]);

	Route::get('/category-json/{parent_id}' , [
		'uses' => 'ArticleController@CategoryJson',
		'as' => 'category.browse_parent_id'
	]);

	Route::any('/subscribe-now/' , [
		'uses' => 'EmailSubscriberController@SubscribeNow',
		'as' => 'email.subscribe_now'
	]);

	Route::any('/unsubscribe/' , [
		'uses' => 'EmailSubscriberController@Unsubscribe',
		'as' => 'email.unsubscribe'
	]);

	Route::any('/update-category-subscriber/' , [
		'uses' => 'EmailSubscriberController@UpdateCategorySubscriber',
		'as' => 'email.update_category_subscriber'
	]);

	Route::get('/advertise-us/' , function(){
		return View::make('frontend.other.advertise_us');
	});

	Route::get('/contact/' , function(){
		return View::make('frontend.other.contact');
	});

	Route::get('/add-your-books/' , function(){
		return View::make('frontend.other.add_your_books');
	});

	Route::get('/privacy-policy/' , function(){
		return View::make('frontend.other.privacy_policy');
	});

	Route::get('/refund-policy/' , function(){
		return View::make('frontend.other.refund_policy');
	});

	Route::get('/scholarship/' , function(){
		return View::make('frontend.other.scholarship');
	});

	Route::get('/terms-of-service/' , function(){
		return View::make('frontend.other.terms_of_service');
	});

	Route::get('/get_location', [
		'uses' => 'HomeController@getLocation',
		'as' => 'home.location'
	]);

	Route::get('/basic_email', [
		'uses' => 'MailController@basic_email',
		'as' => 'mail'
	]);

    Route::get('/search_results', [
        'uses' => 'ArticleController@searchResults',
        'as' => 'search.results'
    ]);

	Route::get('/sitemap.xml', [
		'uses' => 'HomeController@xmlSitemap',
		'as' => 'homepage.sitemap'
	]);
});
