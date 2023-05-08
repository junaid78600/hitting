<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Validator;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $content = 'admin/category/index'; 
        $title = 'Category';
        $data = Category::orderByDesc('id')->get();
        return view('admin/master')->with([
                    'content'=>$content,
                    'title'=>$title,
                    'data'=>$data
                ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        dd('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $file_name='';

        if ($request->hasFile('image')) {
                  
            $file = $request->file('image');

            $name = $file->getClientOriginalName().'.'.$file->getClientOriginalExtension();

            $image['filePath'] = $name;

            $file_name = time().mt_rand(1,99999).'.'.$file->getClientOriginalExtension();;

            $file->move(public_path().'/category/', $file_name);
        }

        Category::create([
            'name'=>$request->name,
            'image'=>$file_name,
        ]);

        return redirect()->route('admin.category.index');
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
        $data = Category::find($id);
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $category = Category::findOrFail($request->id);

        $category->name = $request->name;

        if ($request->hasFile('image')) {

            $url =$category->image;
            $cleanedURL = basename($url);
            if($cleanedURL){
                unlink('category/'.$cleanedURL);
            }

            $file = $request->file('image');

            $name = $file->getClientOriginalName().'.'.$file->getClientOriginalExtension();

            $image['filePath'] = $name;

            $file_name = time().mt_rand(1,99999).'.'.$file->getClientOriginalExtension();;

            $file->move(public_path().'/category/', $file_name);
            $category->image = $file_name;
        }

        $category->save();

        return redirect()->route('admin.category.index');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $url =$category->image;

        $cleanedURL = basename($url);
        
        if($cleanedURL){
                        
            unlink('category/'.$cleanedURL);
        }

        $category->delete();
        
        return redirect()->route('admin.category.index');
    }

}
