@extends('backend.layouts.admin')

@section('title', 'User Comments')

@section('content')
<h1>User's Comments</h1>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">Comments</div>

                    </div>
                </div>
                <div class="card-body">
                    <table id="comment-table" class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Post Title</th>
                                <th>Comment</th>
                                {{-- <th>Published At</th> --}}
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

{{-- Scripts --}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

    $(document).ready(function() {
        $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

        var table = $('#comment-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('backend.admin.comments.index') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'post_title', name: 'post_title' },
                { data: 'comment', name: 'comment' },
                // { data: 'published_at', name: 'published_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
                  // Approve Button

$(document).on('click', '.approveButton', function() {
    var commentId = $(this).data('id');

    Swal.fire({
        title: "Are you sure?",
        text: "You want to approve this comment?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Approve it!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ url('/admin/comments/approve') }}/" + commentId,
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    Swal.fire({
                        title: "Approved!",
                        text: response.success,
                        icon: "success",
                        confirmButtonText: "OK"
                    }).then(() => {
                        $('#comment-table').DataTable().ajax.reload(); // Refresh table
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        title: "Error!",
                        text: "Something went wrong!",
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                }
            });
        }
    });
});

$(document).on('click', '.deleteBtn', function() {
    var id = $(this).data('id');

    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/admin/comments/" + id,
                type: "DELETE",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content') // CSRF token added here
                },
                success: function(response) {
                    Swal.fire({
                        // title: "Deleted!",
                        // text: "Subscriber has been removed.",
                        // icon: "success",
                        // timer: 2000,
                        // showConfirmButton: false
                        title: "Deleted!",
                        text: response.success,
                        icon: "success",
                        confirmButtonText: "OK"
                    });

                    table.ajax.reload();
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: "Error!",
                        text: xhr.responseJSON.error ||
                                "Something went wrong!",
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                }
            });
        }
    });

            // Approve Button

// $(document).on('click', '.approveButton', function() {
//     var commentId = $(this).data('id');

//     Swal.fire({
//         title: "Are you sure?",
//         text: "You want to approve this comment?",
//         icon: "warning",
//         showCancelButton: true,
//         confirmButtonColor: "#28a745",
//         cancelButtonColor: "#d33",
//         confirmButtonText: "Yes, Approve it!"
//     }).then((result) => {
//         if (result.isConfirmed) {
//             $.ajax({
//                 url: "{{ url('/admin/comments/approve') }}/" + commentId,
//                 type: "POST",
//                 data: {
//                     _token: "{{ csrf_token() }}"
//                 },
//                 success: function(response) {
//                     Swal.fire({
//                         title: "Approved!",
//                         text: response.success,
//                         icon: "success",
//                         confirmButtonText: "OK"
//                     }).then(() => {
//                         $('#comment-table').DataTable().ajax.reload(); // Refresh table
//                     });
//                 },
//                 error: function(xhr) {
//                     Swal.fire({
//                         title: "Error!",
//                         text: "Something went wrong!",
//                         icon: "error",
//                         confirmButtonText: "OK"
//                     });
//                 }
//             });
//         }
//     });
// });

});


    });
</script>
@endsection
