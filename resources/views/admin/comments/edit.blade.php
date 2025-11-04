@extends('layouts.adminNew')
@section('content')
<div class="container mt-4">
    <h3>Edit Comment</h3>
    <form method="POST" action="{{ route('admin.comments.update', $comment->id) }}">
        @csrf
        <div class="mb-3 mt-2">
            <label for="commentBody" class="form-label">Comment Body</label>
            <textarea id="commentBody" name="body" class="form-control " rows="4" required>{{ old('body', $comment->body) }}</textarea>
            @error('body')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-dark">Update Comment</button>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
