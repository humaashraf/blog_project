@extends('backend.layouts.admin')

@section('title', 'Visitors')

@section('content')
<h1>Visitors</h1>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">Visitors</div>

                    </div>
                </div>
                <div class="card-body">
                    <table id="visitor-table" class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                {{-- <th>Post Title</th> --}}
                                <th>Page Name</th>
                                <th>Visitor IP</th>
                                <th>Visited At</th>
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
        var table = $('#visitor-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('backend.admin.visitors.index') }}",
            columns: [
                { data: 'id', name: 'id' },
                // { data: 'post_id', name: 'post_id' },
                { data: 'page_name', name: 'page_name' },
                { data: 'visitor_ip', name: 'visitor_ip' },
                { data: 'visited_at', name: 'visited_at' },
            ]
        });


    });
</script>
@endsection
