<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\SiteCost;
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
use Goutte\Client;

class AdminArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $site_costs = SiteCost::all();

        foreach($site_costs as $site_cost){
            if(!empty($site_cost->article_id)){
                $article = Article::find($site_cost->article_id);
                if(!empty($article)){
                    $article->created_at = $site_cost->when_paid;
                    $article->updated_at = $site_cost->when_paid;
                    $article->save();
                }
            }
        }

        $articles = Article::where('status', true)->orderBy('created_at', 'desc')->Paginate(150);
        return view('admin.articles.index', ['articles' => $articles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('parent_id', '1000')->orderBy('name', 'asc')->get();
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
        $this->validate($request, [
            'title' => 'required|unique:articles',
            'slug' => 'required|unique:articles',
            'expired_slug' => 'unique:articles',
            'thumbnail_alt_tag' => 'required'
        ]);

        $category_id = $request['category_id'];

        $article = new Article();

        $article->title = $request['title'];
        $article->user_id = Auth::user()->id ;
        $article->category_id = $category_id;
        $slug = strtolower($request['slug']);
        $slug = str_replace(' ', '-', $slug);
        $article->slug = $slug ;
        $expired_slug = strtolower($request['expired_slug']);
        $expired_slug = str_replace(' ', '-', $expired_slug);
        if(!empty($expired_slug)){
            $article->expired_slug = $expired_slug ;
        }
        $article->thumbnail_alt_tag = $request['thumbnail_alt_tag'];
        $article->keyword = $request['keyword'];
        $article->status = false;
        $article->waiting_for_approval = false;
        $article->meta_description = $request['meta_description'];
        $article->meta_title = $request['meta_title'];
        $article->conclusion = $request['conclusion'];

        $message = $request->input('body');
        $dom = new DomDocument();
        libxml_use_internal_errors(true);

        $dom->loadHTML('<?xml encoding="utf-8" ?>' . "<div>$message</div>");

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

        if (!empty(Input::file('image'))) {
            $this->saveThumbnail($article);
        }

        foreach ($images as $img) {
            $src = $img->getAttribute('src');

            if (preg_match('/data:image/', $src)) {
                $this->mime_type_image_save($src, $img, $article);
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
        $categories = Category::where('parent_id', '1000')->orderBy('name', 'asc')->get();
        $published_products = Product::where(['article_id' => $id, 'deleted' => false])->orderBy('created_at', 'asc')->get();
        $deleted_products = Product::where(['article_id' => $id, 'deleted' => true])->orderBy('created_at', 'asc')->get();
        $uploads = Upload::where('article_id', $article->id)->orderBy('created_at', 'desc')->get();

        $image_exist = null;
        if (!empty($article->thumbnail_id)) {
            $image_exist = Upload::find($article->thumbnail_id);
        }

        return view('admin.articles.edit', ['article'=>$article,
            'categories'=> $categories,
            'published_products'=> $published_products,
            'deleted_products'=> $deleted_products,
            'image_exist'=> $image_exist,
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
        $this->validate($request, [
            'title' => 'required',
            'slug' => 'required'
        ]);

        $article = Article::find($id);
        $article->title = $request['title'];
        $article->thumbnail_alt_tag = $request['thumbnail_alt_tag'];
        $expired_slug = strtolower($request['expired_slug']);
        $expired_slug = str_replace(' ', '-', $expired_slug);

        if(!empty($expired_slug)){
            $article->expired_slug = $expired_slug ;
        }

        if (!empty($request['user_id'])) {
            $article->user_id = $request['user_id'];
        }

        if (!$article->status) {
            $slug = strtolower($request['slug']);
            $slug = str_replace(' ', '-', $slug);
            $article->slug = $slug;
        }


        $article->user_id = $request['user_id'] ;
        if (!empty($request['category_id'])) {
            $article->category_id = $request['category_id'];
        }
        $article->keyword = $request['keyword'];

        if (!empty($request['meta_title'])) {
            $article->meta_title = $request['meta_title'];
        }

        if (!empty($request['meta_description'])) {
            $article->meta_description = $request['meta_description'];
        }

        $article->conclusion = $request['conclusion'];

        if (!empty(Input::file('image'))) {
            $this->saveThumbnail($article);
        }

        $message = $request->input('body');

        $dom = new DomDocument();
        libxml_use_internal_errors(true);

        $dom->loadHTML('<?xml encoding="utf-8" ?>' . "<div>$message</div>");

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
                $this->mime_type_image_save($src, $img, $article);
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
        if (!$article) {
            return redirect()->route('admin_articles.index')->with(['fail' => 'Page not found !']);
        }

        foreach ($article->products as $product) {
            $product->delete();
        }

        foreach ($article->uploads as $upload) {
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

    public function publish_or_unpublished($id)
    {
        $article = Article::find($id);

        if ($article->status) {
            $article->status = false;
        } else {
            $article->waiting_for_approval = false;
            $article->created_at = Carbon::now();
            $article->status = true;

            $products = Product::where(['article_id' => $article->id, 'deleted' => true ])->get();

            foreach ($products as $product) {
                $product->delete();
            }
        }

        $article->update();

        $category = $article->category;
        $articles = Article::where('category_id', $category->id)->where('status', true)->get();

        if (count($articles) > 0) {
            $category->category_status = true;
            $category->update();
        } else {
            $category->category_status = false;
            $category->update();
        }

        return redirect()->back();
    }

    public function set_article_deadline($id, Request $request)
    {
        $article = Article::find($id);

        $article->article_deadline = $request->article_deadline;
        $article->save();

        return redirect()->back();
    }

    public function submit_for_review($id)
    {
        $article = Article::find($id);

        $article->waiting_for_approval = true;
        $article->save();

        return redirect()->back();
    }

    public function review_article()
    {
        if (Auth::user()->roleType->name != 'editor') {
            $articles = Article::where('status', false)->orderBy('created_at', 'desc')->Paginate(100);
        } else {
            $articles = Article::where('status', false)
                ->where('user_id', Auth::user()->id)
                ->orderBy('created_at', 'desc')
                ->Paginate(10);
        }
        return view('admin.articles.review_article', ['articles' => $articles]);
    }

    public function mime_type_image_save($src, $img, $article)
    {
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

    public function saveThumbnail($article)
    {
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

        $image = Input::file('image');

        $thumb_filename = 'obr_thumb_250_250_' . $filename;
        Image::make($image->getRealPath())->resize(250, 250, function ($constraint) {
            $constraint->aspectRatio();
        })->save(public_path($folder_path . $thumb_filename));

        $thumb_md5 = md5_file(public_path($folder_path . $thumb_filename));

        $upload = new Upload();
        $upload->name = $thumb_filename;
        $upload->folder_path = $folder_path;
        $upload->md5_hash = $thumb_md5;
        $upload->article_id = $article->id;
        $upload->save();

        $upload = new Upload();
        $upload->name = $filename;
        $upload->folder_path = $folder_path;
        $upload->md5_hash = $img_md5_value;
        $upload->article_id = $article->id;
        $upload->save();
        $article->thumbnail_id = $upload->id;
        Input::file('image')->move(public_path($folder_path), $filename);
    }

    public function product_add(Request $request)
    {
        $isbn = $request['isbn'];
        $article_id = $request['article_id'];

        $amazon_response = Helper::amazonAdAPI($isbn);
        $amazon_response = $amazon_response->response;
        $amazon_response = json_decode($amazon_response);

        $item = $amazon_response->ItemsResult->Items[0];
        if($item){
            $get_amazon_items = $item;
        } else {
            $get_amazon_items = null;
        }

        if(!empty($get_amazon_items)){
            $item = $get_amazon_items ;
            $editorial_details = '';
            $date = '';
            $author_name = $get_amazon_items->ItemInfo->ByLineInfo->Contributors[0]->Name;
            $publication_date = isset($get_amazon_items->ItemInfo->ContentInfo->PublicationDate)? $get_amazon_items->ItemInfo->ContentInfo->PublicationDate->DisplayValue : '';
            $release_date = isset($get_amazon_items->ItemInfo->ProductInfo->ReleaseDate)? $get_amazon_items->ItemInfo->ProductInfo->ReleaseDate->DisplayValue : '';
            if (!empty($publication_date)) {
                $date = $publication_date;
            }else {
                if (!empty($release_date)) {
                    $date = $release_date;
                }
            }

            $product = new Product();
            $product->isbn = $item->ASIN;
            $product->product_title = $item->ItemInfo->Title->DisplayValue;
            $product->product_description = $editorial_details;
            $product->amazon_link = $item->DetailPageURL;
            $product->image_url = $item->Images->Primary->Large->URL;
            $product->author_name = $author_name;
            $product->publication_date = $date? Carbon::parse($date)->format('Y-m-d 00:00:00') : '';
            $product->article_id = $article_id;
            $product->save();

        }




        return redirect()->back()->with(['success' => 'Product Created Successfully']);
    }

    public function product_destroy(Request $request)
    {
        $product_id = $request['product_id'];
        $product = Product::find($product_id);

        if ($product->deleted) {
            $product->delete();
        } else {
            $product->deleted = true;
            $product->save();
        }
    }

    public function product_review($isbn)
    {
        $product_url = "https://www.amazon.com/product-reviews/". $isbn. "/ref=cm_cr_arp_d_viewopt_srt?sortBy=recent&pageNumber=1";
        $client = new Client();
        $client->setHeader('user-agent', "Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.101 Safari/537.36");
        $crawler = $client->request('GET', $product_url);
        $json_data = null;
        $total_review_count = $crawler->filter('.totalReviewCount')->each(function ($node) {
            return $node->text();
        });

        $json_data['total_review_count'] = $total_review_count[0];

        $total_rating = $crawler->filter('.arp-rating-out-of-text')->each(function ($node) {
            return ((float)(substr($node->text(), 0, 3)));
        });

        $json_data['total_rating'] = $total_rating[0];

        $total_rating_details = $crawler->filter('.histogram-review-count')->each(function ($node) {
            return $node->text();
        });

        $json_data['total_rating_details'] = $total_rating_details;

//        $total_review_details = $crawler->filter('#cm_cr-review_list .a-icon-alt , #cm_cr-review_list .a-color-base, .review-text')->each(function ($node) {
        $total_review_details = $crawler->filter('#cm_cr-review_list .review-date,#cm_cr-review_list .review-title, #cm_cr-review_list .a-icon-alt, #cm_cr-review_list .review-text')->each(function ($node) {
            return $node->text();
        });

        $json_data['total_review_details'] = $total_review_details;

        return response()->json($json_data);
    }


    public function edit_time_tracker($article_id)
    {
        $article = Article::find($article_id);

        $role_type = Auth::user()->roleType->name;

        $spend_time_type = $role_type. '_spend_time';

        $spend_time = Carbon::createFromFormat('H:i:s', $article[$spend_time_type])
            ->addSeconds(5)->format('H:i:s');
        $article[$spend_time_type]  = $spend_time;

        $article->save();


        $json_data['editor_spend_time'] = $article->editor_spend_time;
        $json_data['admin_spend_time'] = $article->admin_spend_time;
        $json_data['sub_admin_spend_time'] = $article->sub_admin_spend_time;

        return response()->json($json_data);
    }
}
