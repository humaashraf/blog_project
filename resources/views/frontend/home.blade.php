
@extends('frontend.layouts.app')
@section('content')

        <!-- banner section start -->
<div class="container-fluid">
    <div class="banner_section layout_padding">
        <h1 class="banner_taital">Welcome <br>Our Blog Nest</h1>
        <div id="my_slider" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                @foreach($sliders as $key => $slider)
                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                    <div class="image_main">
                        <div class="container">
                            <img src="{{ asset('uploads/' . $slider->slider_image) }}" class="image_1" alt="Slider Image">
                        </div>
                        <div class="contact_bt"><a href="{{ url('contact') }}">Contact Us</a></div>
                    </div>
                </div>
                @endforeach
            </div>
            <a class="carousel-control-prev" href="#my_slider" role="button" data-slide="prev">
                <i class="fa fa-angle-left"></i>
            </a>
            <a class="carousel-control-next" href="#my_slider" role="button" data-slide="next">
                <i class="fa fa-angle-right"></i>
            </a>
        </div>
    </div>
</div>
<!-- banner section end -->

         <!-- about section start -->
         <div class="about_section layout_padding">
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

@php
    use Illuminate\Support\Str;
@endphp

<div class="col-lg-4 col-sm-12">
    <div class="image_5">
        <img src="{{ asset('uploads/' . $about->image) }}" alt="About Image">
    </div>
    <h1 class="about_taital">{{ $about->name }}</h1>
    <p class="about_text">{{ Str::limit($about->description, 150, '...') }}</p>
    <div class="read_bt_1">
        <a href="{{ url('/about') }}">Read More</a>
    </div>
</div>


               </div>
            </div>
         </div>
         <!-- about section end -->

         <!-- blog section start -->
         <div class="about_section layout_padding">
            <div class="container">
               <div class="row">
                @if($thirdLastPost)
                <div class="col-lg-8 col-sm-12">
                    <a href="{{ route('post.show', ['slug' => Str::slug($secondLastPost->title), 'id' => $secondLastPost->id]) }}">
                    <div class="about_img">
                        <img src="{{ asset('uploads/' . $secondLastPost->image) }}" alt="{{ $secondLastPost->title }}">
                    </div>
                    <div class="like_icon"><img src="{{ asset('assets/images/like-icon.png') }}"></div>
                    <p class="post_text">Post By: {{ \Carbon\Carbon::parse($secondLastPost->published_at)->format('d/m/Y') }}</p>
                    <h2 class="most_text">{{ $secondLastPost->title }}</h2>
                    <p class="lorem_text">{{ Str::limit($secondLastPost->description, 150) }}</p></a>
                    <div class="social_icon_main">
                        <div class="social_icon"></div>

                        <div class="read_bt">
                            <a href="{{ route('post.show', ['slug' => Str::slug($secondLastPost->title), 'id' => $secondLastPost->id]) }}">Read More</a>
                        </div>
                    </div>
                </div>
                @endif
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
         <!-- blog section end -->
         <!-- newsletter section start -->
         <div class="about_section layout_padding">
            <div class="container">
               <div class="row">
                @if($thirdLastPost)
                <div class="col-lg-8 col-sm-12">
                    <a href="{{ route('post.show', ['slug' => Str::slug($thirdLastPost->title), 'id' => $thirdLastPost->id]) }}">
                    <div class="about_img">
                        <img src="{{ asset('uploads/' . $thirdLastPost->image) }}" alt="{{ $thirdLastPost->title }}">
                    </div>
                    <div class="like_icon"><img src="{{ asset('assets/images/like-icon.png') }}"></div>
                    <p class="post_text">Post By: {{ \Carbon\Carbon::parse($thirdLastPost->published_at)->format('d/m/Y') }}</p>
                    <h2 class="most_text">{{ $thirdLastPost->title }}</h2>
                    <p class="lorem_text">{{ Str::limit($thirdLastPost->description, 150) }}</p></a>
                    <div class="social_icon_main">
                        <div class="social_icon"></div>

                        <div class="read_bt">
                            <a href="{{ route('post.show', ['slug' => Str::slug($thirdLastPost->title), 'id' => $thirdLastPost->id]) }}">Read More</a>
                        </div>

                    </div>
                </div>
                @endif

                  <div class="col-lg-4 col-sm-12">
                    <div class="newsletter_main">
                        <h1 class="newsletter_taital">NEWSLETTER</h1>
                        @if(session('success'))
                                <p style="color: green; font-weight: bold;">{{ session('success') }}</p>
                            @endif
                        <form action="{{ route('subscribe.newsletter') }}" method="POST">
                            @csrf
                            <div class="input_box">
                                <input type="text" class="input_text" placeholder="Your Name" name="name" required>
                                <input type="email" class="input_text" placeholder="Enter Your Email" name="email" required>
                                    @error('email')
                                        <p style="color: red;">{{ $message }}</p>
                                    @enderror

                                <button type="submit" class="send_text">Subscribe</button>
                            </div>
                        </form>
                    </div>
                </div>

               </div>
            </div>
         </div>
         <!-- newsletter section end -->
         <!-- recent section start -->
         <div class="about_section layout_padding">
            <div class="container">
               <div class="row">
                @if($fourthLastPost)
                <div class="col-lg-8 col-sm-12">
                    <a href="{{ route('post.show', ['slug' => Str::slug($fourthLastPost->title), 'id' => $fourthLastPost->id]) }}">
                    <div class="about_img">
                        <img src="{{ asset('uploads/' . $fourthLastPost->image) }}" alt="{{ $fourthLastPost->title }}">
                    </div>
                    <div class="like_icon"><img src="{{ asset('assets/images/like-icon.png') }}"></div>
                    <p class="post_text">Post By: {{ \Carbon\Carbon::parse($fourthLastPost->published_at)->format('d/m/Y') }}</p>
                    <h2 class="most_text">{{ $fourthLastPost->title }}</h2>
                    <p class="lorem_text">{{ Str::limit($fourthLastPost->description, 150) }}</p></a>
                    <div class="social_icon_main">
                        <div class="social_icon"></div>

                        <div class="read_bt">
                            <a href="{{ route('post.show', ['slug' => Str::slug($fourthLastPost->title), 'id' => $fourthLastPost->id]) }}">Read More</a>
                        </div>

                    </div>
                </div>
                @endif
                  <div class="col-lg-4 col-sm-12">
                     <div class="newsletter_main">
                        <h1 class="recent_taital">Recent post</h1>
                        <div class="recent_box">
                            @if($firstPost)
                                <div class="recent_left">
                                    <div class="image_6">
                                        <img src="{{ asset('uploads/' . $firstPost->image) }}" alt="{{ $firstPost->title }}">
                                    </div>
                                </div>
                                <div class="recent_right">
                                    <h3 class="consectetur_text">{{ $firstPost->title }}</h3>
                                    <p class="dolor_text">{{ Str::limit($firstPost->description, 50) }}</p>
                                </div>
                            @else
                                <p>No recent posts available.</p>
                            @endif
                        </div>
                        {{-- Second Last Post --}}
                        <div class="recent_box">
                            @if($secondLastPost)
                                <div class="recent_left">
                                    <div class="image_6">
                                        <img src="{{ asset('uploads/' . $secondLastPost->image) }}" alt="{{ $secondLastPost->title }}">
                                    </div>
                                </div>
                                <div class="recent_right">
                                    <h3 class="consectetur_text">{{ $secondLastPost->title }}</h3>
                                    <p class="dolor_text">{{ Str::limit($secondLastPost->description, 50) }}</p>
                                </div>
                            @else
                                <p>No second recent post available.</p>
                            @endif
                        </div>
                     </div>
                  </div>

                {{-- latest posts start --}}

                @if($remainingPosts->count() > 0)
                    @foreach($remainingPosts as $post)
                        <div class="col-lg-8 col-sm-12">
                            <a href="{{ route('post.show', ['slug' => Str::slug($post->title), 'id' => $post->id]) }}" class="">
                            <div class="about_img">
                                <img src="{{ asset('uploads/' . $post->image) }}" alt="{{ $post->title }}">
                            </div>
                            <div class="like_icon"><img src="{{ asset('assets/images/like-icon.png') }}"></div>
                            <p class="post_text">Post By: {{ \Carbon\Carbon::parse($post->published_at)->format('d/m/Y') }}</p>
                            <h2 class="most_text">{{ $post->title }}</h2>
                            <p class="lorem_text">{{ Str::limit($post->description, 150) }}</p></a>
                            <div class="social_icon_main">
                                <div class="social_icon"></div>
                                <div class="read_bt mb-5 pb-5">
                                    <a href="{{ route('post.show', ['slug' => Str::slug($post->title), 'id' => $post->id]) }}" class="">Read More</a>
                                </div>
                            </div>
                        </div>

                    @endforeach
                @endif
               </div>
               {{-- latest posts end --}}

               {{-- pagination start --}}
                <div class="contact_section layout_padding">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <ul class="pagination pagination-sm d-flex justify-content-center">
                                    <li class="page-item {{ $remainingPosts->onFirstPage() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $remainingPosts->previousPageUrl() }}">«</a>
                                    </li>

                                    @for ($i = 1; $i <= $remainingPosts->lastPage(); $i++)
                                        <li class="page-item {{ $i == $remainingPosts->currentPage() ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $remainingPosts->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endfor

                                    <li class="page-item {{ $remainingPosts->hasMorePages() ? '' : 'disabled' }}">
                                        <a class="page-link" href="{{ $remainingPosts->nextPageUrl() }}">»</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
         </div>
         {{-- pagination end --}}


         <!-- recent section end -->

         <!-- tag section start -->

        <div class="tag_section py-5 bg-light">
            <div class="container">
                <h1 class="tag_taital text-center">Recent Tags</h1>
                <div class="tag_bt">
                    <ul class="list-unstyled d-flex flex-wrap justify-content-center gap-2">
                        @foreach($tags as $tag)
                            <li class="mt-2 mb-2">
                                <a href="{{ route('tag.posts', ['slug' => Str::slug($tag->name)]) }}"
                                    class="btn btn-primary btn-sm rounded-pill border-0 tag-link">
                                     {{ $tag->name }} ({{ $tag->posts_count }})
                                 </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

         <!-- tag section end -->
         <!-- contact section start -->
         <div class="contact_section layout_padding">
            <div class="container">
               <div class="row">

                  <div class="col-md-6">
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            @foreach ($contactSliders as $key => $slider)
                                <li data-target="#carouselExampleIndicators" data-slide-to="{{ $key }}"
                                    class="{{ $key == 0 ? 'active' : '' }}"
                                    style="text-indent: 0; border: none; color: #000; font-size: 18px; text-align: center;">
                                    {{ $key + 1 }}
                                </li>
                            @endforeach
                        </ol>
                        <div class="carousel-inner">
                            @foreach ($contactSliders as $key => $slider)
                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                    <div class="">
                                        <img src="{{ asset('uploads/' . $slider->slider_image) }}" class="d-block w-100" alt="Slider Image">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="mail_section">
                        <h1 class="contact_taital">Contact us</h1>

                        @if(session('success'))
                            <p style="color: green; font-weight: bold;">{{ session('success') }}</p>
                        @endif

                        <form action="{{ route('contact.submit') }}" method="POST">
                            @csrf

                            <input type="text" class="email_text" placeholder="Name" name="name" value="{{ old('name') }}" required>
                            @error('name') <p style="color: red; font-size: 14px;">{{ $message }}</p> @enderror

                            <input type="text" class="email_text" placeholder="Phone" name="phone" value="{{ old('phone') }}" required>
                            @error('phone') <p style="color: red; font-size: 14px;">{{ $message }}</p> @enderror

                            <input type="email" class="email_text" placeholder="Email" name="email" value="{{ old('email') }}" required>
                            @error('email') <p style="color: red; font-size: 14px;">{{ $message }}</p> @enderror

                            <textarea class="massage_text" placeholder="Message" rows="5" name="message" required>{{ old('message') }}</textarea>
                            @error('message') <p style="color: red; font-size: 14px;">{{ $message }}</p> @enderror

                            <button type="submit" class="send_bt">Send</button>
                        </form>
                    </div>
                </div>


               </div>
            </div>
         </div>

         <script>
            document.addEventListener("DOMContentLoaded", function () {
                const tagLinks = document.querySelectorAll(".tag-link");

                tagLinks.forEach(link => {
                    link.addEventListener("click", function (event) {
                        tagLinks.forEach(tag => tag.classList.remove("active"));
                        this.classList.add("active");
                    });
                });
            });
        </script>
@endsection
