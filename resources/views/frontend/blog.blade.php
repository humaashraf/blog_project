@extends('frontend.layouts.app')

@section('content')

<!-- Blog section start -->
<div class="about_section layout_padding mb-5">
    <div class="container">
       <div class="row">
          <div class="col-lg-8 col-sm-12">
             @if($recentPosts->count())
                 @foreach($recentPosts as $post)
                 <a href="{{ route('blog.show', ['slug' => $post->slug, 'id' => $post->id]) }}">
                     <div class="about_img">
                         <img src="{{ asset('uploads/' . $post->image) }}" alt="{{ $post->title }}">
                     </div>
                     <div class="like_icon"><img src="assets/images/like-icon.png"></div>
                     <p class="post_text">Post By : {{ $post->published_at->format('d/m/Y') }}</p>
                     <h2 class="most_text">{{ $post->title }}</h2>
                     <p class="lorem_text">{{ Str::limit($post->description, 200) }}</p></a>
                     <div class="social_icon_main">
                        <div class="social_icon"></div>
                        <div class="read_bt">
                            <a href="{{ route('blog.show', ['slug' => $post->slug, 'id' => $post->id]) }}">Read More</a>
                        </div>
                     </div>
                     <hr> <!-- Divider between posts -->
                 @endforeach
             @else
                 <p>No recent posts available.</p>
             @endif
          </div>

          <div class="col-lg-4 col-sm-12">
             <div class="about_main">
                <h1 class="follow_text">CONNECT & FOLLOW</h1>
                <div class="follow_icon">
                   <ul>
                      <li><a href="#"><img src="assets/images/fb-icon-1.png"></a></li>
                      <li><a href="#"><img src="assets/images/twitter-icon-1.png"></a></li>
                      <li><a href="#"><img src="assets/images/linkedin-icon-1.png"></a></li>
                      <li><a href="#"><img src="assets/images/instagram-icon-1.png"></a></li>
                   </ul>
                </div>
             </div>
          </div>
       </div>
    </div>
</div>
<!-- Blog section end -->

@endsection
