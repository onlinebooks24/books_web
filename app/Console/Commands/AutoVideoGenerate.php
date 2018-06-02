<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;

class AutoVideoGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto_video_generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $article_id = 169;

        $article = Article::find($article_id);

        $image_path_1 = 'public/uploads/videos/generate_assets/frame_1/step_1.jpg';
        $image_path_2 = 'public/uploads/videos/generate_assets/frame_1/step_2.jpg';
        $image_path_3 = 'public/uploads/videos/generate_assets/frame_1/step_3.jpg';
        $image_path_4 = 'public/uploads/videos/generate_assets/frame_1/step_4.jpg';
        $audio_path = 'public/uploads/videos/generate_assets/audio/audio_1.mp3';

        $video_output_path = "public/uploads/videos/generate_output/$article->slug.mp4";

        $command = "ffmpeg -framerate 1 -pattern_type glob -i 'public/uploads/videos/generate_assets/frame_1/*.jpg' -c:v libx264 -r 300 -pix_fmt yuv420p $video_output_path";
//                    " && ffmpeg -i \"$video_output_path\" -i \"$audio_path\" -shortest $video_output_path";
        dd(exec($command));
    }

}
