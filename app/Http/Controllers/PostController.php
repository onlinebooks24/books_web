<?php

namespace App\Http\Controllers;

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

class PostController extends Controller
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
        return view('admin.post.index',['categories' => $categories,'posts' => $posts]);
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
        //$dom->loadHtml($message, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

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
        // foreach <img> in the submited message

        foreach ($images as $img) {
            $src = $img->getAttribute('src');

            // if the img source is 'data-url'
            if (preg_match('/data:image/', $src)) {
                // get the mimetype
                preg_match('/data:image\/(?<mime>.*?)\;/', $src, $groups);
                $mimetype = $groups['mime'];
                // Generating a random filename
                $filename = $img->getAttribute('data-filename');
                $filename = str_replace(' ', '_', $filename);
                $path = public_path() . '/uploads/blog_images/';
                $year_folder = $path . date("Y");
                $month_folder = $year_folder . '/' . date("m");

                !file_exists($year_folder) && mkdir($year_folder, 0777);
                !file_exists($month_folder) && mkdir($month_folder, 0777);
                $filepath = "/uploads/blog_images/" . date('Y') . "/" . date('m') . "/" . "$filename";
                $upload = new Upload();
                $image_exist = Upload::where('name', '=', $filename)->first();
                if ($image_exist === null) {
                    $upload->name = $filename;
                }else {
                    $filename = Carbon::now().'_'.$filename;
                    $upload->name = str_replace(' ', '_', $filename);
                }

                $upload->folder_path = "/uploads/blog_images/" . date('Y') . "/" . date('m') . "/";
                $upload->md5_hash = md5_file($src);
                $upload->save();
                // @see http://image.intervention.io/api/
                $image = Image::make($src)
                    // resize if required
                    /* ->resize(300, 200) */
                    ->encode($mimetype, 100)// encode file to the specified mimetype
                    ->save(public_path($filepath));
                $new_src = $filepath;
                $img->removeAttribute('src');
                $img->setAttribute('src', $new_src);
            } // <!--endif
        } // <!-
        $post->body = $dom->saveHTML();
        $post->save();
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
        return view ('admin.post.edit',['post'=>$post, 'categories'=>$categories]);
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
        //$dom->loadHtml($message, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

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
        // foreach <img> in the submited message

        foreach ($images as $img) {
            $src = $img->getAttribute('src');

            // if the img source is 'data-url'
            if (preg_match('/data:image/', $src)) {
                // get the mimetype
                preg_match('/data:image\/(?<mime>.*?)\;/', $src, $groups);
                $mimetype = $groups['mime'];
                // Generating a random filename
                $filename = $img->getAttribute('data-filename');
                $filename = str_replace(' ', '_', $filename);
                $path = public_path() . '/uploads/blog_images/';
                $year_folder = $path . date("Y");
                $month_folder = $year_folder . '/' . date("m");

                !file_exists($year_folder) && mkdir($year_folder, 0777);
                !file_exists($month_folder) && mkdir($month_folder, 0777);
                $filepath = "/uploads/blog_images/" . date('Y') . "/" . date('m') . "/" . "$filename";
                $upload = new Upload();
                $image_exist = Upload::where('name', '=', $filename)->first();
                if ($image_exist === null) {
                    $upload->name = $filename;
                }else {
                    $filename = Carbon::now().'_'.$filename;
                    $upload->name = str_replace(' ', '_', $filename);
                }
                $upload->folder_path = "/uploads/blog_images/" . date('Y') . "/" . date('m') . "/";
                $upload->md5_hash = md5_file($src);
                $upload->save();
                // @see http://image.intervention.io/api/
                $image = Image::make($src)
                    // resize if required
                    /* ->resize(300, 200) */
                    ->encode($mimetype, 100)// encode file to the specified mimetype
                    ->save(public_path($filepath));
                $new_src = $filepath;
                $img->removeAttribute('src');
                $img->setAttribute('src', $new_src);
            } // <!--endif
        } // <!-
        $post->body = $dom->saveHTML();

        $post->update();

        return redirect()->route('post.index')->with(['success' => 'Post Updated Successfully']);
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
        return redirect()->route('post.index')->with(['success' => 'Post Deleted Successfully.']);
    }

}
