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
		Route::any('/admin_articles/review_article/',[
			'uses' => 'Admin\AdminArticlesController@review_article',
			'as' => 'admin_articles.review_article'
		]);
		Route::resource('/admin_articles','Admin\AdminArticlesController');
		Route::resource('/admin_uploads','Admin\AdminUploadsController');
		Route::resource('/admin_auto_articles','Admin\AdminAutoArticlesController');
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

	Route::get('/category_json/{parent_id}' , [
		'uses' => 'ArticleController@CategoryJson',
		'as' => 'category.browse_parent_id'
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

	Route::get('/basicemail', [
		'uses' => 'MailController@basic_email',
		'as' => 'mail'
	]);
});
