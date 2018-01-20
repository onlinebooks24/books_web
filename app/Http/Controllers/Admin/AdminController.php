<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class AdminController extends Controller
{

	public function index()
	{
		return redirect()->to(route('admin_all_reports.index'));
	}

    public function getlogout()
    {
    	Auth::logout();
    	return redirect()->route('blog.index');
    }

}
