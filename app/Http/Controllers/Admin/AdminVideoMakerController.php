<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminVideoMakerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $path;
    private $descriptor;
    private $video_name;


    public function makeVideo($slug)
    {
        $article_url_api = "https://www.onlinebooksreview.com/articles/".$slug."?format=json";
        
        $this->path=public_path("/uploads/videos");
        $this->descriptor=$this->path."/video_desc.txt";
        echo realpath($this->path);

        $json=$this->get_from($article_url_api);
        $json2=$this->make_ready($json);
        $ret=$this->make_desc($json2);
        echo "\n";
        echo $this->make_video($ret);
        echo "\n";
    }


    private function make_desc($input)
    {
        $in_data=json_decode($input,true);
        $option=array();
        $option[0]=array("high_quality",1);
        $option[1]=array("subtitle_type","\"render\"");
        $option[2]=array("border",120);
        $option[3]=array("sharpen",1);
        $option[4]=array("widescreen",1);
        $option[5]=array("subtitle_font_size",16);
        $option[6]=array("subtitle_location_x",0);
        $option[7]=array("subtitle_location_y",75);
        $option[8]=array("subtitle_color","\"black\"");
        $option[9]=array("subtitle_outline_color","\"white\"");
        $fadein="fadein:1\n";
        $fadeout="fadeout:1\n";
        
        $background=$in_data["background"];
        $audio=$in_data["audio"];
        $trans=$in_data["trans"];
        
        
        $file_desc=fopen($this->descriptor,"w");
        
        for($i=0;$i<sizeof($option);$i++)
        {
            fwrite($file_desc,$option[$i][0]."=".$option[$i][1]."\n");
        }
        
        fwrite($file_desc,realpath($audio).":1:fadein:0:fadeout:2\n");
        fwrite($file_desc,"background:0::".realpath($background)."\n");
        fwrite($file_desc,"background:1\n");
        fwrite($file_desc,$fadein);
        fwrite($file_desc,"title:".$in_data["title"]["duration"].":".$this->title_maker($in_data["title"]["text"])."\n");
        fwrite($file_desc,$trans);
        fwrite($file_desc,realpath($in_data["intro"]["img_name"]).":".$in_data["intro"]["duration"]."\n");
        fwrite($file_desc,$trans);
        
        for($i=0;$i<sizeof($in_data["book"]);$i++)
        {
            //fwrite($file_desc,realpath($in_data["book"][$i]["rank_img"]).":2\n");
            //fwrite($file_desc,$trans);
            fwrite($file_desc,realpath($in_data["book"][$i]["image"]["img_name"]).":".$in_data["book"][$i]["image"]["duration"].":".$in_data["book"][$i]["image"]["name"].":\n");
            fwrite($file_desc,$trans);
            fwrite($file_desc,realpath($in_data["book"][$i]["details"]["img_name"]).":".$in_data["book"][$i]["details"]["duration"]."\n");
            fwrite($file_desc,$trans);
        }
        
        fwrite($file_desc,realpath($in_data["conclution"]["img_name"]).":".$in_data["conclution"]["duration"]."\n");
        fwrite($file_desc,$fadeout);
        fwrite($file_desc,"background:0\n");
        fwrite($file_desc,"exit\n");
        fclose($file_desc);
        
        return true;
        
    }

    private function make_ready($input)
    {
        $old_data=json_decode($input,true);
        $new_data=array();
        $new_data["title"]["text"]=str_replace(":","\:",$old_data["title"]["text"]);
        $new_data["title"]["duration"]=$old_data["title"]["duration"];
        $new_data["intro"]["img_name"]=$this->html2img($old_data["intro"]["text"],"intro.png");
        $new_data["intro"]["duration"]=$old_data["intro"]["duration"];
        
        for($i=0;$i<sizeof($old_data["book"]);$i++)
        {
            //$new_data["book"][$i]["rank_img"]=rank_maker($i+1);
            $new_data["book"][$i]["details"]["img_name"]=$this->html2img($old_data["book"][$i]["details"]["text"],"t".($i+1).".png");
            $new_data["book"][$i]["details"]["duration"]=$old_data["book"][$i]["details"]["duration"];
            
            $new_data["book"][$i]["image"]["img_name"]=$this->file_downloader($old_data["book"][$i]["image"]["link"],"i".($i+1).".jpeg");
            $new_data["book"][$i]["image"]["duration"]=$old_data["book"][$i]["image"]["duration"];
            $new_data["book"][$i]["image"]["name"]=($i+1)."\: ".str_replace(":","\:",$old_data["book"][$i]["image"]["name"]);
            
        }
        
        $new_data["conclution"]["img_name"]=$this->html2img($old_data["conclution"]["text"],"conc.png");
        $new_data["conclution"]["duration"]=$old_data["conclution"]["duration"];
        
        $new_data["background"]=$this->file_downloader($old_data["background"],"back.jpeg");
        $new_data["audio"]=$this->file_downloader($old_data["audio"],"audio.mp3");
        $new_data["trans"]=$old_data["trans"];
        
        
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
        shell_exec("dvd-slideshow -n '".$name."' -o '".$this->path."' -f '".$this->path."/video_desc.txt' -mp4 -s 1920x1080");
        return $this->path."/".$name.".mp4";
        
    }
    
    private function get_from($url)
    {
        $json=file_get_contents($url);
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
    
        $cmd="xvfb-run --server-args=\"-screen 0, 710x400x24\" ";
        $cmd=$cmd."cutycapt --min-width=710 --min-height=400 --url=file://".realpath($htm)." --out=".$this->path."/temp_data/".$name." 2>&1";
        shell_exec($cmd);
        sleep(10);
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
