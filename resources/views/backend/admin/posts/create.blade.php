@extends('backend.layouts.admin')

@section('title', 'Posts')

@section('content')
    <h1>Posts</h1>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">Posts</div>
                        <div class="col " >
                            @if (auth()->user()->roles->first()->hasPermissionTo('backend.admin.posts.store'))
                            <a class="btn btn-info mb-2 mt-2" style="float: right;" data-bs-toggle="modal" data-bs-target="#exampleModal" id="add_post">Add new Post</a>
                            @endif
                            {{-- <a class="btn btn-info mb-2 mt-2" style="float: right;" data-bs-toggle="modal" data-bs-target="#exampleModal" id="add_post">Add new Post</a> --}}
                        </div>
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
                                <th scope="col">Approval Status</th>
                                <th scope="col">Tags</th>
                                <th scope="col">Author Name</th>
                                {{-- <th scope="col">Actions</th> --}}
                                {{-- <th scope="col">
                                    @canany(['backend.admin.posts.edit', 'backend.admin.posts.delete', 'backend.admin.post.show'])
                                        Actions
                                    @endcanany
                                </th> --}}
                                <th scope="col">
                                    @canany(['backend.admin.posts.edit', 'backend.admin.posts.delete', 'backend.admin.post.show'])
                                        Actions
                                    @endcanany
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
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <div class="modal fade ajax-modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form id="ajaxForm">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-title">Create Post</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="post_id" id="post_id">
                        <div class="form-group mb-3">
                            <label for="">Title</label>
                            <input type="text" name="title" id="title" class="form-control">
                            <span id="titleError" class="text-danger error-messages"></span>
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Description</label>
                            <input type="text" name="description" id="description" class="form-control">
                            <span id="descriptionError" class="text-danger error-messages"></span>
                        </div>

                        <select name="tag_ids[]" multiple>
                            @foreach ($tags as $tag)
                                <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                            @endforeach
                        </select>

                        <div class="form-group mb-3">
                            <label for="category_id">Category</label>
                            <select name="category_id" id="category_id" class="form-control" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <span id="category_idError" class="text-danger error-messages"></span>
                        </div>


                        <!-- Image Upload -->
                        <div class="form-group mb-3">
                            <label for="image">Upload Image</label>
                            <input type="file" name="image" id="image" class="form-control">
                            <span id="imageError" class="text-danger error-messages"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="saveBtn">Save Post</button>
                    </div>
                </div>
            </div>
        </form>
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
                ajax: "/admin/posts",
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
                        data: 'approval_status',
                        name: 'approval_status'
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

            // Save data
            $('#saveBtn').click(function() {
                $('.error-messages').html('');

                var formData = new FormData($('#ajaxForm')[0]);
                formData.append('image', $('#image')[0].files[0]);

                $.ajax({
                    url: '{{ route('backend.admin.posts.store') }}',
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
                    // error: function(error) {
                    //     var response = JSON.parse(error.responseText);
                    //     $('#titleError').html(response.errors?.title || '');
                    //     $('#descriptionError').html(response.errors?.description || '');
                    //     $('#imageError').html(response.errors?.image || '');
                    // }
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

            // Edit button
            $('body').on('click', '.editButton', function() {
                var id = $(this).data('id');

                $.ajax({
                    url: '{{ url('admin/posts', '') }}' + '/' + id + '/edit',
                    method: 'GET',
                    success: function(response) {
                        $('.ajax-modal').modal('show');
                        $('#modal-title').html('Edit Post');
                        $('#saveBtn').html('Update Post');
                        $('#post_id').val(response.id);
                        $('#title').val(response.title);
                        $('#description').val(response.description);
                        $('#category_id').val(response.category_id);
                        // $('#image').val(response.image);
                        // Display the current image if it exists
                        if (response.image) {
                            $('#image').attr('src', '{{ url('uploads') }}/' + response.image);
                            $('#image').show();
                        } else {
                            $('#image').hide();
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });

            // Delete button
            $('body').on('click', '.delButton', function() {
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
                            url: '{{ url('admin/posts/delete') }}/' + id,
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

            // $(document).on('click', '.viewButton', function () {
            //     var postId = $(this).data('id');
            //     window.location.href = "/admin/post/" + postId; // Backend route ka URL
            // });

            // $(document).on('click', '.viewButton', function () {
            //     var postId = $(this).data('id');
            //     window.location.href = "/admin/post/" + postId; // Backend route ka URL
            // });
//             $(document).on('click', '.viewButton', function () {
//     let postId = $(this).data('id');
//     let postSlug = $(this).data('slug');

//     let url = `/admin/post/${postSlug}/${postId}`; // Generate dynamic URL

//     window.location.href = url; // Redirect to the new page
// });

// $(document).on('click', '.viewButton', function () {
//     var postId = $(this).data('id');
//     var postSlug = $(this).data('slug'); // Get the slug from the data attribute

//     window.location.href = "/admin/post/" + postSlug + "/" + postId; // Redirect with slug
// });
// $(document).on('click', '.viewButton', function () {
//     var postId = $(this).data('id');
//     var postSlug = $(this).data('slug');

//     // Correct format
//     let url = "/admin/post/" + postSlug + "/" + postId;

//     window.location.href = url; // Redirect user
// });
// $(document).on('click', '.viewButton', function () {
//     var postId = $(this).data('id'); // Get post ID
//     var postSlug = $(this).data('slug'); // Get post slug

//     if (postSlug && postId) {
//         let url = "/admin/post/" + postSlug + "/" + postId;
//         window.location.href = url; // Redirect to the correct URL
//     } else {
//         console.error("Slug or ID is missing!");
//     }
// });
$(document).on('click', '.viewButton', function () {
    var slug = $(this).data('slug');
    var postId = $(this).data('id');

    if (slug && postId) {
        window.location.href = "/admin/post/" + slug + "/" + postId;
    }
});






        });
    </script>

@endsection

{{-- </body>

</html> --}}
