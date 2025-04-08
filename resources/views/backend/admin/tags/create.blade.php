
    @extends('backend.layouts.admin')

    @section('title', 'Tags')

@section('content')
<h1>Tags</h1>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">Tags</div>
                        <div class="col " >
                            @if (auth()->user()->roles->first()->hasPermissionTo('backend.admin.tags.store'))
                            <a class="btn btn-info mb-3" data-bs-toggle="modal" style="float: right;" data-bs-target="#exampleModal" id="add_tag">Add new Tag</a>
                            @endif
                            {{-- <a class="btn btn-info mb-3" data-bs-toggle="modal" style="float: right;" data-bs-target="#exampleModal" id="add_tag">Add new Tag</a> --}}
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="tag-table" class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                {{-- <th scope="col">Actions</th> --}}
                                <th scope="col">
                                    @canany(['backend.admin.tags.edit', 'backend.admin.tags.delete'])
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
{{-- <h1 class="mt-4">Tags</h1>
<ol class="breadcrumb">
    <li class="breadcrumb-item active">Tags</li>
</ol> --}}

    <div class="modal fade ajax-modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">

        <form id="ajaxForm">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-title">Create Tag</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" value="" name="tag_id" id="tag_id">
                        <div class="form-group mb-3">
                            <label for="">Name</label>
                            <input type="text" name="name" id="name" class="form-control">
                            <span id="nameError" class="text-danger error-messages"></span>
                        </div>
                        {{-- <div class="form-group mb-3">
                            <label for="post_ids">Select Posts</label>
                            <select name="post_ids[]" id="post_ids" class="form-control" multiple>
                                @foreach($posts as $post)
                                    <option value="{{ $post->id }}">{{ $post->title }}</option>
                                @endforeach
                            </select>
                        </div> --}}


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="saveBtn">Save Tag</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- <div class="row">
        <div class="col-md-6 offset-3" style="margin-top: 100px;">
            <a class="btn btn-info mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal" id="add_tag">Add new Tag</a>
            <table id="tag-table" class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div> --}}

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('#tag-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('backend.admin.tags.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    // {
                    //     data: 'posts',
                    //     name: 'posts',
                    //     orderable: false,
                    //     searchable: false
                    // },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#saveBtn').click(function() {
                $('.error-messages').html('');

                var formData = new FormData($('#ajaxForm')[0]);

                $.ajax({
                    url: '{{ route('backend.admin.tags.store') }}',
                    method: 'POST',
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: function(response) {
                        console.log(response);

                        $('#name').val('');
                        $('#tag_id').val('');
                        $('.ajax-modal').modal('hide');
                        if (response) {
                            Swal.fire({
                                title: "Success!",
                                text: response.success,
                                icon: "success",
                                confirmButtonText: "OK"
                            });
                            table.ajax.reload();
                        }
                    },
                    error: function(error) {
                        var response = JSON.parse(error.responseText);
                        var nameError = response.errors.name;
                        // var typeError = response.errors.type
                        if (error) {
                            $('#nameError').html(nameError);
                            // $('#typeError').html(typeError);
                        }
                    }
                });
            });

            //Edit button
            $('body').on('click', '.editButton', function() {
                var id = $(this).data('id');

                $.ajax({
                    url: '{{ url('admin/tags', '') }}' + '/' + id + '/edit',
                    method: 'GET',
                    success: function(response) {
                        $('.ajax-modal').modal('show');
                        $('#modal-title').html('Edit Tag');
                        $('#saveBtn').html('Update Tag');
                        // $('#category_id').val(response.id);
                        $('#tag_id').val(response.id);
                        $('#name').val(response.name);


                        console.log(response.name);

                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });

            //Delete button
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
                            url: '{{ url('admin/tags/delete') }}/' + id,
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                table.draw();
                                Swal.fire({
                                    title: "Deleted!",
                                    text: response.success,
                                    icon: "success",
                                    confirmButtonText: "OK"
                                });

                                $('#tagsTable').DataTable().ajax.reload();
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
            $('#add_tag').click(function() {
                $('#model_title').html('Create Tag');
                $('#saveBtn').html('Save Tag');
                $('.error-messages').html('');
            });

            // Modal close event - Ensuring proper binding
    $(document).on('hidden.bs.modal', '.ajax-modal', function() {
        console.log('Modal closed');
        $('#ajaxForm')[0].reset(); // Reset the form
        $('.error-messages').html('');
        $('#modal-title').html('Create Tag');
        $('#saveBtn').html('Save Tag');
    });






        });
    </script>

@endsection


