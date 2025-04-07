@extends('backend.layouts.admin')

@section('title', 'Roles')

@section('content')
<h1>Manage Roles</h1>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">Manage Roles</div>
                        <div class="col " >
                            <a class="btn btn-info mb-3" data-bs-toggle="modal" style="float: right;" data-bs-target="#exampleModal" id="addRoleBtn">Add Role</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="role-table" class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th>Name</th>
                                <th>Permissions</th>
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
{{-- <div class="container">
    <h2>Roles Management</h2>
    <button class="btn btn-success" id="addRoleBtn">Add Role</button>

    <table id="rolesTable" class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Permissions</th>
                <th>Actions</th>
            </tr>
        </thead>
    </table>
</div> --}}

<!-- Add/Edit Modal -->
{{-- <div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="roleModalLabel">Manage Role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="roleForm">
                    @csrf
                    <input type="hidden" id="roleId">

                    <div class="form-group">
                        <label for="roleName">Role Name</label>
                        <input type="text" id="roleName" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Permissions</label><br>
                        @foreach($permissions as $permission)
                            <div class="form-check">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="form-check-input permissionCheckbox">
                                <label class="form-check-label">{{ $permission->name }}</label>
                            </div>
                        @endforeach
                    </div>

                    <button type="submit" class="btn btn-primary" id="saveRole">Save</button>
                </form>
            </div>
        </div>
    </div>
</div> --}}

<div class="modal fade ajax-modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
aria-hidden="true">

<form id="ajaxForm">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Manage Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- <input type="hidden" name="d" id="role_id"> --}}
                <input type="hidden" name="roleId" id="roleId">

                    <div class="form-group">
                        <label for="roleName">Role Name</label>
                        <input type="text" id="roleName" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Permissions</label><br>
                        @foreach($permissions as $permission)
                            <div class="form-check">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="form-check-input permissionCheckbox">
                                <label class="form-check-label">{{ $permission->name }}</label>
                            </div>
                        @endforeach
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                {{-- <button type="button" class="btn btn-primary" id="saveBtn">Save Category</button> --}}
                <button type="submit" class="btn btn-primary" id="saveRole">Save</button>
            </div>
        </div>
    </div>
</form>
</div>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>


<script>
$(document).ready(function () {
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
    // Show data
    let table = $('#role-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('backend.admin.roles.index') }}",
        columns: [
            { data: 'id' },
            { data: 'name' },
            // { data: 'permissions' },
            {
            data: 'permissions',
            render: function(data, type, row) {
                if (data.length > 50) {
                    return data.substring(0, 50) + '...';
                }
                return data;
            }
        },
            { data: 'actions', orderable: false, searchable: false }
        ]
    });
    // Add Role Date
$('#addRoleBtn').click(function () {
    console.log('Add Role button clicked'); // Debugging log
    $('#ajaxForm')[0].reset(); // Reset form
    $('#roleId').val(''); // Ensure roleId is empty for new entries
    $('.permissionCheckbox').prop('checked', false); // Uncheck all permissions
    $('#exampleModal').modal('show'); // Show modal
});
// // Submit update and add Role data
// $('#ajaxForm').submit(function (e) {
//     e.preventDefault();
//     let id = $('#roleId').val();
//     let url = id ? "{{ url('admin/roles/update') }}/" + id : "{{ route('backend.admin.roles.store') }}";

//     $.ajax({
//         url: url,
//         method: "POST",
//         data: {
//             _token: "{{ csrf_token() }}",
//             name: $('#roleName').val(),
//             permissions: $('.permissionCheckbox:checked').map(function () {
//                 return $(this).val();
//             }).get()
//         },
//         success: function (response) {
//             $('#exampleModal').modal('hide');
//             $('#ajaxForm')[0].reset(); // Reset form after submission
//             table.ajax.reload();
//         },
//         error: function (xhr) {
//             console.log(xhr.responseText); // Debugging
//         }
//     });
// });
// Submit update and add Role data
$('#ajaxForm').submit(function (e) {
    e.preventDefault();
    let id = $('#roleId').val(); // Get role ID
    let isUpdate = id ? true : false; // Check if it's an update
    let url = id ? "{{ url('admin/roles/update') }}/" + id : "{{ route('backend.admin.roles.store') }}";

    $.ajax({
        url: url,
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            name: $('#roleName').val(),
            permissions: $('.permissionCheckbox:checked').map(function () {
                return $(this).val();
            }).get()
        },
        success: function (response) {
            let successMessage = isUpdate ? "Role updated successfully!" : "Role created successfully!";

            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: successMessage,
                // timer: 2000,
                // showConfirmButton: false
                confirmButtonText: "OK"
            });

            $('#exampleModal').modal('hide');
            $('#ajaxForm')[0].reset(); // Reset form after submission
            table.ajax.reload();
        },
        error: function (xhr) {
            let errorMessage = "Something went wrong!";
            if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMessage = xhr.responseJSON.error;
            }

            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: errorMessage,
                confirmButtonText: "OK"

            });

            console.log(xhr.responseText); // Debugging
        }
    });
});




    // $('#roleForm').submit(function (e) {
    //     e.preventDefault();
    //     let id = $('#roleId').val();
    //     let url = id ? "{{ url('admin/roles/update') }}/" + id : "{{ route('backend.admin.roles.store') }}";

    //     $.ajax({
    //         url: url,
    //         method: "POST",
    //         data: {
    //             _token: "{{ csrf_token() }}",
    //             name: $('#roleName').val(),
    //             permissions: $('.permissionCheckbox:checked').map(function () {
    //                 return $(this).val();
    //             }).get()
    //         },
    //         success: function (response) {
    //             $('#roleModal').modal('hide');
    //             table.ajax.reload();
    //         }
    //     });
    // });

    // $(document).on('click', '.editRole', function () {
    //     let id = $(this).data('id');
    //     $.get("{{ url('admin/roles/edit') }}/" + id, function (data) {
    //         $('#roleId').val(data.id);
    //         $('#roleName').val(data.name);
    //         $('.permissionCheckbox').prop('checked', false);
    //         $.each(data.permissions, function (index, permission) {
    //             $('.permissionCheckbox[value="' + permission.id + '"]').prop('checked', true);
    //         });
    //         $('#roleModal').modal('show');
    //     });
    // });
    $(document).on('click', '.editRole', function () {
    let id = $(this).data('id');
    $.get("{{ url('admin/roles/edit') }}/" + id, function (data) {
        $('#roleId').val(data.id);
        $('#roleName').val(data.name);
        $('.permissionCheckbox').prop('checked', false);
        $.each(data.permissions, function (index, permission) {
            $('.permissionCheckbox[value="' + permission.id + '"]').prop('checked', true);
        });
        $('#exampleModal').modal('show');
    });
});


    // $(document).on('click', '.deleteRole', function () {
    //     if (!confirm('Are you sure?')) return;
    //     let id = $(this).data('id');

    //     $.ajax({
    //         url: "{{ url('admin/roles/delete') }}/" + id,
    //         method: "DELETE",
    //         data: { _token: "{{ csrf_token() }}" },
    //         success: function (response) {
    //             table.ajax.reload();
    //         }
    //     });
    // });
    $(document).on('click', '.deleteRole', function () {
    let id = $(this).data('id');

    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ url('admin/roles/delete') }}/" + id,
                method: "DELETE",
                data: { _token: "{{ csrf_token() }}" },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'Role has been deleted.',
                        confirmButtonText: "OK"
                    });
                    table.ajax.reload(); // Refresh DataTable
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong.',
                        confirmButtonText: "OK"
                    });
                    console.log(xhr.responseText);
                }
            });
        }
    });
});

});
</script>
@endsection
