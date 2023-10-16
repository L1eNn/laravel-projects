@extends('layouts.app')
@section('content')

    <div class="orderCreateContainer">
        <div class="orderCreateForm">
            <h1>Order create</h1>
            <form action="{{route('orders.store')}}" method="POST">
                @csrf
                <input name="address" type="text" placeholder="Type here your delivery address" required>
                <input name="first_name" type="text" placeholder="First name" required>
                <input name="second_name" type="text" placeholder="Second name" required>
                <input name="third_name" type="text" placeholder="Third name" required>
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>

@endsection
