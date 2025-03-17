<div class="footer_section layout_padding">
    <div class="container text-center">
        <div class="footer_logo">
            <a href="{{ url('/') }}">
                <img src="{{ asset('assets/images/blog-logo-footer.png') }}" width="230" alt="Footer Logo">
            </a>
        </div>

        <div class="footer_menu">
            <ul class="list-inline">
                <li class="list-inline-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="list-inline-item"><a href="{{ url('/about') }}">About</a></li>
                <li class="list-inline-item"><a href="{{ url('/blog') }}">Blog</a></li>
                <li class="list-inline-item"><a href="{{ url('/contact') }}">Contact Us</a></li>
            </ul>
        </div>

        <div class="contact_menu">
            <ul class="list-inline">
                <li class="list-inline-item">
                    <a href="#"><img src="{{ asset('assets/images/call-icon.png') }}" width="20"></a>
                    Call : +01 1234567890
                </li>
                <li class="list-inline-item">
                    <a href="{{ url('/blog') }}"><img src="{{ asset('assets/images/mail-icon.png') }}" width="20"></a>
                    <a href="mailto:blognest@gmail.com">blognest@gmail.com</a>
                </li>
            </ul>
        </div>
    </div>
</div>
