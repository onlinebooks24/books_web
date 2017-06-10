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
		Route::any('/admin_articles/publish_or_unpublished/{id}',[
			'uses' => 'Admin\AdminArticlesController@publish_or_unpublished',
			'as' => 'admin_articles.publish_or_unpublished'
		]);
		Route::any('/admin_articles/review_article/',[
			'uses' => 'Admin\AdminArticlesController@review_article',
			'as' => 'admin_articles.review_article'
		]);
		Route::resource('/admin_articles','Admin\AdminArticlesController');
		Route::resource('/admin_auto_articles','Admin\AdminAutoArticlesController');
	});

	Route::get('/', [
		'uses' => 'ArticleController@index',
		'as' => 'blog.index'
	]);


	Route::get('/articles/{slug}', [
		'uses' => 'ArticleController@getSinglePost',
		'as' => 'articles.single'
	]);

	Route::resource('articles' , 'ArticleController');


	Route::get('/categories/{slug}' , [
		'uses' => 'ArticleController@getCategoryPost',
		'as' => 'category.post'
	]);

	Route::get('/basicemail', [
		'uses' => 'MailController@basic_email',
		'as' => 'mail'
	]);
});
