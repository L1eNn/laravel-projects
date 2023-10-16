@extends('layouts.app')
@section('content')
    <div class="myContainer">
        <form class="goodsCreateForm" action="{{route('goods.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <h1>GOOD CREATE</h1>
            <input name="title" type="text" placeholder="Type here title of your good" required>
            <textarea name="description" placeholder="Type here description" required minlength="100"></textarea>
            <div class="imagesContainer">
                <label>
                    <img src="{{URL('icons/add-image.svg')}}" alt="">
                    <input class="imageInput" name="image_1" id="firstImage" type="file" accept="image/png, image/jpeg">
                </label>
                <label>
                    <img src="{{URL('icons/add-image.svg')}}" alt="">
                    <input class="imageInput" name="image_2" id="secondImage" type="file" accept="image/png, image/jpeg">
                </label>
                <label>
                    <img src="{{URL('icons/add-image.svg')}}" alt="">
                    <input class="imageInput" name="image_3" id="thirdImage" type="file" accept="image/png, image/jpeg">
                </label>
                <label>
                    <img src="{{URL('icons/add-image.svg')}}" alt="">
                    <input class="imageInput" name="image_4" id="fourthImage" type="file" accept="image/png, image/jpeg">
                </label>
                <label>
                    <img src="{{URL('icons/add-image.svg')}}" alt="">
                    <input class="imageInput" name="image_5" id="fifthImage" type="file" accept="image/png, image/jpeg">
                </label>
            </div>
            <input name="price" type="number" placeholder="Price" required>
            <button onclick=" event.preventDefault()
            let imageAmount = 0
            document.querySelectorAll('.imageInput').forEach(input => {
                if (input.files.length !== 0) imageAmount++
            })
            if (imageAmount !== 0) {
                document.querySelector('.goodsCreateForm').submit()
            } else {
                alert('Image has to be added')
            }">CREATE</button>
        </form>
    </div>

@endsection
