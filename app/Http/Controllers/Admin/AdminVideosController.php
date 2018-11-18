<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VideosTemplate;
use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\Article;
use Session;
use Spatie\Browsershot\Browsershot;
use DOMXPath;
use DOMDocument;

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
        shell_exec("rm -rf ". $temp_html_dir );

        $final_video_path = $video_path . "/final_videos/";

        if (!is_dir($temp_html_dir)) {
            mkdir($temp_html_dir, 0777, true);
        }

        if (!is_dir($final_video_path)) {
            mkdir($final_video_path, 0777, true);
        }

        $video_creator_script = $temp_html_dir . "video_creator_script.txt";
        $file_desc = fopen($video_creator_script,"w");

        $img_no = 1;
        $audio_no = 1;

        $audio_creator_script = $temp_html_dir . "voice_creator_script.txt";
        $audio_desc = fopen($audio_creator_script,"w");

        $template_audio_location = $video_template->audio_location;

        $article = Article::find($request->article_id);
        $video_name = $article->slug . '.mp4';

        $duration_array = $request->duration;

        $new_image_file = null;

        foreach($html_description as $key => $html_item){
            $temp_html_file =  $temp_html_dir . "temp_design.html";
            $open_temp_html_file = fopen($temp_html_file,"w");

            fwrite($open_temp_html_file,$html_item);
            fclose($open_temp_html_file);

            $image_name = "image".str_pad($img_no++, 5, "0", STR_PAD_LEFT).".jpeg";
            $new_image_file = $temp_html_dir.$image_name;

            Browsershot::url("file://".$temp_html_file)->setScreenshotType('jpeg',100)->windowSize(1280, 720)->save($new_image_file);

            $doc = new DOMDocument();
            libxml_use_internal_errors(true);

            $doc->loadHTML($html_item);
            $finder = new DomXPath($doc);
            $node = $finder->query("//*[contains(@class, 'video-container')]");
            $voice_html = $doc->saveHTML($node->item(0));
            $voice_html = strip_tags($voice_html);
            $voice_html = '<Break time="2000ms"/>'. str_replace(["\n", "\t", "\r", ".."], [".", "", "", "."], $voice_html);
            $voice_html_array = str_split($voice_html, 600);
            $duration = 0;
            foreach($voice_html_array as $voice_html_item){
                $_POST['EID'] = 3;
                $_POST['LID'] = 1;
                $_POST['VID'] = 3;
                $_POST['TXT'] = $voice_html_item;
                $_POST['ACC'] = 6887135;
                $_POST['API'] = 2638673;
                $_POST['SECRET'] = '589e570dc04cbb23ed03c781c4adeb5d';
                $_POST['SESSION'] = '';

                $ext = isset($_POST['EXT']) &&
                in_array(trim(strtolower($_POST['EXT'])), array('mp3','swf')) ?
                    trim(strtolower($_POST['EXT'])) : '';
                $fxType = isset($_POST['FX_TYPE']) && strlen($_POST['FX_TYPE']) > 0 ?
                    $_POST['FX_TYPE'] : '';
                $fxLevel= isset($_POST['FX_LEVEL']) && strlen($_POST['FX_LEVEL']) > 0 ?
                    $_POST['FX_LEVEL'] : '';
                $httpErr= isset($_POST['HTTP_ERR']) && strlen($_POST['HTTP_ERR']) > 0 ?
                    $_POST['HTTP_ERR'] : '';
//Construct parameters.
                $get = 'EID='.$_POST['EID']
                    .'&LID='.$_POST['LID']
                    .'&VID='.$_POST['VID']
                    .'&TXT='.urlencode($_POST['TXT'])
                    .'&EXT='.$ext
                    .'&FX_TYPE='.$fxType
                    .'&FX_LEVEL='.$fxLevel
                    .'&ACC='.$_POST['ACC']
                    .'&API='.$_POST['API']
                    .'&SESSION='.$_POST['SESSION']
                    .'&HTTP_ERR='.$httpErr;
                $CS = md5($_POST['EID'].$_POST['LID'].$_POST['VID'].$_POST['TXT'].
                    $ext.$fxType.$fxLevel.$_POST['ACC']. $_POST['API'].$_POST['SESSION'].
                    $httpErr.$_POST['SECRET']);
                $url = 'http://www.vocalware.com/tts/gen.php?' . $get . '&CS=' . $CS;

                $audio_no += 1;
                $new_audio_file = $temp_html_dir. 'audio0000'. $audio_no . '.mp3';

                for($i = 0; $i < 5;  $i++){
                    \Log::info("----------------------". $i );
                    $this->downloadFile($url, $new_audio_file);
                    $audio_type =  mime_content_type($new_audio_file);
                    sleep(10);

                    if($audio_type == "audio/mpeg"){
                        $audio = new \wapmorgan\Mp3Info\Mp3Info($new_audio_file, true);
                        fwrite($audio_desc,"file '".$new_audio_file."'\n");
                        $duration += $audio->duration;
                        break;
                    }

                    if($i == 5){
                        dd($url, $voice_html_item, $new_audio_file);
                    }
                }
            }

            fwrite($file_desc,"file '".$new_image_file."'\n");
            fwrite($file_desc,"duration ". $duration ."\n");
        }

        fwrite($file_desc,"file '".$new_image_file."'\n");

        fclose($file_desc);
        fclose($audio_desc);

        $voice_audio_name = $temp_html_dir .'voice_output.mp3';
        $join_all_voice_command = 'ffmpeg -f concat -safe 0 -y -i '. $audio_creator_script .' -c copy '. $voice_audio_name;

        shell_exec($join_all_voice_command);

        $decrease_volume_name = $temp_html_dir. "audio_output.mp3";
        $decrease_volume_command = "ffmpeg -i ". $template_audio_location . " -filter:a \"volume=0.03\" " . $decrease_volume_name;
        shell_exec($decrease_volume_command);

        $final_audio_name = $temp_html_dir .'final_audio_output.mp3';
        $join_final_audio_command = "ffmpeg -i ".$voice_audio_name." -i ".$decrease_volume_name." -filter_complex amerge -ac 2 -c:a libmp3lame -q:a 4 ". $final_audio_name;

        shell_exec($join_final_audio_command);

        $create_video_command = "ffmpeg -f concat -safe 0 -i '".$video_creator_script."' -i ". $final_audio_name ." -vsync vfr -pix_fmt yuv420p -vf scale=1280:720 -y -shortest ".$final_video_path.$video_name;

        $video_create_log = shell_exec($create_video_command);

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

    public function downloadFile($url, $new_audio_file){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $data = curl_exec($ch);

        curl_close($ch);

        file_put_contents($new_audio_file, $data);
    }

    public function getMimeType($filename)
    {
        $mimetype = false;
        if(function_exists('finfo_fopen')) {
            // open with FileInfo
        } elseif(function_exists('getimagesize')) {
            // open with GD
        } elseif(function_exists('exif_imagetype')) {
            // open with EXIF
        } elseif(function_exists('mime_content_type')) {
            $mimetype = mime_content_type($filename);
        }
        return $mimetype;
    }
}
