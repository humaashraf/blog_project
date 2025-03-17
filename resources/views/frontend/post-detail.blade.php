@extends('frontend.layouts.app')
@section('content')

<!-- post detail start -->
<div class="about_section layout_padding mb-5">
    <div class="container">
       <div class="row">
          <div class="col-lg-8 col-sm-12">
             <div class="about_img">
                <img src="{{ asset('uploads/' . $post->image) }}" alt="{{ $post->title }}">
             </div>
             <div class="like_icon">
                <img src="{{ asset('assets/images/like-icon.png') }}">
             </div>
             <p class="post_text">Post By: {{ $post->created_at->format('d/m/Y') }}</p>
             <h2 class="most_text">{{ $post->title }}</h2>
             <p class="lorem_text">{{ $post->description }}</p>
             <div class="social_icon_main">
                <div class="social_icon">
                   <ul>
                      <li><a href="#"><img src="{{ asset('assets/images/fb-icon.png') }}"></a></li>
                      <li><a href="#"><img src="{{ asset('assets/images/twitter-icon.png') }}"></a></li>
                      <li><a href="#"><img src="{{ asset('assets/images/instagram-icon.png') }}"></a></li>
                   </ul>
                </div>
             </div>
             

<!-- Comments Section -->
<div class="comments_section mt-5">
    @if(session('login_error'))
<div class="alert alert-danger">{{ session('login_error') }}</div>
@endif

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
    <h3>Comments</h3>
    @foreach($post->comments->where('is_approved', 1)->whereNotNull('published_at') as $comment)
        <div class="comment_box mb-3 p-3 border rounded">
            <p class="mb-1"><strong>Comment:</strong> {{ $comment->comment }}</p>
            <small class="text-muted">Posted on: {{ $comment->published_at->format('d/m/Y') }}</small>
        </div>
    @endforeach
</div>

<!-- Add Comment Form -->
<div class="add_comment_section mt-4">
    <h4>Leave a Comment</h4>
    <form action="{{ route('comments.store') }}" method="POST" id="commentForm">
        @csrf
        <input type="hidden" name="post_id" value="{{ $post->id }}">
        <div class="mb-3">
            <label for="comment" class="form-label">Your Comment</label>
            <textarea name="comment" id="comment" class="form-control" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<!-- JavaScript to Show Login Message Without Redirect -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        @if(session('login_error'))
            alert("{{ session('login_error') }}");
        @endif
    });
</script>

          </div>
          <div class="col-lg-4 col-sm-12">
             <div class="about_main">
                <h1 class="follow_text">CONNECT & FOLLOW</h1>
                <div class="follow_icon">
                   <ul>
                      <li><a href="#"><img src="{{ asset('assets/images/fb-icon-1.png') }}"></a></li>
                      <li><a href="#"><img src="{{ asset('assets/images/twitter-icon-1.png') }}"></a></li>
                      <li><a href="#"><img src="{{ asset('assets/images/linkedin-icon-1.png') }}"></a></li>
                      <li><a href="#"><img src="{{ asset('assets/images/instagram-icon-1.png') }}"></a></li>
                   </ul>
                </div>
             </div>
          </div>
       </div>
    </div>
</div>
<!-- post detail section end -->

@endsection
