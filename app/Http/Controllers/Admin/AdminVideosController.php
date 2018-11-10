<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        $products = $article->products;
        return view('admin.videos.create', compact('article', 'products'));
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
        $name = 'anc';
        $audio = public_path("/uploads/videos/temp_data/audio.mp3");

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

        $command = "ffmpeg -f concat -safe 0 -i '".$video_creator_script."' -i '". $audio ."' -vsync vfr -pix_fmt yuv420p  -y -shortest '".$video_path."/".$name.".mp4'";

        shell_exec($command);

        $video = new Video();
        $video->article_id = $request->article_id;
        $video->video_template_id = $request->video_template_id;
        $video->file_location = $request->file_location;
        $video->youtube_link = $request->youtube_link;
        $video->save();

        $flash_message = 'Successfully Saved';
        Session::flash('message', $flash_message);

        return redirect()->to(route('admin_videos.index'));
    }

    private function make_desc($input)
    {
        $in_data=json_decode($input,true);
        $this->audio=$in_data["audio"];
        
        $file_desc=fopen($this->descriptor,"w");

        fwrite($file_desc,"file '".$in_data["background"]."'\n");
        fwrite($file_desc,"duration 5"."\n");

        
        fwrite($file_desc,"file '".realpath($in_data["intro"]["img_name"])."'\n");
        fwrite($file_desc,"duration ".$in_data["intro"]["duration"]."\n");
        
        for($i=0;$i<sizeof($in_data["book"]);$i++)
        {
            
            fwrite($file_desc,"file '".realpath($in_data["book"][$i]["image"]["img_name"])."'\n");
            fwrite($file_desc,"duration ".$in_data["book"][$i]["image"]["duration"]."\n");
            fwrite($file_desc,"file '".realpath($in_data["book"][$i]["details"]["img_name"])."'\n");
            fwrite($file_desc,"duration ".$in_data["book"][$i]["details"]["duration"]."\n");
        }
        
        fwrite($file_desc,"file '".realpath($in_data["conclution"]["img_name"])."'\n");
        fwrite($file_desc,"duration ".$in_data["conclution"]["duration"]."\n");
        
        fclose($file_desc);
        
        return true;
        
    }

    private function make_ready($input)
    {
        $img_no=1;
        $old_data=json_decode($input,true);
        $new_data=array();
        $new_data["background"]= $this->html2img($old_data["title"]["text"],"image".str_pad($img_no++, 5, "0", STR_PAD_LEFT).".jpeg", "template_1");
        $new_data["audio"]=$this->file_downloader($old_data["audio"],"audio.mp3");
        $new_data["trans"]=$old_data["trans"];
        $new_data["title"]["text"]=str_replace(":","\:",$old_data["title"]["text"]);
        $new_data["title"]["duration"]=$old_data["title"]["duration"];
        $new_data["intro"]["img_name"]=$this->html2img($old_data["intro"]["text"],"image".str_pad($img_no++, 5, "0", STR_PAD_LEFT).".jpeg", "template_2");
        $new_data["intro"]["duration"]=$old_data["intro"]["duration"];
        
        for($i=0;$i<sizeof($old_data["book"]);$i++)
        {
            $new_data["book"][$i]["details"]["img_name"]=$this->html2img($old_data["book"][$i]["details"]["text"],"image".str_pad($img_no++, 5, "0", STR_PAD_LEFT).".jpeg", "template_2");
            $new_data["book"][$i]["details"]["duration"]=$old_data["book"][$i]["details"]["duration"];
            
            $new_data["book"][$i]["image"]["img_name"]=$this->file_downloader($old_data["book"][$i]["image"]["link"],"image".str_pad($img_no++, 5, "0", STR_PAD_LEFT).".jpeg");
            $new_data["book"][$i]["image"]["duration"]=$old_data["book"][$i]["image"]["duration"];
            $new_data["book"][$i]["image"]["name"]=($i+1)."\: ".str_replace(":","\:",$old_data["book"][$i]["image"]["name"]);
            
        }
        
        $new_data["conclution"]["img_name"]=$this->html2img($old_data["conclution"]["text"],"image".str_pad($img_no++, 5, "0", STR_PAD_LEFT).".jpeg", "template_2");
        $new_data["conclution"]["duration"]=$old_data["conclution"]["duration"];
        
        
        
        
        return json_encode($new_data);
        
    }

    private function html2img($string,$name, $template)
    {
        $htm=$this->path."/temp_data/tm_pg.html";
        $page=fopen($htm,"w");

        if($template == 'template_1'){
            $code='<html>
                <head>
                    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
                    <style>
                        body{
                            background-image: url(\'back.jpeg\');
                        }

                        .title {
                            text-align: center;
                            margin-top: 30px;
                        }
                    </style>
                </head>

            <body>
                <h1 class="title">'
                .$string.
                '</h1>
            </body>
            </html>';
        } else {
            $code='<html>
                <head>
                    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
                    <style>
                        body{
                            background-image: url(\'back.jpeg\');
                        }
                    </style>
                </head>

            <body>
                <div>'
                .$string.
                '</div>
            </body>
            </html>';
        }

        fwrite($page,$code);
        fclose($page);

        Browsershot::url("file://".realpath($htm))->setScreenshotType('jpeg',100)->save($this->path."/temp_data/".$name);
        return $this->path."/temp_data/".$name;
    }

    private function file_downloader($url,$name)
    {
        $dest=$this->path."/temp_data/".$name;
        copy($url,$dest);
        return $this->path."/temp_data/".$name;
    }
}
