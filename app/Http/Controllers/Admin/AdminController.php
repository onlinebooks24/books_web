<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class AdminController extends Controller
{

	public function index()
	{
		return view('admin.index');
	}

    public function getlogout()
    {
    	Auth::logout();
    	return redirect()->route('blog.index');
    }
}
