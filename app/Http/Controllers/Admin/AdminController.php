<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class AdminController extends Controller
{

	public function index()
	{
		if(Auth::user()->roleType->name == 'editor'){
			return redirect('/admin/admin_articles/review_article');
		} else {
			return redirect('/admin/admin_all_reports');
		}
	}

    public function getlogout()
    {
    	Auth::logout();
    	return redirect()->route('blog.index');
    }

}
