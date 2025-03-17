<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="{{ url('dashboard') }}">Blog Nest Author</a>

    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Navbar Search-->
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <div class="input-group">
            <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
            <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
        </div>
    </form>
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="notificationDropdown" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-bell"></i>
                        <span class="badge bg-danger">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                        @forelse(auth()->user()->unreadNotifications->sortBy('created_at') as $notification)
                            <li>
                                <a class="dropdown-item mark-as-read" href="{{ route('posts.show', $notification->data['post_id']) }}"
                                   data-post-id="{{ $notification->data['post_id'] }}">
                                   <strong style="color: red;">Rejected</strong> <br>
                                   <strong>Title</strong> -
                                    {{ $notification->data['title'] }}
                                    @if(isset($notification->data['reason']))
                                         <br>
                                        <small><strong>Reason:</strong> {{ $notification->data['reason'] }}</small>
                                    @else
                                        - <strong style="color: green;">Approved</strong>
                                    @endif
                                </a>
                            </li>
                        @empty
                            <li><a class="dropdown-item" href="#">No new notifications</a></li>
                        @endforelse
                    </ul>
                </li>
            </ul>

        </li>

    </ul>

</nav>
<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.mark-as-read').forEach(function (element) {
        element.addEventListener('click', function (event) {
            event.preventDefault();
            let postId = this.getAttribute('data-post-id');

            fetch('{{ route('notifications.markAsRead') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ post_id: postId })
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      location.href = this.getAttribute('href'); // Redirect to post
                  }
              }).catch(error => console.error('Error:', error));
        });
    });
});



        $('.mark-as-read').click(function (event) {
            event.preventDefault();
            let notificationId = $(this).data('id');
            let redirectUrl = $(this).attr('href');

            $.ajax({
                url: "{{ route('notifications.markAsRead') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    notification_id: notificationId
                },
                success: function (response) {
                    if (response.success) {
                        $('a[data-id="' + notificationId + '"]').parent().remove();

                        let count = parseInt($('#notificationCount').text());
                        if (count > 1) {
                            $('#notificationCount').text(count - 1);
                        } else {
                            $('#notificationCount').remove();
                        }

                        window.location.href = redirectUrl;
                    }
                }
            });
        });




</script>
