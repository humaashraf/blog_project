@extends('frontend.layouts.app')

@section('content')
<div class="container py-5">
    {{-- <h1 class="text-center font-weight-bold">Posts with Tag: {{ $tag->name }}</h1>
     --}}
     <h1 class="text-center my-5" style="font-weight: 600;">Posts with Tag: {{ $tag->name }}</h1>


    @if($posts->count() > 0)
        <div class="row">
            @foreach($posts as $post)
                <div class="col-lg-8 col-sm-12">
                    <div class="about_img">
                        <img src="{{ asset('uploads/' . $post->image) }}" alt="{{ $post->title }}">
                    </div>
                    <div class="like_icon"><img src="{{ asset('assets/images/like-icon.png') }}"></div>
                    <p class="post_text">Post By: {{ \Carbon\Carbon::parse($post->published_at)->format('d/m/Y') }}</p>
                    <h2 class="most_text">{{ $post->title }}</h2>
                    <p class="lorem_text">{{ Str::limit($post->description, 150) }}</p>
                    <div class="social_icon_main">
                        <div class="social_icon"></div>
                        <div class="read_bt mb-5 pb-5">
                            <a href="{{ route('post.show', ['slug' => Str::slug($post->title), 'id' => $post->id]) }}">Read More</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    @else
        <p class="text-center text-muted">No posts found for this tag.</p>
    @endif
</div>
@endsection
