<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VideosTemplate;
use Session;

class AdminVideosTemplatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $videos_templates = VideosTemplate::paginate(10);
        return view('admin.videos_templates.index', compact('videos_templates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.videos_templates.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $videos_template = new VideosTemplate();
        $template_name = strtolower(str_replace(' ', '_', $request->template_name));
        $videos_template->template_name = $template_name;
        $videos_template->book_title_html = $request->book_title_html;
        $videos_template->book_image_html = $request->book_image_html;
        $videos_template->book_description_html = $request->book_description_html;
        $videos_template->book_conclusion_html = $request->book_conclusion_html;

        $audio_file = $request->file('audio_name');
        if(!empty($audio_file)){
            $audio_name = str_replace(' ', '_', $audio_file->getClientOriginalName());
            $audio_file->move(public_path('uploads/videos/templates/'. $template_name ), $audio_name);
            $videos_template->audio_name = $audio_name;
        }

        $background_image_file = $request->file('background_image');
        if(!empty($background_image_file)){
            $background_image = str_replace(' ', '_', $background_image_file->getClientOriginalName());;
            $background_image_file->move(public_path('uploads/videos/templates/'. $template_name ), $background_image);
            $videos_template->background_image = $background_image;
        }
        $videos_template->save();

        $flash_message = 'Successfully Saved';
        Session::flash('message', $flash_message);

        return redirect()->to(route('admin_videos_templates.index'));
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
        $videos_template = VideosTemplate::find($id);
        return view('admin.videos_templates.edit', compact('videos_template'));
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
        $videos_template = VideosTemplate::find($id);
        $template_name = strtolower(str_replace(' ', '_', $request->template_name));
        $videos_template->template_name = $template_name;
        $videos_template->book_title_html = $request->book_title_html;
        $videos_template->book_image_html = $request->book_image_html;
        $videos_template->book_description_html = $request->book_description_html;
        $videos_template->book_conclusion_html = $request->book_conclusion_html;

        $audio_file = $request->file('audio_name');
        if(!empty($audio_file)){
            $audio_name = str_replace(' ', '_', $audio_file->getClientOriginalName());
            $audio_file->move(public_path('uploads/videos/templates/'. $template_name ), $audio_name);
            $videos_template->audio_name = $audio_name;
        }

        $background_image_file = $request->file('background_image');
        if(!empty($background_image_file)){
            $background_image = str_replace(' ', '_', $background_image_file->getClientOriginalName());;
            $background_image_file->move(public_path('uploads/videos/templates/'. $template_name ), $background_image);
            $videos_template->background_image = $background_image;
        }
        $videos_template->save();

        $flash_message = 'Successfully Updated';
        Session::flash('message', $flash_message);

        return redirect()->to(route('admin_videos_templates.index'));
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
}
