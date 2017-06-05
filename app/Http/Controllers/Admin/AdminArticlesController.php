<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Article;
use Auth;
use Illuminate\Support\Facades\Input;
use \DomDocument;
use Mockery\Recorder;
use Session;
use Redirect;
use Intervention\Image\ImageManagerStatic as Image;
use Carbon\Carbon;
use File;
use Date;
use App\Models\Upload;

class AdminArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $articles = Article::orderBy('created_at','desc')->Paginate(10);
        return view('admin.articles.index',['articles' => $articles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.articles.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request , [
            'title' => 'required|unique:articles'
        ]);
        $article = new Article();

        $article->title = $request['title'];
        $article->user_id = Auth::user()->id ;
        $article->category_id = $request['category_id'];
        $slug = strtolower($request['title']);
        $slug = str_replace(' ', '-', $slug);
        $article->slug = $slug ;
        $article->keyword = $request['keyword'];
        $article->status = true;
        $article->meta_description = $request['meta_description'];

        $message = $request->input('body');
        $dom = new DomDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML("<div>$message</div>");

        $container = $dom->getElementsByTagName('div')->item(0);
        $container = $container->parentNode->removeChild($container);

        while ($dom->firstChild) {
            $dom->removeChild($dom->firstChild);
        }

        while ($container->firstChild) {
            $dom->appendChild($container->firstChild);
        }

        $images = $dom->getElementsByTagName('img');

        $article->body = $dom->saveHTML();
        $article->save();

        foreach ($images as $img) {
            $src = $img->getAttribute('src');

            if (preg_match('/data:image/', $src)) {
                preg_match('/data:image\/(?<mime>.*?)\;/', $src, $groups);
                $mimetype = $groups['mime'];
                $filename = date("d") . '_' . $img->getAttribute('data-filename');
                $filename = str_replace(' ', '_', $filename);
                $public_path = public_path() . '/uploads/blog_images/';
                $year_folder = $public_path . date("Y");
                $month_folder = $year_folder . '/' . date("m");

                !file_exists($year_folder) && mkdir($year_folder, 0777);
                !file_exists($month_folder) && mkdir($month_folder, 0777);
                $folder_path = "/uploads/blog_images/" . date('Y') . "/" . date('m') . "/";
                $img_md5_value = md5_file($src);
                $image_exist = Upload::where([['name', '=', $filename], ['folder_path', '=', $folder_path]])->first();

                if (!empty($image_exist)) {
                    $filename =  Carbon::now()->timestamp . '_' . $filename;
                }

                $upload = new Upload();
                $upload->name = $filename;
                $upload->folder_path = $folder_path;
                $upload->md5_hash = $img_md5_value;
                $upload->article_id = $article->id;
                $upload->save();
                $image = Image::make($src)
                    // resize if required
                    /* ->resize(300, 200) */
                    ->encode($mimetype, 100)// encode file to the specified mimetype
                    ->save(public_path($folder_path.$filename));

                $new_src = $folder_path.$filename;
                $img->removeAttribute('src');
                $img->setAttribute('src', $new_src);
            } // <!--endif
        } // <!-

        $article->body = $dom->saveHTML();

        $article->update();

        return redirect()->back()->with(['success' => 'Article Created Successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $article = Article::find($id);
        $categories = Category::all();
        return view ('admin.articles.edit',['article'=>$article, 'categories'=>$categories]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $article = Article::find($id);

        $article->title = $request['title'];
        $article->user_id = Auth::user()->id ;
        $article->category_id = $request['category_id'];
        $slug = strtolower($request['title']);
        $slug = str_replace(' ', '-', $slug);
        $article->slug = $slug ;
        $article->keyword = $request['keyword'];
        $article->status = true;
        $article->meta_description = $request['meta_description'];

        $message = $request->input('body');
        $dom = new DomDocument();
        libxml_use_internal_errors(true);

        $dom->loadHTML("<div>$message</div>");

        $container = $dom->getElementsByTagName('div')->item(0);

        $container = $container->parentNode->removeChild($container);

        while ($dom->firstChild) {
            $dom->removeChild($dom->firstChild);
        }

        while ($container->firstChild) {
            $dom->appendChild($container->firstChild);
        }

        $images = $dom->getElementsByTagName('img');

        foreach ($images as $img) {
            $src = $img->getAttribute('src');

            if (preg_match('/data:image/', $src)) {
                preg_match('/data:image\/(?<mime>.*?)\;/', $src, $groups);
                $mimetype = $groups['mime'];
                $filename = date("d") . '_' . $img->getAttribute('data-filename');
                $filename = str_replace(' ', '_', $filename);
                $public_path = public_path() . '/uploads/blog_images/';
                $year_folder = $public_path . date("Y");
                $month_folder = $year_folder . '/' . date("m");

                !file_exists($year_folder) && mkdir($year_folder, 0777);
                !file_exists($month_folder) && mkdir($month_folder, 0777);
                $folder_path = "/uploads/blog_images/" . date('Y') . "/" . date('m') . "/";
                $img_md5_value = md5_file($src);
                $image_exist = Upload::where([['name', '=', $filename], ['folder_path', '=', $folder_path]])->first();

                if (!empty($image_exist)) {
                    $filename =  Carbon::now()->timestamp . '_' . $filename;
                }

                $upload = new Upload();
                $upload->name = $filename;
                $upload->folder_path = $folder_path;
                $upload->md5_hash = $img_md5_value;
                $upload->article_id = $article->id;
                $upload->save();
                $image = Image::make($src)
                    // resize if required
                    /* ->resize(300, 200) */
                    ->encode($mimetype, 100)// encode file to the specified mimetype
                    ->save(public_path($folder_path.$filename));

                $new_src = $folder_path.$filename;
                $img->removeAttribute('src');
                $img->setAttribute('src', $new_src);
            } // <!--endif
        } // <!-
        $article->body = $dom->saveHTML();

        $article->update();

        return redirect()->route('admin_articles.index')->with(['success' => 'Article Updated Successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = Article::find($id);
        if(!$article){
            return redirect()->route('article.index')->with(['fail' => 'Page not found !']);
        }
        $article->delete();
        return redirect()->route('admin_articles.index')->with(['success' => 'Article Deleted Successfully.']);
    }

}
