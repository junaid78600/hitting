<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;

class HomeController extends Controller
{
    public function home(Request $request)
    {
        $content = 'admin/dashboard/index';
        $pageTitle = 'Dashboard';
        $product = Product::count();
        $user = User::count();
        $category = Category::count();
        return view('admin/master')->with([
            'content'=>$content,
            'product'=>$product,
            'user'=>$user,
            'category'=>$category,
            'title'=>$pageTitle
        ]);
        
    }
}
