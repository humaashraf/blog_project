@extends('backend.layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="row mt-5">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h4>Edit Settings</h4>
            </div>
            <div class="card-body">
                <form id="ajaxForm">
                    <input type="hidden" name="setting_id" id="setting_id" value="{{$setting->id}}">
                    <div class="d-flex">
                        <div class="form-group m-3 col-md-3">
                            <label for="blog_name">Blog Name</label>
                            <input type="text" name="blog_name" id="blog_name" value="{{$setting->blog_name}}" class="form-control">
                            <span id="blog_nameError" class="text-danger error-messages"></span>
                        </div>

                        <div class="form-group m-3 col-md-3">
                            <label for="favicon_image">Favicon Image</label>
                            <input type="file" name="favicon_image" id="favicon_image" class="form-control">
                            <img id="favicon-preview" src="{{ asset('uploads/' . $setting->favicon_image) }}" alt="Favicon" class="img-thumbnail mt-2" width="50">
                            <span id="favicon_imageError" class="text-danger error-messages"></span>
                        </div>

                        <div class="form-group m-3 col-md-3">
                            <label for="logo_image">Logo Image</label>
                            <input type="file" name="logo_image" id="logo_image" class="form-control">
                            <img id="logo-preview" src="{{ asset('uploads/' . $setting->logo_image) }}" alt="Logo" class="img-thumbnail mt-2" width="100">
                            <span id="logo_imageError" class="text-danger error-messages"></span>
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="form-group m-3 col-md-4">
                            <label for="description">Description</label>
                            <textarea name="description" rows="5" cols="50" id="description" class="form-control">{{$setting->description}}</textarea>
                            <span id="descriptionError" class="text-danger error-messages"></span>
                        </div>

                        <div class="form-group m-3 col-md-4">
                            <label for="social_links">Social Links</label>
                            <textarea name="social_links" rows="5" cols="50" id="social_links" class="form-control">{{$setting->social_links}}</textarea>
                            <span id="social_linksError" class="text-danger error-messages"></span>
                        </div>
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
        var id = $('#setting_id').val();
        if (!id) {
            console.error("Error: ID is missing while updating!");
            return;
        }

        var url = "{{ route('backend.admin.settings.update', ':id') }}".replace(':id', id);
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
                if (response.favicon_image) {
                    $('#favicon-preview').attr('src', response.favicon_image);
                }
                if (response.logo_image) {
                    $('#logo-preview').attr('src', response.logo_image);
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
