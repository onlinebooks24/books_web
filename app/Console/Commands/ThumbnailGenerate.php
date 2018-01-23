<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Article;
use App\User;
use App\Models\Upload;
use Carbon\Carbon;
use Intervention\Image\ImageManagerStatic as Image;

class ThumbnailGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'thumbnail:generate';

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
        $articles = Article::all();
        foreach($articles as $article){
            if(isset($article->thumbnail_image)){
                $folder_path = $article->thumbnail_image->folder_path;
                $filename = $article->thumbnail_image->name;
                $thumb_filename = 'obr_thumb_250_250_' . $filename;

                try
                {
                    $image = Image::make(public_path($folder_path . $filename));
                }
                catch(\Exception $e)
                {
                    // If error, stop and continue looping to next iteration
                    continue;
                }
                // If no error ...
                $image->resize(250, 250, function ($constraint)
                {
                    $constraint->aspectRatio();
                })->save(public_path($folder_path . $thumb_filename));

                $thumb_md5 = md5_file(public_path($folder_path . $thumb_filename));
                $image_exist = Upload::where('md5_hash', $thumb_md5)->first();

                if(empty($image_exist)){
                    $upload = new Upload();
                    $upload->name = $thumb_filename;
                    $upload->folder_path = $folder_path;
                    $upload->md5_hash = $thumb_md5;
                    $upload->article_id = $article->id;
                    $upload->save();
                }
            }
        }
    }
}
