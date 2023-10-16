@extends('layouts.app')

@section('content')

    <div class="myContainer">
        <div class="goods">
            @foreach($goods as $good)
                <div class="goodsContainer">
                    <div class="good">
                        <div class="title"><p>{{ $good->title }}</p></div>
                        <div class="imageContainer">
                            <img src="{{URL('images/'.str_replace(':', '-', $good->updated_at).' '.json_decode($good->images)[0] )}}" alt="">
                        </div>
                        <div class="price">
                            <p>{{ $good->price }}</p>
                            <img src="{{URL('icons/tenge.svg')}}" alt="">
                        </div>
                    </div>
                    <div class="actions">
                        <button onclick="event.preventDefault()
                        document.getElementById('addToBasket_{{$good->id}}').submit()">Add to basket</button>
                        <form id="addToBasket_{{$good->id}}" action="{{route('baskets.store')}}" method="POST">
                            @csrf
                            <input name="good_id" type="hidden" value="{{$good->id}}">
                            <input name="goodId" type="hidden" value="5">
                        </form>
                    </div>
                </div>

            @endforeach
        </div>
    </div>

@endsection
