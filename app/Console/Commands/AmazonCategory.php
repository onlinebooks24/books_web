<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\Helper;
use App\Models\Category;


class AmazonCategory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'amazon:category';

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
        $node_list =  [4366];

        while(!empty($node_list)){
            foreach($node_list as $node_item){
                $now_parent_is = $node_item;

                $search_query = [
                    'Operation' => 'BrowseNodeLookup',
                    'BrowseNodeId' => $now_parent_is,
                ];

                $amazon_response = Helper::amazonAdAPI($search_query);
                $browse_node = $amazon_response['BrowseNodes']['BrowseNode'];
                $this->saveCategory($browse_node['Name'], $browse_node['BrowseNodeId'], '1', $browse_node['Ancestors']['BrowseNode']['BrowseNodeId']);


                if(isset($browse_node['Children']['BrowseNode'])){
                    foreach($browse_node['Children']['BrowseNode'] as $key => $children_item){
                        if(isset($children_item['Name'])){
                            $this->saveCategory($children_item['Name'], $children_item['BrowseNodeId'], ++$key , $now_parent_is);
                            array_push( $node_list, $children_item['BrowseNodeId']);
                        } else {
                            \Log::info("----------------------");
                            \Log::info("----------------------");
                            \Log::info("----------------------");
                            \Log::info($children_item);
                            \Log::info("----------------------");
                            \Log::info("----------------------");
                            \Log::info("----------------------");
                            \Log::info("----------------------");
                            \Log::info("----------------------");

                        }

                    }
                }

                if (($key = array_search($node_item, $node_list)) !== false) {
                    unset($node_list[$key]);
                }

                \Log::info($node_list);
            }
            sleep(1);
        }

    }

    public function saveCategory($category_name, $browse_node_id, $order_id , $now_parent_is){
        $node_check = Category::where('browse_node_id' ,  $browse_node_id)->first();

        if(empty($node_check)){
            $slug = strtolower($category_name);
            $slug = str_replace('&', 'and', $slug);
            $slug = str_replace(' ', '-', $slug);
            $slug_check = Category::where('slug' ,  $slug)->get();
            $slug_check_count = count($slug_check);
            if($slug_check_count > 0){
                $collect_parent = Category::where('browse_node_id', $now_parent_is)->first();
                if(empty($collect_parent)){
                    $slug = $slug. '-' . rand(10, 99);
                } else {
                    $slug = $slug. '-' . $collect_parent->slug;
                    $category_name = $category_name. '(' . $collect_parent->name . ')';
                }
            }

            $category_db = new Category();
            $category_db->browse_node_id = $browse_node_id;
            $category_db->name = $category_name;
            $category_db->slug = $slug;
            $category_db->order_id = $order_id;
            $category_db->parent_id = $now_parent_is;
            $category_db->save();
            \Log::info("----------------------. $category_db->name");
        }
    }
}
