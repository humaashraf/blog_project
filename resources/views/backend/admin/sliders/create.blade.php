@extends('backend.layouts.admin')

@section('title', 'Sliders')

@section('content')
<h1>Sliders</h1>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">Sliders</div>
                        <div class="col " >
                            @if (auth()->user()->roles->first()->hasPermissionTo('backend.admin.sliders.store'))
                            <a class="btn btn-info mb-3" style="float: right" data-bs-toggle="modal" data-bs-target="#exampleModal" id="add_slider">Add new Slider</a>
                            @endif
                            {{-- <a class="btn btn-info mb-3" style="float: right" data-bs-toggle="modal" data-bs-target="#exampleModal" id="add_slider">Add new Slider</a> --}}
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
                                    @canany(['backend.admin.sliders.edit', 'backend.admin.sliders.delete'])
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
{{-- <h1 class="mt-4">Sliders</h1>
<ol class="breadcrumb">
    <li class="breadcrumb-item active">Sliders</li>
</ol> --}}
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

{{-- <div class="row">
    <div class="col-md-6 offset-3" style="margin-top: 100px;">
        <a class="btn btn-info mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal" id="add_slider">Add new Slider</a>
        <table id="slider-table" class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Image</th>
                    <th scope="col">Sort by</th>
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

        // Show data
        var table = $('#slider-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('backend.admin.sliders.index') }}",
            columns: [
                { data: 'id', name: 'id' },
                {
                    data: 'slider_image',
                    name: 'slider_image',
                    render: function(data) {
                        // Assuming your image is stored in the public folder and your file path is relative
                        return '<img src="/uploads/' + data + '" alt="Slider Image" width="100" height="60">';
                    }
                },
                { data: 'sort_by', name: 'sort_by' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        // Save data
        // $('#saveBtn').click(function() {
        //     $('.error-messages').html(''); // Clear previous errors

        //     var formData = new FormData($('#ajaxForm')[0]);

        //     // Ensure the image file is appended correctly
        //     var fileInput = $('#slider_image')[0];
        //     if (fileInput.files.length > 0) {
        //         formData.append('slider_image', fileInput.files[0]);
        //     }

        //     $.ajax({
        //         url: '{{ route('backend.admin.sliders.store') }}',
        //         method: 'POST',
        //         processData: false,
        //         contentType: false,
        //         data: formData,
        //         success: function(response) {
        //             $('#slider_image').val(''); // Reset file input
        //             $('#sort_by').val('');
        //             $('#slider_id').val(''); // Reset hidden input
        //             $('.ajax-modal').modal('hide'); // Hide modal
        //             Swal.fire({
        //                 title: "Success!",
        //                 text: response.success,
        //                 icon: "success",
        //                 confirmButtonText: "OK"
        //             });
        //             table.ajax.reload(); // Reload table data
        //         },
        //         error: function(error) {
        //             if (error.status == 422) {
        //                 var response = JSON.parse(error.responseText);
        //                 var slider_imageError = response.errors.slider_image;
        //                 $('#slider_imageError').html(slider_imageError); // Show error for image upload
        //             }
        //         }
        //     });
        // });
        $('#saveBtn').click(function() {
    $('.error-messages').html(''); // Clear previous errors

    var sliderId = $('#slider_id').val();
    var formData = new FormData($('#ajaxForm')[0]);

    var url = sliderId ? '{{ url("admin/sliders/update") }}/' + sliderId : '{{ route('backend.admin.sliders.store') }}';
    var method = sliderId ? 'POST' : 'POST';

    // Agar sirf `sort_by` update karna hai
    if (sliderId) {
        formData = new FormData(); // Naya FormData create karo
        formData.append('sort_by', $('#sort_by').val()); // Sirf sort_by add karo
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
            // $('body').on('click', '.editButton', function() {
                $(document).on('click', '.editButton', function() {
                var id = $(this).data('href');

                console.log(id)
                function capitalizeFirstLetter(string) {
                    return string.charAt(0).toUpperCase() + string.slice(1);
                }
                $.ajax({
                    url: '{{ url('admin/sliders', '') }}' + '/' + id + '/edit',
                    method: 'GET',
                    success: function(response) {
                        $('.ajax-modal').modal('show');
                        $('#modal-title').html('Edit Slider');
                        $('#saveBtn').html('Update Slider');
                        $('#slider_id').val(response.id);
                        $('#sort_by').val(response.sort_by);
                        // var type = capitalizeFirstLetter(response.type);
                        // $('#type').val(response.type);
                         // Hide the image input field in edit mode
            $('#slider_image').closest('.form-group').hide();


                        // console.log(response.type);

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
                        url: '{{ url('admin/sliders/delete') }}/' + id,
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
