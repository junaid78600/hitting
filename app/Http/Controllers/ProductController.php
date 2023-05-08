<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $content = 'admin/product/index'; 
        $title = 'Product';
        $data = Product::with('category')->orderByDesc('id')->get();
        $category = Category::orderByDesc('id')->get();
        return view('admin/master')->with([
                    'content'=>$content,
                    'title'=>$title,
                    'data'=>$data,
                    'category'=>$category,
                ]);
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

        $file_name='';

        if ($request->hasFile('image')) {
                  
            $file = $request->file('image');

            $name = $file->getClientOriginalName().'.'.$file->getClientOriginalExtension();

            $image['filePath'] = $name;

            $file_name = time().mt_rand(1,99999).'.'.$file->getClientOriginalExtension();;

            $file->move(public_path().'/product/', $file_name);
        }

        Product::create([
            'categoryId'=>$request->categoryId,
            'title'=>$request->title,
            'price'=>$request->price,
            'description'=>$request->description,
            'image'=>$file_name,
            'date' => date("d-M-Y"),
            'time' => date("h:i:s a"),
        ]);

        return redirect()->route('admin.product.index');
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
        $data = Product::find($id);
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
        $product = Product::findOrFail($request->id);

        $product->title = ($request->title?$request->title:$product->title);
        $product->price = ($request->price?$request->price:$product->price);
        $product->description = ($request->description?$request->description:$product->description);

        if ($request->hasFile('image')) {

            $url =$product->image;
            $cleanedURL = basename($url);
            if($cleanedURL){
                unlink('product/'.$cleanedURL);
            }

            $file = $request->file('image');

            $name = $file->getClientOriginalName().'.'.$file->getClientOriginalExtension();

            $image['filePath'] = $name;

            $file_name = time().mt_rand(1,99999).'.'.$file->getClientOriginalExtension();;

            $file->move(public_path().'/product/', $file_name);
            $product->image = $file_name;
        }
        else{
            $url =$product->image;
            $cleanedURL = basename($url);
            $product->image = $cleanedURL;
        }

        $product->save();

        return redirect()->route('admin.product.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $url =$product->image;

        $cleanedURL = basename($url);
        
        if($cleanedURL){
                        
            unlink('product/'.$cleanedURL);
        }

        $product->delete();
        
        return redirect()->route('admin.product.index');
    }
}
