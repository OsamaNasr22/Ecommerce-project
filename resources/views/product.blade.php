@extends('layout')

@section('title', 'Product')

@section('extra-css')

@endsection

@section('content')

    @component('components.breadcrumbs')
        <a href="/">Home</a>
        <i class="fa fa-chevron-right breadcrumb-separator"></i>
        <span><a href="{{ route('shop.index') }}">Shop</a></span>
        <i class="fa fa-chevron-right breadcrumb-separator"></i>
        <span>{{ $product->name }}</span>
    @endcomponent

    <div class="container">
        @if(count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{$error}}</li>
                    @endforeach
                </ul>
            </div>

        @endif

        @if(session('success'))
            <div class="alert alert-success">
                <p>{{session('success')}}</p>
            </div>
        @endif
    </div>
    <div class="product-section container">
        <div>

            <div class="product-section-image">
                <img src="{{ imagePath($product->image) }}" alt="product" class="active" id="currentImage">
            </div>

            <div class="product-section-images">
                <div class="product-section-thumbnail selected">
                    <img src="{{ imagePath($product->image) }}" alt="product">
                </div>
                @if ($product->images)
                    @foreach (json_decode($product->images, true) as $image)
                        <div class="product-section-thumbnail">
                            <img src="{{ imagePath($image) }}" alt="product">
                        </div>
                    @endforeach
                @endif
        </div>
        </div>
        <div class="product-section-information">
            <h1 class="product-section-title">{{$product->name}}</h1>
            <div class="product-section-subtitle">{{$product->details}}</div>
            <div class="product-section-price">{{$product->presentPrice()}}</div>
            <p>
                {{$product->description}}
            </p>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptas magni accusantium, sapiente dicta iusto ut dignissimos atque placeat tempora iste.</p>

            <p>&nbsp;</p>

            <!--<a href="#" class="button">Add to Cart</a>-->
            <form action="{{route('cart.store',$product->id)}}" method="post">
                {{csrf_field()}}
               <button type="submit" class="button button-plain">Add to Cart</button>
            </form>
        </div>
    </div> <!-- end product-section -->

    @include('partials.might-like')


@endsection

@section('extra-js')

    <script>
        (function () {
            const currentImage= document.querySelector('#currentImage');
            const images = document.querySelectorAll('.product-section-thumbnail');

            images.forEach((image) =>image.addEventListener('click',changeCurrent));
            function changeCurrent(e) {
               let image= this.querySelector('img');
                currentImage.classList.remove('active');
                currentImage.addEventListener('transitionend',function () {
                    currentImage.src = image.src;
                    currentImage.classList.add('active');
                });
                images.forEach((image) => image.classList.remove('selected'));
                this.classList.add('selected');
            }



        })();


    </script>

    @endsection