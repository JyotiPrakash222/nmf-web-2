@extends('layouts.adminNew')
@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">

    <link href="{{ asset('asset/new_admin/css/main_style.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="px-5 mt-4">
    <h3>Edit Blog Event</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('editEventBlog', ['id' => $eventBlog->id]) }}" method="POST">
        @csrf

        <!-- Event Dropdown -->
        <div class="mb-3">
            <label for="event_id" class="form-label">Select Event</label>
            <select name="event_id" id="event_id" class="form-select" required>
                <option value="">-- Select Event --</option>
                @foreach($events as $event)
                    <option value="{{ $event->id }}" {{ $event->id == $eventBlog->big_event_id ? 'selected' : '' }}>{{ $event->title }}</option>
                @endforeach
            </select>
            
            @error('event_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <!-- Blog ID Input -->
        <div class="mb-3">
            <label for="blog_id" class="form-label">Enter Blog ID</label>
            <input type="number" name="blog_id" id="blog_id" class="form-control" placeholder="Blog ID" value="{{ $eventBlog->blog_id }}" required>
            @error('blog_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <!-- Sort Order -->
        <div class="mb-3">
            <label for="sort_order" class="form-label">Sort Order (optional)</label>
            <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ $eventBlog->sort_order }}" placeholder="e.g. 1">
            @error('sort_order') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-dark">Update Blog</button>
    </form>
</div>
@endsection
