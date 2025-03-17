@extends('backend.layouts.admin')
@section('title', 'Top Viewed Posts Report')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-white bg-dark p-3 text-center rounded">
        <i class="fas fa-chart-bar"></i> Top Viewed Posts Report
    </h2>

    @foreach ($categories as $category)
        <h3 class="mt-4">{{ $category->name }}</h3>

        <div class="table-responsive">
            <table class="table table-bordered table-hover shadow-sm">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Post ID</th>
                        <th>Post Title</th>
                        <th>Views</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @forelse ($category->posts as $post)
                    <tr>
                        <td>{{ $post->id }}</td>
                        <td>{{ $post->title }}</td>
                        <td><strong>{{ $post->visitors_count }}</strong></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-danger">No posts available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endforeach

    {{-- Export Button --}}
    <div class="d-flex justify-content-end mt-3">
        <a href="{{ route('backend.admin.categories.top_viewed_posts.export_pdf') }}" class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> Export to PDF
        </a>
    </div>
</div>
@endsection
