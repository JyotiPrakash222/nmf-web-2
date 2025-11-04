@extends('layouts.adminNew')

@section('content')
<div class="container mt-4">
    <h3 class="text-center mb-4">Edit Candidate</h3>

    <form action="{{ route('candidates.update', $candidate->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Party</label>
            <select name="party_id" class="form-control" required>
                <option value="">Select Party</option>
                @foreach($parties as $party)
                    <option value="{{ $party->id }}" {{ $candidate->party_id == $party->id ? 'selected' : '' }}>
                        {{ $party->party_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Candidate Name</label>
            <input type="text" name="candidate_name" class="form-control" value="{{ $candidate->candidate_name }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Area</label>
            <input type="text" name="area" class="form-control" value="{{ $candidate->area }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Candidate Image</label>
            <input type="file" name="candidate_image" class="form-control">
            @if($candidate->candidate_image)
                <img src="{{ asset($candidate->candidate_image) }}" width="80" height="80" class="mt-2">
            @endif
        </div>

        <button type="submit" class="btn btn-success">Update Candidate</button>
        <a href="{{ route('candidates.list') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection

