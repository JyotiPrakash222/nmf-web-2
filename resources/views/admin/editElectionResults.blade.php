@extends('layouts.adminNew')

@section('content')
<div class="container">
    <h3>Edit Election Result</h3>

    <form action="{{ route('updateElection', $result->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Party Name</label>
            <input type="text" name="party_name" value="{{ $result->party_name }}" class="form-control">
        </div>
        <div class="mb-3">
            <label>Abbreviation</label>
            <input type="text" name="abbreviation" value="{{ $result->abbreviation }}" class="form-control">
        </div>
        <div class="mb-3">
            <label>Alliance</label>
            <input type="text" name="alliance" value="{{ $result->alliance }}" class="form-control">
        </div>
        <div class="mb-3">
            <label>Seats Won</label>
            <input type="number" name="seats_won" value="{{ $result->seats_won }}" class="form-control">
        </div>
       
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('showElectionResults') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
