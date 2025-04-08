@extends('backend.layouts.admin')

@section('title', 'Contact Slider')

@section('content')
<h1>Contact Sliders</h1>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">Contact Sliders</div>
                        <div class="col " >
                            @if (auth()->user()->roles->first()->hasPermissionTo('backend.admin.contacts.store'))
                            <a class="btn btn-info mb-3" style="float: right" data-bs-toggle="modal" data-bs-target="#exampleModal" id="add_slider">Add new Slider</a>
                            @endif
                         </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="slider-table" class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Image</th>
                                <th scope="col">Sort by</th>
                                {{-- <th scope="col">Actions</th> --}}
                                <th scope="col">
                                    @canany(['backend.admin.contacts.edit', 'backend.admin.contacts.delete'])
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

<div class="modal fade ajax-modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <form id="ajaxForm">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Create Slider</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="slider_id" id="slider_id">
                    <div class="form-group mb-3">
                        <label for="slider_image">Upload Image</label>
                        <input type="file" name="slider_image" id="slider_image" class="form-control">
                        <span id="slider_imageError" class="text-danger error-messages"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Sort by</label>
                        <input type="number" name="sort_by" id="sort_by" class="form-control">
                        <span id="sort_byError" class="text-danger error-messages"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveBtn">Save Slider</button>
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
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Show data
        var table = $('#slider-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('backend.admin.contacts.index') }}",
            columns: [
                { data: 'id', name: 'id' },
                {
                    data: 'slider_image',
                    name: 'slider_image',
                    render: function(data) {
                        // Assuming your image is stored in the public folder and your file path is relative
                        return '<img src="/uploads/' + data + '" alt="Slider Image" width="60" height="60">';
                    }
                },
                { data: 'sort_by', name: 'sort_by' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });


    $('#saveBtn').click(function() {
    $('.error-messages').html(''); // Clear previous errors

    var sliderId = $('#slider_id').val();
    var formData = new FormData($('#ajaxForm')[0]);

    var url = sliderId ? '{{ url("admin/contacts/update") }}/' + sliderId : '{{ route('backend.admin.contacts.store') }}';
    var method = sliderId ? 'POST' : 'POST';

    // If only `sort_by` updates
    if (sliderId) {
        formData = new FormData(); // New FormData created
        formData.append('sort_by', $('#sort_by').val()); // only sort_by added
        formData.append('_token', $('meta[name="csrf-token"]').attr('content')); // CSRF Token
    }

    $.ajax({
        url: url,
        method: method,
        processData: false,
        contentType: false,
        data: formData,
        success: function(response) {
            $('#ajaxForm')[0].reset(); // Reset form
            $('.ajax-modal').modal('hide'); // Hide modal
            Swal.fire({
                title: "Success!",
                text: response.success,
                icon: "success",
                confirmButtonText: "OK"
            });
            table.ajax.reload(); // Reload table data
        },
        error: function(error) {
            if (error.status == 422) {
                var response = JSON.parse(error.responseText);
                if (response.errors.sort_by) {
                    $('#sort_byError').html(response.errors.sort_by);
                }
            }
        }
    });
});



                //Edit button
                $(document).on('click', '.editButton', function() {
                var id = $(this).data('href');

                console.log(id)
                function capitalizeFirstLetter(string) {
                    return string.charAt(0).toUpperCase() + string.slice(1);
                }
                $.ajax({
                    url: '{{ url('admin/contacts', '') }}' + '/' + id + '/edit',
                    method: 'GET',
                    success: function(response) {
                        $('.ajax-modal').modal('show');
                        $('#modal-title').html('Edit Slider');
                        $('#saveBtn').html('Update Slider');
                        $('#slider_id').val(response.id);
                        $('#sort_by').val(response.sort_by);

                        $('#slider_image').closest('.form-group').hide();


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
                        url: '{{ url('admin/contacts/delete') }}/' + id,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            table.ajax.reload(); // Reload table after deletion
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

        // Show modal for creating slider
        $('#add_slider').click(function() {
            $('#modal-title').html('Create Slider');
            $('#saveBtn').html('Save Slider');
            $('.error-messages').html(''); // Clear error messages

    // Show the image input field in create mode
    $('#slider_image').closest('.form-group').show();
        });

        // Reset form on modal close
        $(document).on('hidden.bs.modal', '#exampleModal', function() {
            $('#ajaxForm')[0].reset();
            $('#slider_id').val('');
            $('.error-messages').html('');
            $('#modal-title').html('Create Slider');
            $('#saveBtn').html('Save Slider');
        });
    });
</script>

@endsection
