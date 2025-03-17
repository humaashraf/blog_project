@extends('backend.layouts.admin')
@section('title', 'Posts Reports')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4"><i class="fas fa-chart-bar"></i> Post Report</h2>

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('backend.admin.posts.report') }}" class="bg-light p-3 rounded shadow-sm">
        <div class="row g-3 align-items-center">
            <div class="col-md-3">
                <label class="form-label fw-bold"><i class="fas fa-calendar-alt"></i> Start Date:</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold"><i class="fas fa-calendar-alt"></i> End Date:</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold"><i class="fas fa-user"></i> Author:</label>
                {{-- <select name="author" class="form-select">
                    <option value="">Select Author</option>
                    @foreach($posts->unique('author_id') as $post)
                        <option value="{{ $post->author_id }}">{{ $post->author_id }}</option>
                    @endforeach
                </select> --}}
                <select name="author" class="form-select">
                    <option value="">Select Author</option>
                    @foreach($posts->unique('author_id') as $post)
                        <option value="{{ $post->author_id }}">{{ $post->author->name }}</option>
                    @endforeach
                </select>

            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold"><i class="fas fa-file-alt"></i> Filter Posts</label>
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Filter</button>
            </div>
        </div>
    </form>

    {{-- Data Table --}}
    <div class="table-responsive mt-4">
        <table class="table table-bordered table-hover shadow-sm">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach($posts as $post)
                <tr>
                    <td>{{ $post->id }}</td>
                    <td>{{ $post->title }}</td>
                    {{-- <td>{{ $post->author_id }}</td> --}}
                    <td>{{ $post->author->name ?? 'N/A' }}</td>
                    <td>{{ $post->created_at->format('d-M-Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Export Buttons --}}
    <div class="d-flex justify-content-end mt-3">
        {{-- <a href="{{ route('backend.admin.posts.export.pdf') }}" class="btn btn-danger"><i class="fas fa-file-pdf"></i> Export to PDF</a> --}}
        <a href="{{ route('backend.admin.posts.export.pdf', request()->all()) }}" class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> Export to PDF
        </a>

    </div>
</div>
@endsection
