<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($category=null,$sort=null)
    {
        //


        $categories= Category::all();
        $pagination= 9;
        if (isset($category) ){

            if(!Category::where('slug',$category)->first()) return redirect()->route('shop.index');
            $products = Product::with('categories')->whereHas('categories',function ($query) use ($category){
                $query->where('slug',$category);
            });
            $categoryName= optional($categories->where('slug',$category)->first())->name;
            $slug= optional($categories->where('slug',$category)->first())->slug;
            if (isset($sort)){
                if($sort === 'asc'){
                    $products= $products->orderBy('price',$sort)->take(12)->inRandomOrder()->paginate($pagination);

                }elseif ($sort === 'desc'){
                    $products= $products->orderBy('price',$sort)->take(12)->inRandomOrder()->paginate($pagination);

                }else{
                    return redirect()->route('shop.index');
                }

            }else{
                $products= $products->take(12)->inRandomOrder()->paginate($pagination);
            }
        }else{

            $products = Product::where('featured',true)->take(12)->inRandomOrder()->paginate($pagination);
            $categoryName=$slug='Featured';


        }





      //  $products= Product::inRandomOrder()->take(12)->get();
        return view('products')->with([
            'products'=>$products,
            'categories'=>$categories,
            'category_name'=>$categoryName,
            'slug'=>$slug
        ]);
    }


    public function show($slug)
    {
        //
        $product= Product::where('slug',$slug)->firstOrFail();
        $suggestions= Product::where('slug','!=',$slug)->Suggestions()->get();
        return view('product')->with([
            'product'=>$product,
            'suggestions'=>$suggestions,

        ]);

    }

}
