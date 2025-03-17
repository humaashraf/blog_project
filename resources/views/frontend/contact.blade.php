@extends('frontend.layouts.app')
@section('content')

<!-- contact section start -->
<div class="contact_section layout_padding">
    <div class="container">
       <div class="row">
          {{-- <div class="col-md-6">
             <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                   <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active" style="text-indent: 0; border: none; color: #000; font-size: 18px; text-align: center;">1</li>
                   <li data-target="#carouselExampleIndicators" data-slide-to="1"style="text-indent: 0; border: none; color: #000; font-size: 18px; text-align: center;">2</li>
                   <li data-target="#carouselExampleIndicators" data-slide-to="2"style="text-indent: 0; border: none; color: #000; font-size: 18px; text-align: center;">3</li>
                   <li data-target="#carouselExampleIndicators" data-slide-to="3"style="text-indent: 0; border: none; color: #000; font-size: 18px; text-align: center;">4</li>
                </ol>
                <div class="carousel-inner">
                   <div class="carousel-item active">
                      <div class="contact_img"></div>
                   </div>
                   <div class="carousel-item">
                      <div class="contact_img"></div>
                   </div>
                   <div class="carousel-item">
                      <div class="contact_img"></div>
                   </div>
                   <div class="carousel-item">
                      <div class="contact_img"></div>
                   </div>
                </div>
             </div>
          </div> --}}

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

          {{-- <div class="col-md-6">
             <div class="mail_section">
                <h1 class="contact_taital">Contact us</h1>
                <input type="" class="email_text" placeholder="Name" name="Name">
                <input type="" class="email_text" placeholder="Phone" name="Phone">
                <input type="" class="email_text" placeholder="Email" name="Email">
                <textarea class="massage_text" placeholder="Message" rows="5" id="comment" name="Message"></textarea>
                <div class="send_bt"><a href="#">send</a></div>
             </div>
          </div> --}}
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
 <!-- contact section end -->

@endsection
