@extends('layouts.app')
@section('content')
    <div class="myContainer">
        <div class="basketContainer">
            <h1>YOUR BASKET</h1>
            @foreach($goodsInBasket as $basket)
                <div class="basketGood">
                    <div class="imageContainer">
                        <img src="{{URL('images/'.str_replace(':', '-', $basket->good->updated_at).' '.json_decode($basket->good->images)[0])}}" alt="">
                    </div>
                    <div class="title">
                        <p>{{$basket->good->title}}</p>
                    </div>
                    <div class="price">
                        <p>{{$basket->good->price}}</p>
                        <img src="{{URL('icons/tenge.svg')}}" alt="">
                    </div>
                </div>
            @endforeach
            <div class="totalPrice">
                <h2>TOTAL: </h2>
                <p>{{$totalPrice}}</p>
                <img src="{{URL('icons/tenge.svg')}}" alt="">
            </div>
            <div class="actions">
                <a href="{{route('orders.create')}}"><button>Make order</button></a>
            </div>
        </div>
    </div>
@endsection
