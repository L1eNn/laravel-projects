@extends('layouts.app')

@section('content')
    @vite(['resources/js/script.js'])
    <div class="myContainer">
        <div class="adminPanel">
            <div class="adminProfile">
                <h1>{{ strtoupper($user->name) }}</h1>
                <div class="adminNav">
                    <a href="{{ route('admin.orders') }}">Orders</a>
                </div>
            </div>
            <div class="goods">
                <h2>GOODS</h2>
                <a href="{{route('goods.create')}}"><button class="goodCreateButton">New good</button></a>
                @foreach($goods as $key => $good)
                    <div class="good">
                        <div class="topBlock">
                            <p>{{ $good->title }}</p>
                            <div class="more">
                                <div class="actions">
                                    <a href="{{ route('goods.edit', parameters: ['good' => $good]) }}"><img id="editButton" src="{{ URL('icons/pen.svg') }}" alt=""></a>
                                    <img id="deleteButton" src="{{ URL('icons/trash-can.svg') }}" alt="" onclick="if (confirm('Are you sure to delete post?')) {
                                        document.getElementById('delete').submit()
                                    }">
                                    <form action="{{route("goods.destroy", parameters: ['good' => $good->id])}}" method="post" id="delete">
                                        @csrf
                                        {{method_field('DELETE')}}
                                    </form>
                                </div>
                                <img id="moreButton" src="{{URL('icons/more.svg')}}" alt="">
                            </div>
                        </div>
                        <div class="middleBlock">
                            <div class="imagesContainer">
                                <div class="cover">
                                    @if(json_decode($good->images))
                                        <img class="coverImage" src="{{URL('images/'.str_replace(':', '-', $good->updated_at).' '.json_decode($good->images)[0])}}" alt="">
                                    @endif
                                </div>
                                <div class="otherImages">
                                    @if(json_decode($good->images))
                                        @foreach(json_decode($good->images) as $image)
                                            <img class="images" src="{{URL('images/'.str_replace(':', '-', $good->updated_at).' '.$image )}}" alt="">
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="description">
                                <h4>Description</h4>
                                <p>{{ $good->description }}</p>
                            </div>
                        </div>
                        <div class="bottomBlock">
                            <div class="price">
                                <p>{{ $good->price }}</p>
                                <img src="{{ URL('icons/tenge.svg') }}" alt="">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection
