@extends('layouts.app')
@section('content')

    <div class="myContainer">
        <div class="ordersContainer">
            <h1>ORDERS</h1>
            <div class="orders">
                @foreach($orders as $order)
                    <div class="order">
                        <div class="leftBlock">
                            <div class="imageContainer">
                                <img src="{{URL('images/'.str_replace(':', '-', $order->good->updated_at).' '.json_decode($order->good->images)[0])}}" alt="">
                            </div>
                        </div>
                        <div class="rightBlock">
                            <div class="number">
                                <p>Number: </p>
                                <p class="value">{{ $order->id }}</p>
                            </div>
                            <div class="title">
                                <p>Good name: </p>
                                <p class="value">{{ $order->good->title }}</p>
                            </div>
                            <div class="address">
                                <p>Address: </p>
                                <p class="value">{{ $order->address }}</p>
                            </div>
                            <div class="customerInfo">
                                <p>First name: </p>
                                <p class="value">{{ $order->first_name }}</p>
                                <p>Second name: </p>
                                <p class="value">{{ $order->second_name }}</p>
                                <p>Third name: </p>
                                <p class="value">{{ $order->third_name }}</p>
                            </div>
                            <div class="orderStatus">
                                <p>Status:</p>
                                <form id="statusUpdate" action="{{route('orders.update', parameters: ['order' => $order->id])}}" method="POST">
                                    {{method_field('PATCH')}}
                                    @csrf
                                    <input name="status" class="value" type="text" value="{{ $order->status }}">
                                </form>
                            </div>
                        </div>
                        <button onclick="event.preventDefault()
                        document.getElementById('statusUpdate').submit()">Update status</button>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
