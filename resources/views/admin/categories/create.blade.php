@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
<h1 class="mt-4">Categories</h1>
<ol class="breadcrumb">
    <li class="breadcrumb-item active">Categories</li>
</ol>

<div class="modal fade ajax-modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">

    <form id="ajaxForm">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Create Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="category_id" id="category_id">
                    <div class="form-group mb-3">
                        <label for="">Name</label>
                        <input type="text" name="name" id="name" class="form-control">
                        <span id="nameError" class="text-danger error-messages"></span>
                    </div>
                    <div class="form-group mb-1">
                        <label for="">Category type</label>
                        <select name="type" id="type" class="form-control">
                            <option disabled selected>Choose Option</option>
                            <option value="Electronic">Electronic</option>
                            <option value="Furniture">Furniture</option>
                            <option value="Home Accessories">Home Accessories</option>
                        </select>
                        <span id="typeError" class="text-danger error-messages"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveBtn">Save Category</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="row">
    <div class="col-md-6 offset-3" style="margin-top: 100px;">
        <a class="btn btn-info mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal" id="add_category">Add new Category</a>
        <table id="category-table" class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Type</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

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

        var table = $('#category-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.categories.index') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'type',
                    name: 'type'
                },
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
                url: '{{ route('admin.categories.store') }}',
                method: 'POST',
                processData: false,
                contentType: false,
                data: formData,
                success: function(response) {
                    $('#name').val('');
                    $('#type').val('');
                    $('#category_id').val('');
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
                    var typeError = response.errors.type
                    if (error) {
                        $('#nameError').html(nameError);
                        $('#typeError').html(typeError);
                    }
                }
            });
        });

    // Edit button event delegation
    $('body').on('click', '.editButton', function() {
        var id = $(this).data('id');
        $.ajax({
            url: '{{ url("admin/categories") }}/' + id + '/edit',  // Ensure the correct URL with the prefix
            method: 'GET',
            success: function(response) {
                $('.ajax-modal').modal('show');
                $('#modal-title').html('Edit Category');
                $('#saveBtn').html('Update Category');
                $('#category_id').val(response.id);
                $('#name').val(response.name);
                $('#type').val(response.type);
            },
            error: function(error) {
                console.log(error);
            }
        });
    });

        // Delete button event delegation
        $(document).on('click', '.delButton', function() {
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
                        url: '{{ url('admin/categories/delete') }}/' + id,
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
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: "Error!",
                                text: xhr.responseJSON.error || "Something went wrong!",
                                icon: "error",
                                confirmButtonText: "OK"
                            });
                        }
                    });
                }
            });
        });

        $('#add_category').click(function() {
            $('#model_title').html('Create Category');
            $('#saveBtn').html('Save Category');
            $('.error-messages').html('');
        });

        $(document).on('hidden.bs.modal', '.ajax-modal', function() {
            $('#ajaxForm')[0].reset();
            $('.error-messages').html('');
            $('#modal-title').html('Create Category');
            $('#saveBtn').html('Save Category');
        });
    });
</script>

@endsection
