
@extends('backend.layouts.admin')

@section('title', 'Pending Posts')

@section('content')
<h1>Pending Posts</h1>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">Pending Posts</div>

                    </div>
                </div>
                <div class="card-body">
                    <table id="post-table" class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Title</th>
                                <th scope="col">Description</th>
                                <th scope="col">Image</th>
                                <th scope="col">Category</th>
                                <th scope="col">Tags</th>
                                <th scope="col">Author Name</th>
                                {{-- <th scope="col">Actions</th> --}}
                                <th scope="col">
                                    @if (auth()->user()->roles->first()->hasPermissionTo('backend.admin.pendingposts.deny') || auth()->user()->roles->first()->hasPermissionTo('backend.admin.pendingposts.approve') || auth()->user()->roles->first()->hasPermissionTo('backend.admin.pendingposts.delete'))
                                        Actions
                                    @endif
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

<!-- Deny Reason Modal -->
<div class="modal fade" id="denyModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Deny Post</h5>
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <label for="denyReason">Enter Reason:</label>
                <textarea id="denyReason" class="form-control"></textarea>
            </div>
            <div class="modal-footer">
                {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button> --}}
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="submitDeny">Submit</button>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //  Show data

        var table = $('#post-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('backend.admin.pendingposts.index') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'title',
                    name: 'title'
                },
                // {
                //     data: 'description',
                //     name: 'description'
                // },
                {
                        data: 'description',
                        name: 'description',
                        render: function(data, type, row) {
                            return data.length > 50 ? data.substring(0, 50) + "..." : data;
                        }
                    },
                {
                    data: 'image',
                    name: 'image'
                },
                {
                    data: 'category',
                    name: 'category'
                },
                {
                    data: 'tags',
                    name: 'tags'
                },
                {
                    data: 'author',
                    name: 'author'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        // denied posts

var denyPostId = null; // Global variable

$(document).on('click', '.denyButton', function() {
    denyPostId = $(this).data('id'); // Get post ID
    $('#denyModal').modal('show'); // Show modal
});

$('#submitDeny').on('click', function() {
    var reason = $('#denyReason').val();

    if (!denyPostId) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Something went wrong! Post ID not found.',
            confirmButtonText: 'OK'
        });
        return;
    }

    if (reason.trim() === '') {
        Swal.fire({
            icon: 'warning',
            title: 'Warning',
            text: 'Please enter a reason for denial!',
            confirmButtonText: 'OK'
        });
        return;
    }

    Swal.fire({
        title: 'Are you sure?',
        text: "You are about to deny this post!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, deny it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ url('/admin/pendingposts/deny') }}/" + denyPostId,
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    reason: reason
                },
                success: function(response) {
                    $('#denyModal').modal('hide'); // Hide modal
                    Swal.fire({
                        icon: 'success',
                        title: 'Denied!',
                        text: response.success,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $('#post-table').DataTable().ajax.reload(); // Refresh table
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong!',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });
});




        // Save data
        $('#saveBtn').click(function() {
            $('.error-messages').html('');

            var formData = new FormData($('#ajaxForm')[0]);
            formData.append('image', $('#image')[0].files[0]);

            $.ajax({
                url: '{{ route('backend.admin.pendingposts.store') }}',
                method: 'POST',
                processData: false,
                contentType: false,
                data: formData,
                success: function(response) {
                    $('#title').val('');
                    $('#description').val('');
                    $('#image').val('');
                    $('#category_id').val('');
                    $('#post_id').val('');
                    $('.ajax-modal').modal('hide');
                    Swal.fire({
                        title: "Success!",
                        text: response.success,
                        icon: "success",
                        confirmButtonText: "OK"
                    });
                    table.ajax.reload();
                },

                error: function(error) {
                    if (error.status == 422) {
                        var response = JSON.parse(error.responseText);
                        var titleError = response.errors.title;
                        var descriptionError = response.errors.description;
                        var category_idError = response.errors.category_id;
                        var imageError = response.errors.image;
                        if (error) {
                            $('#titleError').html(titleError);
                            $('#descriptionError').html(descriptionError);
                            $('#imageError').html(imageError);
                            $('#category_idError').html(category_idError);
                        }
                    }
                }
            });
        });

        // Approve Button

$(document).on('click', '.approveButton', function() {
    var postId = $(this).data('id');

    Swal.fire({
        title: "Are you sure?",
        text: "You want to approve this post?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Approve it!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ url('/admin/pending-posts/approve') }}/" + postId,
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
                        $('#post-table').DataTable().ajax.reload(); // Refresh table
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

// Deny Button
$(document).on('click', '.denyButton', function() {
    var postId = $(this).data('id');
    $('#denyPostId').val(postId); // Post ID set karein
    $('#denyModal').modal('show'); // Modal open karein
});

$('#confirmDeny').on('click', function() {
    var postId = $('#denyPostId').val();
    var reason = $('#denyReason').val();

    if (reason.trim() === "") {
        alert("Please enter a reason for denial.");
        return;
    }

    $.ajax({
        url: "{{ url('/admin/pendingposts/deny') }}/" + postId,
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            reason: reason
        },
        success: function(response) {
            alert(response.success);
            $('#denyModal').modal('hide'); // Modal close karein
            $('#post-table').DataTable().ajax.reload(); // Table refresh karein
        },
        error: function(xhr) {
            alert("Something went wrong!");
        }
    });
});




        // Delete button
        $('body').on('click', '.deleteBtn', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('admin/pendingposts/delete') }}/' + id,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            table.ajax.reload();
                            Swal.fire({
                                title: "Deleted!",
                                text: response.success,
                                icon: "success",
                                confirmButtonText: "OK"
                            });
                        },
                        error: function(xhr) {
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
        });

        // Show title
        $('#add_post').click(function() {
            $('#modal-title').html('Create Post');
            $('#saveBtn').html('Save Post');
            $('.error-messages').html('');
        });

        // Modal close event - Ensuring proper binding
        $(document).on('hidden.bs.modal', '#exampleModal', function() {
            $('#ajaxForm')[0].reset();
            $('#post_id').val('');
            $('.error-messages').html('');
            $('#modal-title').html('Create Post');
            $('#saveBtn').html('Save Post');
        });

        // Modal close hone par reason field clear karein
    $('#denyModal').on('hidden.bs.modal', function () {
        $('#denyReason').val('');
    });
    });
</script>

@endsection

{{-- </body>

</html> --}}
