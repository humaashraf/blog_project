<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="{{ asset('admin/css/styles.css') }}" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    {{-- <link rel="icon" type="image/png" href="{{ asset('assets/images/faveicon.png') }}"> --}}

    {{-- <link rel="icon" type="image/png" href="{{ asset('uploads/' . $settings->favicon_image) }}"> --}}
    @if (isset($settings) && $settings->favicon_image)
        <link rel="icon" type="image/png" href="{{ asset('uploads/' . $settings->favicon_image) }}">
    @else
        <link rel="icon" type="image/png" href="{{ asset('assets/images/faveicon.png') }}">
    @endif


    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="sb-nav-fixed">

    {{-- @include('backend.layouts.partials.navbar') --}}

    @if(auth()->user()->role_id == '1')
        @include('backend.layouts.partials.navbar') {{-- Admin Navbar --}}
    @elseif(auth()->user()->role_id == '2')
        @include('backend.layouts.partials.navbar_author') {{-- Author Navbar --}}
    @endif


    <div id="layoutSidenav">

        {{-- <div id="layoutSidenav_nav">
            @include('layouts.partials.sidebar')
        </div> --}}
        <div id="layoutSidenav_nav">
            @if (auth()->user()->role_id == 2)
                <!-- Example: Author Role -->
                @include('backend.layouts.partials.sidebar_author') <!-- Author Sidebar -->
            @else
                @include('backend.layouts.partials.sidebar') <!-- Default Sidebar -->
            @endif
        </div>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    @yield('content')

                </div>
            </main>

            @include('backend.layouts.partials.footer')

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="{{ asset('admin/js/scripts.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>

    <script src="{{ asset('admin/js/datatables-simple-demo.js') }}"></script>

</body>

</html>
