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
use App\Models\Product;

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

        $category_id = $request['category_id'];

        $article = new Article();

        $article->title = $request['title'];
        $article->user_id = Auth::user()->id ;
        $article->category_id = $category_id;
        $slug = strtolower($request['title']);
        $slug = str_replace(' ', '-', $slug);
        $slug_check = Article::where('slug' , $slug)->first();
        if(!empty($slug_check)){
            $slug = $slug.'_'.Carbon::now()->timestamp;
        }
        $article->slug = $slug ;
        $article->keyword = $request['keyword'];
        $article->status = false;
        $article->waiting_for_approval = true;
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
                $this->mime_type_image_save($src,$img,$article);
            } // <!--endif
        } // <!-

        $article->body = $dom->saveHTML();

        $article->update();

        return redirect()->route('admin_articles.index')->with(['success' => 'Article Created Successfully']);
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
        $products = Product::where('article_id',$id)->get();
        $image_exist = Upload::where('article_id', '=', $article->id)->orderBy('id','desc')->first();

        return view ('admin.articles.edit',['article'=>$article, 'categories'=>$categories,'products'=>$products,'image_exist'=>$image_exist]);
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

        if(!empty($request['title'])){
            $article->title = $request['title'];

            if (!$article->status){
                $slug = strtolower($request['title']);
                $slug = str_replace(' ', '-', $slug);
                $slug_check = Article::where('slug' , $slug)->first();
                if(!empty($slug_check)){
                    $slug = $slug.'_'.Carbon::now()->timestamp;
                }
                $article->slug = $slug;
            }
        }

        $article->user_id = Auth::user()->id ;
        if(!empty($request['category_id'])){
            $article->category_id = $request['category_id'];
        }
        $article->keyword = $request['keyword'];
        if(!empty($request['meta_description'])){
            $article->meta_description = $request['meta_description'];
        }
        $article->conclusion = $request['conclusion'];

        if(!empty(Input::file('image'))){
            $fileName = Input::file('image')->getClientOriginalName();
            $public_path = public_path() . '/uploads/blog_images/';
            $year_folder = $public_path . date("Y");
            $month_folder = $year_folder . '/' . date("m");
            !file_exists($year_folder) && mkdir($year_folder, 0777);
            !file_exists($month_folder) && mkdir($month_folder, 0777);
            $destinationPath = public_path() ."/uploads/blog_images/" . date('Y') . "/" . date('m') . "/";
            $folder_path = "/uploads/blog_images/" . date('Y') . "/" . date('m') . "/";
            $fileName = $article->id.'_'.$fileName;
            Input::file('image')->move($destinationPath, $fileName);
            $upload = new Upload();
            $upload->name = $fileName;
            $upload->folder_path = $folder_path;
            $upload->md5_hash = md5_file($destinationPath.$fileName);
            $upload->article_id = $article->id;
            $upload->save();
            $article->thumbnail_id = $upload->id;
        }

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
                $this->mime_type_image_save($src,$img,$article);
            }
        }
        $article->body = $dom->saveHTML();

        $article->update();

        return redirect()->route('admin_articles.edit', $article->id)->with(['success' => 'Article Updated Successfully']);
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
            return redirect()->route('admin_articles.index')->with(['fail' => 'Page not found !']);
        }

        foreach($article->products as $product){
            $product->delete();
        }

        foreach($article->uploads as $upload){
            unlink(public_path($upload->folder_path.$upload->name));
            $upload->destroy($upload->id);
        }

        $article->delete();


        return redirect()->route('admin_articles.index')->with(['success' => 'Article Deleted Successfully.']);
    }

    public function product_save(Request $request)
    {
        $product_id = $request['product_id'];
        $product = Product::find($product_id);
        $product->product_description = $request['product_description'];
        $product->save();
    }

    public function publish_or_unpublished($id){
        $article = Article::find($id);

        if($article->status){
            $article->status = false;
        } else {
            $article->waiting_for_approval = false;
            $article->status = true;
        }

        $article->update();

        $category = $article->category;
        $articles = Article::where('category_id', $category->id)->where('status', true)->get();

        if(count($articles) > 0){
            $category->category_status = true;
            $category->update();
        } else {
            $category->category_status = false;
            $category->update();
        }

        return redirect()->back();
    }

    public function review_article(){
        $articles = Article::where('waiting_for_approval', true)->orderBy('created_at','desc')->Paginate(10);
        return view('admin.articles.review_article',['articles' => $articles]);
    }

    public function mime_type_image_save($src,$img,$article){
        preg_match('/data:image\/(?<mime>.*?)\;/', $src, $groups);
        $mimetype = $groups['mime'];
        $filename = date("d") . '_' . $img->getAttribute('data-filename');
        $filename = str_replace(' ', '_', $filename);
        $general_directory = '/uploads/blog_images/';
        $public_path = public_path() . $general_directory ;
        $year_folder = $public_path . date("Y");
        $month_folder = $year_folder . '/' . date("m");

        !file_exists($year_folder) && mkdir($year_folder, 0777);
        !file_exists($month_folder) && mkdir($month_folder, 0777);
        $folder_path = $general_directory . date('Y') . "/" . date('m') . "/";
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
    }

}
