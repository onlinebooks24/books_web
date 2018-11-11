<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VideosTemplate;
use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\Article;
use Session;
use Spatie\Browsershot\Browsershot;

class AdminVideosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $videos = Video::paginate(10);
        return view('admin.videos.index', compact('videos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $article = Article::find($request->article_id);
        $videos_templates = VideosTemplate::all();

        if(empty($request->video_template)){
            $videos_template = $videos_templates->first();
        } else {
            $videos_template = VideosTemplate::find($request->video_template);
        }

        $products = $article->products;
        return view('admin.videos.create', compact('article', 'products', 'videos_templates', 'videos_template'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $html_description = $request->html_description;

        $video_path = public_path("/uploads/videos");
        $video_creator_script = $video_path. "/video_creator_script.txt";
        $img_no = 1;
        $file_desc = fopen($video_creator_script,"w");
        $audio = public_path("/uploads/videos/temp_data/audio.mp3");

        $article = Article::find($request->article_id);
        $video_name = $article->slug . '.mp4';

        foreach($html_description as $html_item){
            $htm = $video_path . "/temp_data/tm_pg.html";
            $html_file = fopen($htm,"w");
            fwrite($html_file,$html_item);
            fclose($html_file);

            $picture_name = "image".str_pad($img_no++, 5, "0", STR_PAD_LEFT).".jpeg";

            Browsershot::url("file://".realpath($htm))->setScreenshotType('jpeg',100)->save($video_path."/temp_data/".$picture_name);
            fwrite($file_desc,"file '".realpath($video_path."/temp_data/".$picture_name)."'\n");
            fwrite($file_desc,"duration 5"."\n");
        }

        fclose($file_desc);

        $command = "ffmpeg -f concat -safe 0 -i '".$video_creator_script."' -i '". $audio ."' -vsync vfr -pix_fmt yuv420p  -y -shortest '".$video_path."/".$video_name;

        shell_exec($command);

        $video = new Video();
        $video->article_id = $request->article_id;
        $video->video_template_id = $request->video_template_id;
        $video->video_name = $video_name;
        $video->youtube_link = $request->youtube_link;
        $video->save();

        $flash_message = 'Successfully Saved';
        Session::flash('message', $flash_message);

        return redirect()->to(route('admin_videos.index'));
    }
}
