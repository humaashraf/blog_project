@extends('backend.layouts.admin')

@section('title', 'User Contact Data')

@section('content')
<h1>User Contact Data</h1>
<meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">Contact</div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="newsletter-table" class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Message</th>
                                {{-- <th>Actions</th> --}}
                                <th scope="col">
                                    @if (auth()->user()->roles->first()->hasPermissionTo('backend.admin.contacts.destroy'))
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
{{-- <h1 class="mt-4">User Contact Data</h1>
<ol class="breadcrumb">
    <li class="breadcrumb-item active">Contact</li>
</ol> --}}

{{-- <meta name="csrf-token" content="{{ csrf_token() }}">

<div class="row">
    <div class="col-md-8 offset-2" style="margin-top: 50px;">
        <table id="newsletter-table" class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Message</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div> --}}

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // CSRF Token Setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Initialize DataTable
    var table = $('#newsletter-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('backend.admin.contacts.indexform') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'phone', name: 'phone' },
            { data: 'message', name: 'message' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        error: function(xhr, error, thrown) {
            console.log(xhr.responseText);
        }
    });

    // Delete Button Functionality
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
                    url: "/admin/contactform/" + id,
                    type: "DELETE",
                    success: function(response) {
                        Swal.fire({
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
                            text: xhr.responseJSON.error || "Something went wrong!",
                            icon: "error",
                            confirmButtonText: "OK"
                        });
                    }
                });
            }
        });
    });

});
</script>
@endsection
