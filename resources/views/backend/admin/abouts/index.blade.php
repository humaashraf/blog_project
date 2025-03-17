@extends('backend.layouts.admin')

@section('title', 'About Us')

@section('content')
<div class="row mt-5">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h4>Edit About Settings</h4>
            </div>
            <div class="card-body">
                <form id="ajaxForm">
                    <input type="hidden" name="about_id" id="about_id" value="{{$about->id}}">
                    <div class="d-flex">
                        <div class="form-group m-3 col-md-5">
                            <label for="name">About Name</label>
                            <input type="text" name="name" id="name" value="{{$about->name}}" class="form-control">
                            <span id="nameError" class="text-danger error-messages"></span>
                        </div>

                        <div class="form-group m-3 col-md-5">
                            <label for="image">About Image</label>
                            <input type="file" name="image" id="image" class="form-control">
                            <img id="image-preview" src="{{ asset('uploads/' . $about->image) }}" alt="About Image" class="img-thumbnail mt-2" width="50">
                            <span id="imageError" class="text-danger error-messages"></span>
                        </div>
                        </div>

                    {{-- <div class="d-flex"> --}}
                        <div class="form-group m-3 col-md-5">
                            <label for="description">Description</label>
                            <textarea name="description" rows="5" cols="50" id="description" class="form-control">{{$about->description}}</textarea>
                            <span id="descriptionError" class="text-danger error-messages"></span>
                        </div>



                    <button type="button" class="btn btn-primary" id="saveBtn">Update Settings</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    });

    $('#saveBtn').click(function() {
        $('.error-messages').html('');
        var id = $('#about_id').val();
        if (!id) {
            console.error("Error: ID is missing while updating!");
            return;
        }

        var url = "{{ route('backend.admin.abouts.update', ':id') }}".replace(':id', id);
        var formData = new FormData($('#ajaxForm')[0]);
        formData.append('_method', 'PUT');

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.fire("Success!", response.success, "success");

                // ** Update image previews dynamically without refresh **
                if (response.image) {
                    $('#image-preview').attr('src', response.image);
                }
            },

            error: function(error) {
                if (error.status == 422) {
                    var response = JSON.parse(error.responseText);
                    $.each(response.errors, function(key, value) {
                        $('#' + key + 'Error').html(value[0]);
                    });
                }
            }
        });
    });
});
</script>
@endsection
