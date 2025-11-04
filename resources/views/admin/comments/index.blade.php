@extends('layouts.adminNew')
<link rel="stylesheet" href="{{ asset('asset/new_admin/css/main_style.css') }}">
@push('styles')
    <style>
        .pagination .page-link {
            font-size: 0.9rem !important;
            padding: 0.25rem 0.5rem !important;
        }

        .pagination .page-link svg {
            width: 1.2em !important;
            height: 1.2em !important;
        }
        
    </style>

@endpush
@section('content')
    <div class="mt-4 px-3">
        <h2 style="text-transform: uppercase; color: rgb(95, 95, 95)">Comments Overview</h2>
        <table class="table table-bordered table-comment table-hover mt-3">
            <thead >
                <tr>
                    <th>Blog Name</th>
                    <th>Comments</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($blogs as $blog)
                    <tr>
                        <td>{{ \Illuminate\Support\Str::limit($blog->name, 100) }}
                            @if(strlen($blog->name) > 60)
                                <br>{{ \Illuminate\Support\Str::substr($blog->name, 100, 100) }}...
                            @endif
                        </td>
                        <td>{{ $blog->all_comments_count }}</td>
                         <td>
                        <div class="btn-group" role="group">
                            <!-- Preview Site Button -->
                            <a href="{{ $blog->full_url }}" 
                               target="_blank" 
                               class="btn" 
                               title="Preview Blog on Public Site">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                            
                            <!-- Show Comments Button -->
                            <a href="{{ route('admin.comments.show', $blog->id) }}" 
                               class="btn" 
                               title="Moderate Comments for this Blog">
                                <i class="fas fa-comments"></i>
                            </a>
                        </div>
                    </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No Activity found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            <nav aria-label="Blog pagination">
                <ul class="pagination pagination-sm">
                    {{ $blogs->links('pagination::bootstrap-4') }}
                </ul>
            </nav>
        </div>

    </div>
@endsection