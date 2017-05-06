<?php

Auth::routes();

Route::group(['middleware' => ['web']] , function() {

	Route::group(['middleware' => 'auth' , 'prefix' => 'admin/dashboard'] , function() {
		Route::get('/home' , [
			'uses' => 'AdminController@getIndex',
			'as' => 'admin.index'
		]);
		Route::get('/logout' , [
			'uses' => 'AdminController@getLogout',
			'as' => 'admin.logout'
		]);
		Route::resource('/category','CategoryController');
		Route::resource('/post','PostController');
	});

	Route::get('/', [
		'uses' => 'ArticleController@index',
		'as' => 'blog.index'
	]);


	Route::get('/articles/{slug}', [
		'uses' => 'ArticleController@getSinglePost',
		'as' => 'post.single'
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
