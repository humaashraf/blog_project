<!DOCTYPE html>
<html lang="en">
   <head>
      <!-- basic -->
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- mobile metas -->
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="viewport" content="initial-scale=1, maximum-scale=1">
      <!-- site metas -->
      <title>BlogNest</title>
      <meta name="keywords" content="">
      <meta name="description" content="">
      <meta name="author" content="">
      <!-- bootstrap css -->
      <link rel="stylesheet" type="text/css" href="{{asset('assets/css/bootstrap.min.css')}}">
      <!-- style css -->
      <link rel="stylesheet" type="text/css" href="{{asset('assets/css/style.css')}}">
      <!-- Responsive-->
      <link rel="stylesheet" href="{{asset('assets/css/responsive.css')}}">
      <!-- fevicon -->
      <link rel="icon" href="{{asset('assets/images/fevicon.png')}}" type="image/gif" />
      <!-- Scrollbar Custom CSS -->
      <link rel="stylesheet" href="{{asset('assets/css/jquery.mCustomScrollbar.min.css')}}">
      <!-- Tweaks for older IEs-->
      <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
      <!-- owl stylesheets -->
      <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
      <link rel="stylesoeet" href="assets/css/owl.theme.default.min.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
      <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
       <!-- Favicon -->
    {{-- <link rel="icon" type="image/png" href="{{ asset('assets/images/faveicon.png') }}"> --}}
    <!-- Favicon -->

        {{-- <link rel="icon" type="image/png" href="{{ asset('uploads/' . $settings->favicon_image) }}"> --}}
        @if(isset($settings) && $settings->favicon_image)
        <link rel="icon" type="image/png" href="{{ asset('uploads/' . $settings->favicon_image) }}">
    @else
        <link rel="icon" type="image/png" href="{{ asset('assets/images/faveicon.png') }}">
    @endif

   </head>
   <body>
      <!-- header section start -->
      <div class="header_section">

        @include('frontend.layouts.header')



      </div>
      @yield('content')
      <!-- header section end -->

      <!-- contact section end -->
      <!-- footer section start -->

      @include('frontend.layouts.footer')

      <!-- footer section end -->
      <!-- copyright section start -->

      <!-- copyright section end -->
      <!-- Javascript files-->
      <script src="{{asset('assets/js/jquery.min.js')}}"></script>
      <script src="{{asset('assets/js/popper.min.js')}}"></script>
      <script src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
      <script src="{{asset('assets/js/jquery-3.0.0.min.js')}}"></script>
      <script src="{{asset('assets/js/plugin.js')}}"></script>
      <!-- sidebar -->
      <script src="{{asset('assets/js/jquery.mCustomScrollbar.concat.min.js')}}"></script>
      <script src="{{asset('assets/js/custom.js')}}"></script>
      <!-- javascript -->
      <script src="{{asset('assets/js/owl.carousel.js')}}"></script>
      <script src="https:cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
   </body>
</html>

