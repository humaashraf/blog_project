<div class="container-fluid header_main">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand logo" href="{{ url('/') }}">
            @if(isset($settings) && $settings->logo_image)
                <img src="{{ asset('uploads/' . $settings->logo_image) }}" width="150" alt="Logo" class="img-fluid">
            @else
                <img src="{{ asset('uploads/default-logo.png') }}" width="150" alt="Default Logo" class="img-fluid">
            @endif
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse text-center" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/about') }}">About</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/blog') }}">Blog</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/contact') }}">Contact Us</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/login') }}">Login</a></li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><img src="{{ asset('assets/images/serach-icon.png') }}" width="20"></a>
                </li>
            </ul>
        </div>
    </nav>
</div>
