@extends('frontend.layouts.app')
@section('content')

<!-- about section start -->
<div class="about_section layout_padding mb-5">
    <div class="container">
       <div class="row">
        @if($firstPost)
        <div class="col-lg-8 col-sm-12">
            <a href="{{ route('post.show', ['slug' => Str::slug($firstPost->title), 'id' => $firstPost->id]) }}">
            <div class="about_img">
                <img src="{{ asset('uploads/' . $firstPost->image) }}" alt="{{ $firstPost->title }}">
            </div>
            <div class="like_icon"><img src="{{ asset('assets/images/like-icon.png') }}"></div>
            <p class="post_text">Post By: {{ \Carbon\Carbon::parse($firstPost->published_at)->format('d/m/Y') }}</p>
            <h2 class="most_text">{{ $firstPost->title }}</h2>
            <p class="lorem_text">{{ Str::limit($firstPost->description, 150) }}</p></a>
            <div class="social_icon_main">
                <div class="social_icon"></div>

                <div class="read_bt">
                    <a href="{{ route('post.show', ['slug' => Str::slug($firstPost->title), 'id' => $firstPost->id]) }}">Read More</a>
                </div>

            </div>
        </div>
        @endif

          <div class="col-lg-4 col-sm-12">
            <div class="image_5">
                <img src="{{ asset('uploads/' . $about->image) }}" alt="About Image">
            </div>
            <h1 class="about_taital">{{ $about->name }}</h1>
            <p class="about_text">{{ $about->description }}</p>
            {{-- <div class="read_bt_1">
                <a href="{{ url('/about') }}">Read More</a>
            </div> --}}
        </div>

       </div>
    </div>
 </div>
 <!-- about section end -->

@endsection
