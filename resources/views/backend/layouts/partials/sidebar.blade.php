<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
            {{-- <div class="sb-sidenav-menu-heading">Core</div> --}}
            @if(in_array('backend.dashboard', $permissions))
            {{-- @if(in_array('backend.admin.categories.index', $permissions)) --}}
            <a class="nav-link" href="{{ url('admin/dashboard') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                Dashboard
            </a>
            @endif

            @if(in_array('backend.admin.users.index', $permissions))
            <a class="nav-link" href="{{ url('admin/users') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                Manage Users
            </a>
            @endif
            {{-- @if(in_array('backend.admin.roles.index', $permissions)) --}}
            {{-- @if(in_array('backend.admin.roles.index', $permissions)) --}}

            @if(in_array('backend.admin.roles.index', $permissions))
            <a class="nav-link" href="{{ url('admin/roles') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-user-shield"></i></div>
                Manage Roles
            </a>
            @endif
            {{-- @endif --}}
            @if(in_array('backend.admin.categories.index', $permissions))
            <a class="nav-link" href="{{ url('admin/categories') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>
                Categories
            </a>
            @endif
            @if(in_array('backend.admin.posts.index', $permissions))
            <a class="nav-link" href="{{ url('admin/posts') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-newspaper"></i></div>
                Posts
            </a>
            @endif
            {{-- <a class="nav-link" href="{{ url('admin/pending-posts') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-newspaper"></i></div>
                Pending Posts
            </a> --}}
            @if(in_array('backend.admin.pendingposts.index', $permissions))
            <a class="nav-link" href="{{ url('admin/pending-posts') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-newspaper"></i></div>
                Pending Posts
                <span class="badge bg-danger" id="pendingPostCount">
                    {{ \App\Models\Post::where('is_approved', 0)->count() }}
                </span>
            </a>
            @endif

            @if(in_array('backend.admin.tags.index', $permissions))
            <a class="nav-link" href="{{ url('admin/tags') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-tags"></i></div>
                Tags
            </a>
            @endif
            @if(in_array('backend.admin.sliders.index', $permissions))
            <a class="nav-link" href="{{ url('admin/sliders') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-images"></i></div>
                Sliders
            </a>
            @endif
            @if(in_array('backend.admin.settings.index', $permissions))
            <a class="nav-link" href="{{ url('admin/settings') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-cog"></i></div>
                website Settings
            </a>
            @endif
            @if(in_array('backend.admin.abouts.index', $permissions))
            <a class="nav-link" href="{{ url('admin/abouts') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-cog"></i></div>
                About Settings
            </a>
            @endif
            @if(in_array('backend.admin.newsletters.index', $permissions))
            <a class="nav-link" href="{{ url('admin/newsletters') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-newspaper"></i></div>
                Newsletters
            </a>
            @endif
            @if(in_array('backend.admin.visitors.index', $permissions))
            <a class="nav-link" href="{{ url('admin/visitors') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-newspaper"></i></div>
                Visitors
            </a>
            @endif
            {{-- <a class="nav-link" href="{{ url('admin/comments') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-comments"></i></div>
                Pending Comments
            </a> --}}
            @if(in_array('backend.admin.comments.index', $permissions))
            <a class="nav-link" href="{{ url('admin/comments') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-comments"></i></div>
                Comments
                <span class="badge bg-danger" id="pendingCommentCount">
                    {{ \App\Models\Comment::where('is_approved', 0)->count() }}
                </span>
            </a>
            @endif

            <a class="nav-link" href="javascript:void(0);" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div>
                Logout
            </a>
            <!-- Logout Form -->
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>




            {{-- <div class="sb-sidenav-menu-heading">Dropdowns</div> --}}
            {{-- <a class="nav-link collapsed" href="" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                <div class="sb-nav-link-icon"><i class="fas fa-comments"></i></div>
                Contact Us
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="{{ url('admin/contactform') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-envelope"></i></div>
                        Contact Form
                    </a>
                </nav>

                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="{{ url('admin/contacts') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-images"></i></div>
                        Sliders
                    </a>
                </nav>
            </div>

            <a class="nav-link collapsed" href="" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                <div class="sb-nav-link-icon"><i class="fas fa-comments"></i></div>
                Reports
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="{{ url('admin/posts/post-report') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-envelope"></i></div>
                        Posts Report
                    </a>
                </nav>
            </div> --}}

            @if(in_array('backend.admin.contacts.index', $permissions) || in_array('backend.admin.contacts.indexform', $permissions))
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseContactUs" aria-expanded="false" aria-controls="collapseContactUs">
                <div class="sb-nav-link-icon"><i class="fas fa-comments"></i></div>
                Contact Us
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseContactUs" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                @if(in_array('backend.admin.contacts.indexform', $permissions))
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="{{ url('admin/contactform') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-envelope"></i></div>
                        Contact Form
                    </a>
                </nav>
                @endif
                @if(in_array('backend.admin.contacts.index', $permissions))
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="{{ url('admin/contacts') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-images"></i></div>
                        Sliders
                    </a>
                </nav>
                @endif
            </div>
            @endif

            @if(in_array('backend.admin.posts.report', $permissions) || in_array('backend.admin.categories.report', $permissions) || in_array('backend.admin.top_viewed_posts', $permissions))
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseReports" aria-expanded="false" aria-controls="collapseReports">
                <div class="sb-nav-link-icon"><i class="fas fa-file-alt"></i></div>
                Reports
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseReports" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                @if(in_array('backend.admin.posts.report', $permissions))
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="{{ url('admin/posts/post-report') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-chart-line"></i></div>
                        Posts Report
                    </a>
                </nav>
                @endif
            </div>
            <div class="collapse" id="collapseReports" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                @if(in_array('backend.admin.categories.report', $permissions))
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="{{ url('admin/categories/category-report') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-chart-line"></i></div>
                        Categories Report
                    </a>
                </nav>
                @endif
                <div class="collapse" id="collapseReports" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    @if(in_array('backend.admin.top_viewed_posts', $permissions))
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ url('admin/categories/top-viewed-posts') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-line"></i></div>
                            Top View Report
                        </a>
                    </nav>
                    @endif
            </div>
            @endif


            {{-- <a class="nav-link" href="javascript:void(0);" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div>
                Logout
            </a>
            <!-- Logout Form -->
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form> --}}

{{--
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                Pages
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                        Authentication
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="login.html">Login</a>
                            <a class="nav-link" href="register.html">Register</a>
                            <a class="nav-link" href="password.html">Forgot Password</a>
                        </nav>
                    </div>
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseError" aria-expanded="false" aria-controls="pagesCollapseError">
                        Error
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="401.html">401 Page</a>
                            <a class="nav-link" href="404.html">404 Page</a>
                            <a class="nav-link" href="500.html">500 Page</a>
                        </nav>
                    </div>
                </nav>
            </div>
            <div class="sb-sidenav-menu-heading">Addons</div>
            <a class="nav-link" href="charts.html">
                <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                Charts
            </a>
            <a class="nav-link" href="tables.html">
                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                Tables
            </a> --}}


        {{-- </div> --}}

        {{-- <a class="nav-link" href="javascript:void(0);" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div>
            Logout
        </a>
        <!-- Logout Form -->
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form> --}}
    </div>
</div>

    {{-- <style>
        .sb-sidenav-footer {
    position: absolute;
    bottom: 0;
    width: 100%;
}

    </style> --}}

    <div class="sb-sidenav-footer">
        <div class="small">Logged in as:</div>
        Admin
    </div>
</nav>
