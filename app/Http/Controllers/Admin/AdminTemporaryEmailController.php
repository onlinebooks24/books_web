<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class AdminTemporaryEmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $access_token = env('githubToken');
        $client = new \GuzzleHttp\Client();
        $email = [];

        $search_url = $client->get('https://api.github.com/search/code?per_page=5&order=desc&q=tensorflow&sort=indexed&access_token='. $access_token);
        $event_json =  json_decode($search_url->getBody());

        foreach($event_json->items as $search_item) {
            $username = $search_item->repository->owner->login;
            $event_url = $client->get('https://api.github.com/users/'. $username .'/events/public?access_token='. $access_token);
            $event_json =  json_decode($event_url->getBody());

            foreach($event_json as $item){
                if(isset($item->payload->commits[0])){
                    $collect_email = $item->payload->commits[0]->author->email;

                    if (filter_var($collect_email, FILTER_VALIDATE_EMAIL) && strpos($collect_email, 'noreply') == false && strpos($collect_email, 'local') == false){
                        $email[] = $collect_email;
                    }
                }
            }
            sleep(1);
        };

        dd(array_unique($email));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

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
    public function update()
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

}
