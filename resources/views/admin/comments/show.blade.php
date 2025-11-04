@extends('layouts.adminNew')
@section('content')
<link rel="stylesheet" href="{{ asset('asset/new_admin/css/main_style.css') }}">
<div class=" mt-4 px-3">
    <h2 class="cm-h">{{ $blog->name }}</h2>
    <h3 class="my-2">Comments List</h3>
    
    <!-- Table WITHOUT nested forms -->
    <div class="responsive-custom">
    <table class="table table-comment text-nowrap mt-3">
        <thead>
            <tr>
                <th><input type="checkbox" onclick="$('.comment-select').prop('checked', this.checked)"></th>
                <th>Viewer</th>
                <th>Body</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($comments as $comment)
            <tr>
                <td><input type="checkbox" class="comment-select" name="selected[]" value="{{ $comment->id }}" form="bulk-form"></td>
                <td>{{ $comment->viewer->name ?? 'Anonymous' }}</td>
                <td>{{ $comment->body }}</td>
                <td>
                    @php
                        $status = strtolower($comment->moderation_status);
                        $statusClass = match ($status) {
                            'pending' => 'status status-pending',     
                            'approved' => 'status status-active',
                            'rejected' => 'status status-inactive',
                            default => 'bg-secondary text-white',
                        };
                    @endphp
                    <span class="px-2 py-1 rounded {{ $statusClass }}">{{ ucfirst($status) }}</span>
                </td>
                <td>
                    @if($comment->isPending())
                        <!-- Individual action forms (NOT nested) -->
                        <form method="POST" action="{{ route('admin.comments.approve', $comment->id) }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm" title="Approve">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.comments.reject', $comment->id) }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm" title="Reject">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('admin.comments.edit', $comment->id) }}" class="ed"  title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form method="POST" action="{{ route('admin.comments.destroy', $comment->id) }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="dl-btn" title="Delete" 
                                onclick="return confirm('Are you sure you want to delete this comment?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    </div>

    <!-- Bulk action form SEPARATE from table -->
    <form id="bulk-form" method="POST" action="{{ route('admin.comments.bulkAction') }}">
        @csrf
        <div class="ac-wrap">
            <button type="submit" name="action" value="approve" class="btn btn-success " title="Bulk Approve">
                <i class="fas fa-check me-1"></i>  Bulk Approve
            </button>
            <button type="submit" name="action" value="reject" class="btn btn-warning " title="Bulk Reject">
                <i class="fas fa-times me-1"></i>  Bulk Reject
            </button>
            <button type="submit" name="action" value="delete" class="btn btn-dark" title="Bulk Delete"
                    onclick="return confirm('Are you sure you want to delete selected comments?')">
                <i class="fas fa-trash me-1 "></i>  Bulk Delete
            </button>
        </div>
    </form>
    
    {{ $comments->links() }}
</div>
@endsection
