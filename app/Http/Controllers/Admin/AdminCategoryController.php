<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class AdminCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::orderBy('created_at','desc')->get();
        return view('admin.category',['categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request , [
            'name' => 'required|unique:categories'
        ]);
        $category = new Category();
        $category->name = strtolower($request['name']);
        $slug = strtolower($request['name']);
        $slug = str_replace(' ', '-', $slug); 
        $category->slug = $slug ; 
        $category->parent_id = 1000 ;
        $category->order_id = 1 ;
        $category->save();

        return redirect()->back()->with(['success' => 'Category Added Successfully']);

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
        $category = Category::find($id);
        $category->name = strtolower($request['name']);
        $slug = strtolower($request['name']);
        $slug = str_replace(' ', '-', $slug); 
        $category->slug = $slug ;
        $category->parent_id = 1000 ;
        $category->order_id = 1 ;
        $category->update();

        return redirect()->back()->with(['success' => 'Category Updated Successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        if(!$category){
            return redirect()->route('category.index')->with(['fail' => 'Page not found !']);
        }
        $category->delete();
        return redirect()->route('category.index')->with(['success' => 'Category Deleted Successfully.']);
    }
}
