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

        $video_template = VideosTemplate::find($request->video_template_id);

        $video_path = public_path("/uploads/videos");
        $temp_html_dir = $video_path . "/temp_data/". $video_template->template_name . "/" ;
        $final_video_path = $video_path . "/final_videos/";

        if (!is_dir($temp_html_dir)) {
            mkdir($temp_html_dir, 0777, true);
        }

        if (!is_dir($final_video_path)) {
            mkdir($final_video_path, 0777, true);
        }

        $video_creator_script = $temp_html_dir . "video_creator_script.txt";

        $img_no = 1;
        $file_desc = fopen($video_creator_script,"w");
        $audio_location = $video_template->audio_location;

        $article = Article::find($request->article_id);
        $video_name = $article->slug . '.mp4';

        $duration_array = $request->duration;

        foreach($html_description as $key => $html_item){
            $temp_html_file =  $temp_html_dir . "temp_design.html";
            $open_temp_html_file = fopen($temp_html_file,"w");
            fwrite($open_temp_html_file,$html_item);
            fclose($open_temp_html_file);

            $image_name = "image".str_pad($img_no++, 5, "0", STR_PAD_LEFT).".jpeg";
            $new_image_file = $temp_html_dir.$image_name;

            Browsershot::url("file://".$temp_html_file)->setScreenshotType('jpeg',100)->windowSize(1280, 720)->save($new_image_file);
            fwrite($file_desc,"file '".$new_image_file."'\n");
            fwrite($file_desc,"duration ". $duration_array[$key] ."\n");
        }

        fclose($file_desc);

        $command = "ffmpeg -f concat -safe 0 -i '".$video_creator_script."' -i ". $audio_location ." -pix_fmt yuv420p -vf scale=1280:720 -y -shortest ".$final_video_path.$video_name;

        $video_create_log = shell_exec($command);
//        shell_exec("rm -rf ". $temp_html_dir );

        $video = Video::where('article_id', $article->id)->first();

        if(empty($video)){
            $video = new Video();
        }

        $video->article_id = $request->article_id;
        $video->video_template_id = $request->video_template_id;
        $video->video_name = $video_name;
        $video->youtube_link = $request->youtube_link;
        $video->save();

        $flash_message = 'Successfully Saved. Log:'. $video_create_log;
        Session::flash('message', $flash_message);

        return redirect()->to(route('admin_videos.index'));
    }
}
