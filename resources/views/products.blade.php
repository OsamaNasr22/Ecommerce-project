@extends('layout')

@section('title', 'Products')

@section('extra-css')

@endsection

@section('content')

    <div class="breadcrumbs">
        <div class="container">
            <a href="{{route('landing-page')}}">Home</a>
            <i class="fa fa-chevron-right breadcrumb-separator"></i>
            <span>Shop</span>
        </div>
    </div> <!-- end breadcrumbs -->

    <div class="products-section container">
        <div class="sidebar">
            <h3>By Category</h3>
            <ul>
            @foreach($categories as $category)
                    <li class="{{setActive($category_name,$category->name)}}"><a href="{{route('shop.index',['category'=>$category->slug])}}" >{{$category->name}}</a></li>
                @endforeach
            </ul>

        </div> <!-- end sidebar -->
        <div>
            <div class="products-header">
                <h1 class="stylish-heading">
                    {{$category_name}}
                </h1>
                @if($category_name != 'Featured')
                    <div>
                        <strong>Price: </strong>
                        <a href="{{ route('shop.index', ['category'=> $slug, 'sort' => 'asc']) }}">Low to High</a> |
                        <a href="{{ route('shop.index', ['category'=> $slug, 'sort' => 'desc']) }}">High to Low</a>
                    </div>
                @endif

            </div>
            <div class="products text-center">
                @foreach($products as $product)
                    <div class="product">
                        <a href="{{route('shop.show',$product->slug)}}"><img src="{{imagePath($product->image)}}" alt="product"></a>
                        <a href="{{route('shop.show',$product->slug)}}"><div class="product-name">{{$product->name}}</div></a>
                        <div class="product-price">{{$product->presentPrice()}}</div>
                    </div>
                    @endforeach
            </div> <!-- end products -->
            <div class="spacer"></div>
            {{ $products->links() }}
        </div>
    </div>


@endsection
