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
use App\Helpers\Helper;
use App\User;

class AdminArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::where('status', true)->orderBy('created_at','desc')->Paginate(100);
        return view('admin.articles.index',['articles' => $articles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('parent_id', '1000')->orderBy('name','asc')->get();
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
            'title' => 'required|unique:articles',
            'slug' => 'required|unique:articles'
        ]);

        $category_id = $request['category_id'];

        $article = new Article();

        $article->title = $request['title'];
        $article->user_id = Auth::user()->id ;
        $article->category_id = $category_id;
        $slug = strtolower($request['slug']);
        $slug = str_replace(' ', '-', $slug);
        $article->slug = $slug ;
        $article->keyword = $request['keyword'];
        $article->status = false;
        $article->waiting_for_approval = true;
        $article->meta_description = $request['meta_description'];
        $article->conclusion = $request['conclusion'];

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

        if(!empty(Input::file('image'))){
            $this->saveThumbnail($article);
        }

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
        $users = User::all();
        $article = Article::find($id);
        $categories = Category::where('parent_id', '1000')->orderBy('name','asc')->get();
        $products = Product::where('article_id',$id)->orderBy('created_at','asc')->get();
        $uploads = Upload::where('article_id', $article->id)->orderBy('created_at','desc')->Paginate(10);

        $image_exist = null;
        if(!empty($article->thumbnail_id)){
            $image_exist = Upload::find($article->thumbnail_id);
        }

        return view ('admin.articles.edit',['article'=>$article,
            'categories'=>$categories,
            'products'=>$products,
            'image_exist'=>$image_exist,
            'users' => $users,
            'uploads' => $uploads]);
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
        $this->validate($request , [
            'title' => 'required',
            'slug' => 'required'
        ]);

        $article = Article::find($id);
        $article->title = $request['title'];
        $article->user_id = $request['user_id'];

        if (!$article->status){
            $slug = strtolower($request['slug']);
            $slug = str_replace(' ', '-', $slug);
            $article->slug = $slug;
        }


        $article->user_id = $request['user_id'] ;
        if(!empty($request['category_id'])){
            $article->category_id = $request['category_id'];
        }
        $article->keyword = $request['keyword'];
        if(!empty($request['meta_description'])){
            $article->meta_description = $request['meta_description'];
        }
        $article->conclusion = $request['conclusion'];

        if(!empty(Input::file('image'))){
            $this->saveThumbnail($article);
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
        $product->created_at = $request['created_at'];
        $product->save();
    }

    public function publish_or_unpublished($id){
        $article = Article::find($id);

        if($article->status){
            $article->status = false;
        } else {
            $article->waiting_for_approval = false;
            $article->created_at = Carbon::now();
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
        $filename = $img->getAttribute('data-filename');
        $filename = date("d") . '_' . $filename;
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

    public function saveThumbnail($article){
        $filename = Input::file('image')->getClientOriginalName();
        $filename = date("d") . '_' . $filename;
        $filename = str_replace(' ', '_', $filename);
        $general_directory = '/uploads/blog_images/';
        $public_path = public_path() . $general_directory ;
        $year_folder = $public_path . date("Y");
        $month_folder = $year_folder . '/' . date("m");

        !file_exists($year_folder) && mkdir($year_folder, 0777);
        !file_exists($month_folder) && mkdir($month_folder, 0777);
        $folder_path = $general_directory . date('Y') . "/" . date('m') . "/";
        $img_md5_value = md5_file(Input::file('image'));
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
        $article->thumbnail_id = $upload->id;
        Input::file('image')->move(public_path($folder_path), $filename);
    }

    public function product_add(Request $request){
        $isbn = $request['isbn'];
        $article_id = $request['article_id'];
        $search_query = [
            'Operation' => 'ItemLookup',
            'ResponseGroup' => 'Medium',
            'ItemId' => $isbn
        ];

        $amazon_response = Helper::amazonAdAPI($search_query);

        if(isset($amazon_response['Items']['Item'])){
            $get_amazon_items = $amazon_response['Items']['Item'];
        } else {
            $get_amazon_items = null;
        }

        if(!empty($get_amazon_items)){
            $item = $get_amazon_items ;
                if(isset($item['EditorialReviews']['EditorialReview'])){
                    $editorial_array = $item['EditorialReviews']['EditorialReview'];
                }
                $editorial_details = '';
                if(!isset($editorial_array['Content'])){
                    foreach($editorial_array as $editorial_item){
                        $editorial_details = $editorial_item['Content'];
                    }
                } else {
                    $editorial_details = $editorial_array['Content'];
                }

                $author_name = null;
                if(isset($item['ItemAttributes']['Author'])){
                    $author_name = $item['ItemAttributes']['Author'];
                    if(is_array($author_name)){
                        $author_name = implode(',', $author_name);
                    }
                }

                if(isset($item['ItemAttributes']['PublicationDate'])){
                    $publication_date = $item['ItemAttributes']['PublicationDate'];
                } else {
                    $publication_date = null;
                }


                if( strlen($publication_date) == 7 ){
                    $publication_date = $publication_date. '-01';
                } elseif (strlen($publication_date) == 4) {
                    $publication_date = $publication_date. '-01'.'-01';
                }

                $product = new Product();
                $product->isbn = $item['ASIN'];
                $product->product_title = $item['ItemAttributes']['Title'];
                $product->product_description = $editorial_details;
                $product->amazon_link = $item['DetailPageURL'];
                $product->image_url = $item['LargeImage']['URL'];
                $product->author_name = $author_name;
                $product->article_id = $article_id;
                $product->publication_date = $publication_date;
                $product->save();
        }
        return redirect()->back()->with(['success' => 'Product Created Successfully']);

    }

    public function product_destroy(Request $request){
        $product_id = $request['product_id'];
        $product = Product::find($product_id);
        $product->delete();
    }
}
