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
		Route::resource('/category','CategoryController');
		Route::resource('/admin_articles','Admin\AdminArticlesController');
		Route::get('/admin_auto_articles', [
				'uses' => 'Admin\AdminAutoArticlesController@index',
				'as' => 'admin.auto_articles.index'
			]);
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


	Route::get('/{slug}' , [
		'uses' => 'ArticleController@getCategoryPost',
		'as' => 'category.post'
	]);

	Route::get('/basicemail', [
		'uses' => 'MailController@basic_email',
		'as' => 'mail'
	]);
});
