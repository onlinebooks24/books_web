<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Category;
use App\Post;
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
use App\Upload;

class AdminArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderBy('created_at','desc')->Paginate(10);
        $categories = Category::all();
        return view('admin.articles.index',['categories' => $categories,'posts' => $posts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'title' => 'required|unique:posts'
        ]);
        $post = new Post();

        $post->title = $request['title'];
        $post->user_id = Auth::user()->id ;
        $post->category_id = $request['category_id'];
        $slug = strtolower($request['title']);
        $slug = str_replace(' ', '-', $slug);
        $post->slug = $slug ;
        $post->keyword = $request['keyword'];
        $post->status = true;
        $post->meta_description = $request['meta_description'];

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

        $post->body = $dom->saveHTML();
        $post->save();

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
                $upload->post_id = $post->id;
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

        $post->body = $dom->saveHTML();

        $post->update();

        return redirect()->back()->with(['success' => 'Post Created Successfully']);
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
        $post = Post::find($id);
        $categories = Category::all();
        return view ('admin.articles.edit',['post'=>$post, 'categories'=>$categories]);
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
        $post = Post::find($id);

        $post->title = $request['title'];
        $post->user_id = Auth::user()->id ;
        $post->category_id = $request['category_id'];
        $slug = strtolower($request['title']);
        $slug = str_replace(' ', '-', $slug);
        $post->slug = $slug ;
        $post->keyword = $request['keyword'];
        $post->status = true;
        $post->meta_description = $request['meta_description'];

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
                $upload->post_id = $post->id;
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
        $post->body = $dom->saveHTML();

        $post->update();

        return redirect()->route('admin_articles.index')->with(['success' => 'Post Updated Successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        if(!$post){
            return redirect()->route('post.index')->with(['fail' => 'Page not found !']);
        }
        $post->delete();
        return redirect()->route('admin_articles.index')->with(['success' => 'Post Deleted Successfully.']);
    }

}
