@extends('backend.layouts.admin')

@section('title', 'Newsletter Subscribers')

@section('content')
<h1>Newsletter Subscribers</h1>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">Subscribers</div>

                    </div>
                </div>
                <div class="card-body">
                    <table id="newsletter-table" class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Subscribed At</th>
                                {{-- <th>Actions</th> --}}
                                <th scope="col">
                                    @canany(['backend.admin.newsletters.destroy'])
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

{{-- Scripts --}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        var table = $('#newsletter-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('backend.admin.newsletters.index') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
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
                url: "/admin/newsletters/" + id,
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
});


    });
</script>
@endsection
