@extends('backend.layouts.admin')
@section('title', 'Categories Report')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4"><i class="fas fa-chart-bar"></i> Categories Report</h2>

    {{-- Data Table --}}
    <div class="table-responsive mt-4">
        <table class="table table-bordered table-hover shadow-sm">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Category Name</th>
                    <th>Type</th>
                    <th>Number of Posts</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->type }}</td>
                    <td>{{ $category->posts->count() }}</td> {{-- Count posts for each category --}}
                    <td>{{ $category->created_at->format('d-M-Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Export Buttons --}}
    <div class="d-flex justify-content-end mt-3">
        <a href="{{ route('backend.admin.categories.export_pdf') }}" class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> Export to PDF
        </a>
    </div>
</div>
@endsection
