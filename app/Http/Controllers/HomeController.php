<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Helpers\Helper;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('web');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('frontend.index');
    }

    public function getLocation()
    {
        return Helper::get_location();
    }

    public function xmlSitemap(){
        $articles = Article::all();
        return response()->view('frontend.other.sitemap', compact('articles'))->header('Content-Type', 'text/xml');
    }

}
