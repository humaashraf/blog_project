@extends('backend.layouts.admin')

@section('title', 'Sub Admins')

@section('content')
<h1>Sub Admins</h1>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">Sub Admins</div>
                    <div class="col">
                        <a class="btn btn-info mb-3" data-bs-toggle="modal" style="float: right;" data-bs-target="#exampleModal" id="add_subadmin">Add Sub Admin</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="subadmin-table" class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Admin Role</th>
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

<div class="modal fade ajax-modal" id="exampleModal" tabindex="-1" aria-hidden="true">
    <form id="ajaxForm">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Create Sub Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="subadmin_id" id="subadmin_id">
                    <div class="form-group mb-3">
                        <label>Name</label>
                        <input type="text" name="name" id="name" class="form-control">
                        <span id="nameError" class="text-danger error-messages"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label>Email</label>
                        <input type="email" name="email" id="email" class="form-control">
                        <span id="emailError" class="text-danger error-messages"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label>Password</label>
                        <input type="password" name="password" id="password" class="form-control">
                        <span id="passwordError" class="text-danger error-messages"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveBtn">Save Sub Admin</button>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- jQuery CDN -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>


<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // show data
    var table = $('#subadmin-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('backend.admin.sub_admins.index') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'role_name', name: 'role_name', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    // save data
    $('#saveBtn').click(function() {
        $('.error-messages').html('');
        var formData = new FormData($('#ajaxForm')[0]);

        $.ajax({
            url: '{{ route('backend.admin.sub_admins.store') }}',
            method: 'POST',
            processData: false,
            contentType: false,
            data: formData,
            success: function(response) {
                $('#ajaxForm')[0].reset();
                $('.ajax-modal').modal('hide');
                Swal.fire("Success!", response.success, "success");
                table.ajax.reload();
            },
            error: function(error) {
                var response = JSON.parse(error.responseText);
                if (error) {
                    $('#nameError').html(response.errors.name);
                    $('#emailError').html(response.errors.email);
                    $('#passwordError').html(response.errors.password);
                }
            }
        });
    });

    // edit button
    $(document).on('click', '.editButton', function() {
        var id = $(this).data('href');
        $.ajax({
            url: '{{ url('admin/sub_admins') }}/' + id + '/edit',
            method: 'GET',
            success: function(response) {
                $('.ajax-modal').modal('show');
                $('#modal-title').html('Edit Sub Admin');
                $('#saveBtn').html('Update');
                $('#subadmin_id').val(response.id);
                $('#name').val(response.name);
                $('#email').val(response.email);
            }
        });
    });

    // delete data
    $(document).on('click', '.delButton', function() {
        var id = $(this).data('href');
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
                    url: '{{ url('admin/sub_admins/delete') }}/' + id,
                    method: 'DELETE',
                    success: function(response) {
                        Swal.fire("Deleted!", response.success, "success");
                        table.ajax.reload();
                    }
                });
            }
        });
    });
});
</script>
@endsection
