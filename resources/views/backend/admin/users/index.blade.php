@extends('backend.layouts.admin')

@section('title', 'Users')

@section('content')
<h1>Users</h1>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">Users</div>
                    <div class="col">
                        <a class="btn btn-info mb-3" data-bs-toggle="modal" style="float: right;" data-bs-target="#exampleModal" id="users">Add new Users</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="user-table" class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Type</th>
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
                    <h5 class="modal-title" id="modal-title">Create User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    {{-- <input type="hidden" name="user_id" id="user_id"> --}}
                    <input type="hidden" id="user_id" name="subadmin_id">

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
                    <div class="form-group">
                        <label for="role">Select Role</label>
                        <select name="role" id="role" class="form-control" required>
                            <option value="" disabled selected>-- Select Role --</option>
                            @foreach($roles as $id => $name)
                                <option value="{{ $id }}" {{ isset($user) && $user->role_id == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveBtn">Save User</button>
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
    var table = $('#user-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('backend.admin.users.index') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'type', name: 'type' },
            { data: 'role_name', name: 'role_name', searchable: true, orderable: true },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    // save data
    $('#saveBtn').click(function() {
        $('.error-messages').html('');
        var formData = new FormData($('#ajaxForm')[0]);

        $.ajax({
            url: '{{ route('backend.admin.users.store') }}',
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
                    $('#roleError').html(response.errors?.role ?? '');
                }
            }
        });
    });

    // edit button
//     $(document).on('click', '.editButton', function() {
//     let userId = $(this).data('id'); // Corrected: Fetch `data-id`

//     if (!userId) {
//         alert("User ID is missing!"); // Debugging
//         return;
//     }

//     $.ajax({
//         url: `/admin/users/${userId}/edit`, // Correctly pass userId
//         method: 'GET',
//         success: function(response) {
//             $('#editModal').modal('show');
//             $('#edit_name').val(response.name);
//             $('#edit_email').val(response.email);
//         },
//         error: function(xhr) {
//             console.log(xhr.responseText);
//             alert('Something went wrong!');
//         }
//     });
// });

$(document).on('click', '.editButton', function() {
        var id = $(this).data('href');
        $.ajax({
            url: '{{ url('admin/users') }}/' + id + '/edit',
            method: 'GET',
            success: function(response) {
                $('.ajax-modal').modal('show');
                $('#modal-title').html('Edit Users');
                $('#saveBtn').html('Update');
                $('#user_id').val(response.id);
                $('#name').val(response.name);
                $('#email').val(response.email);

                $('#role').val(response.role_id).change();
            }
        });
    });

    // $(document).on('click', '.editButton', function() {
    //     var id = $(this).data('href');
    //     $.ajax({
    //         url: '{{ url('admin/users') }}/' + id + '/edit',
    //         method: 'GET',
    //         success: function(response) {
    //             $('.ajax-modal').modal('show');
    //             $('#modal-title').html('Edit Sub Admin');
    //             $('#saveBtn').html('Update');
    //             $('#user_id').val(response.id);
    //             $('#name').val(response.name);
    //             $('#email').val(response.email);
    //         }
    //     });
    // });

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
                    url: '{{ url('admin/users/delete') }}/' + id,
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
