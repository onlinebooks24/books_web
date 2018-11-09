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
        $article = Article::find($request->article_id);
        $article_json_data['article'] = $article;
        $article_json_data['products'] = $article->products;
        $article_json_data = json_encode($article_json_data);

        $this->path=public_path("/uploads/videos");
        $this->descriptor=$this->path."/video_desc.txt";
        echo realpath($this->path);

        $json=$this->get_from($article_json_data);
        $json2=$this->make_ready($json);
        $ret=$this->make_desc($json2);
        echo "\n";
        echo $this->make_video($ret);
        echo "\n";

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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
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
        $new_data["background"]=$this->file_downloader($old_data["background"],"image".str_pad($img_no++, 5, "0", STR_PAD_LEFT).".jpeg");
        $new_data["audio"]=$this->file_downloader($old_data["audio"],"audio.mp3");
        $new_data["trans"]=$old_data["trans"];
        $new_data["title"]["text"]=str_replace(":","\:",$old_data["title"]["text"]);
        $new_data["title"]["duration"]=$old_data["title"]["duration"];
        $new_data["intro"]["img_name"]=$this->html2img($old_data["intro"]["text"],"image".str_pad($img_no++, 5, "0", STR_PAD_LEFT).".jpeg");
        $new_data["intro"]["duration"]=$old_data["intro"]["duration"];
        
        for($i=0;$i<sizeof($old_data["book"]);$i++)
        {
            $new_data["book"][$i]["details"]["img_name"]=$this->html2img($old_data["book"][$i]["details"]["text"],"image".str_pad($img_no++, 5, "0", STR_PAD_LEFT).".jpeg");
            $new_data["book"][$i]["details"]["duration"]=$old_data["book"][$i]["details"]["duration"];
            
            $new_data["book"][$i]["image"]["img_name"]=$this->file_downloader($old_data["book"][$i]["image"]["link"],"image".str_pad($img_no++, 5, "0", STR_PAD_LEFT).".jpeg");
            $new_data["book"][$i]["image"]["duration"]=$old_data["book"][$i]["image"]["duration"];
            $new_data["book"][$i]["image"]["name"]=($i+1)."\: ".str_replace(":","\:",$old_data["book"][$i]["image"]["name"]);
            
        }
        
        $new_data["conclution"]["img_name"]=$this->html2img($old_data["conclution"]["text"],"image".str_pad($img_no++, 5, "0", STR_PAD_LEFT).".jpeg");
        $new_data["conclution"]["duration"]=$old_data["conclution"]["duration"];
        
        
        
        
        return json_encode($new_data);
        
    }
    
    private function make_video($flag)
    {
        if(!$flag)
        {
            exit(0);
        }
        //$name="video_".rand(100,100000);
        $name=$this->video_name;
        shell_exec("ffmpeg -f concat -safe 0 -i '".$this->path."/video_desc.txt' -i '".$this->audio."' -vsync vfr -pix_fmt yuv420p  -y -shortest '".$this->path."/".$name.".mp4'");

        return $this->path."/".$name.".mp4";
        
    }
    
    private function get_from($article_json_data)
    {
        $json= $article_json_data;
        $old_data=json_decode($json,true);
        $new_data=array();
        $new_data["title"]["text"]=$old_data["article"]["title"];
        $new_data["title"]["duration"]=4;
        $new_data["intro"]["text"]=$old_data["article"]["body"];
        $new_data["intro"]["duration"]=5;
        $new_data["conclution"]["text"]="Simple conclution";
        $new_data["conclution"]["duration"]=6;
        $new_data["background"]=$this->path."/extra/back.jpeg";
        $new_data["audio"]=$this->path."/extra/audio.mp3";
        $new_data["trans"]="crossfade:1\n";
        $this->video_name=$old_data["article"]["slug"];
        
        for($i=0;$i<sizeof($old_data["products"]);$i++)
        {
            $new_data["book"][$i]["details"]["duration"]=6;
            $new_data["book"][$i]["details"]["text"]=$old_data["products"][$i]["product_description"];
            
            $new_data["book"][$i]["image"]["duration"]=4;
            $new_data["book"][$i]["image"]["name"]=$old_data["products"][$i]["product_title"];
            $new_data["book"][$i]["image"]["link"]=$old_data["products"][$i]["image_url"];
        }
        
        return json_encode($new_data);
    }

    private function html2img($string,$name)
    {
        $htm=$this->path."/temp_data/tm_pg.html";
        $page=fopen($htm,"w");
        $code='<html><head><meta http-equiv="content-type" content="text/html;charset=utf-8" /><style>html {border-radius: 25px; background-color: white;border: 2px solid #73AD21;padding: 20px; }</style></head><body>';
        $code=$code.$string."</body></html>";
        fwrite($page,$code);
        fclose($page);
    
//        $cmd="xvfb-run --server-args=\"-screen 0, 710x400x24\" ";
//        $cmd=$cmd."cutycapt --min-width=710 --min-height=400 --url=file://".realpath($htm)." --out=".$this->path."/temp_data/".$name." 2>&1";
//        shell_exec($cmd);

        Browsershot::url("file://".realpath($htm))->setScreenshotType('jpeg',100)->save($this->path."/temp_data/".$name);
//        sleep(10);
        return $this->path."/temp_data/".$name;
    }

    private function file_downloader($url,$name)
    {
        $dest=$this->path."/temp_data/".$name;
        copy($url,$dest);
        return $this->path."/temp_data/".$name;
    }


    //private function rank_maker($rank)
    //{
    //    $cmd="convert -size 710x400 -gravity center -font Helvetica caption:".$rank." temp_data/r".$rank.".jpeg";
    //    shell_exec($cmd);
    //    sleep(10);
    //    return "./temp_data/"."r".$rank.".jpeg";
    //}

    private function title_maker($input)
    {
        $words=explode(" ",$input);
        $new_string="";
        for($i=1;$i<=sizeof($words);$i++)
        {
            $new_string=$new_string.$words[$i-1];
            if(($i%4)==0)
            {
                $new_string=$new_string."\\n";
            }
            else
            {
                $new_string=$new_string." ";
            }
        }
        return $new_string;
    }
}
