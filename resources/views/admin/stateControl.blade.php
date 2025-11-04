@extends('layouts.adminNew')
@push('style')
    <link href="{{ asset('asset/new_admin/css/main_style.css') }}" rel="stylesheet" />
@endpush
@section('content')
    <style>
        #menu-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        #menu-list li {
            padding: 12px 16px;
            margin-bottom: 8px;
            background-color: #f1f1f1;
            border: 1px solid #ccc;
            border-radius: 6px;
            cursor: grab;
            transition: background-color 0.2s, transform 0.2s;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        #menu-list li:hover {
            background-color: #e3e3e3;
        }

        #menu-list li.sortable-chosen {
            background-color: #d6f0ff;
            transform: scale(1.02);
        }

        #menu-list li.sortable-ghost {
            opacity: 0.5;
        }
    </style>


    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <!-- /.row -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center px-5 py-">
                                <h3 class="card-title mb-0 fs-5">State Control List</h3>
                                <div class="card-tool ">
                                </div>
                            </div>
                            <div class="row pt-4 px-5">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form id="default-state-form" action="{{ route('defaultState.update') }}"
                                            method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label>
                                                    <input type="radio" name="default_state" value=""
                                                        {{ $states->where('is_default', 1)->isEmpty() ? 'checked' : '' }}>
                                                    No Default
                                                </label>
                                            </div>
                                            <ul id="menu-list">
                                                @foreach ($states as $state)
                                                    <li class="d-flex gap-3 col-12 col-md-4 align-items-center --btn text-dark rounded"
                                                        data-id="{{ $state->id }}">
                                                        <label class="d-flex align-items-center gap-2 mb-0">
                                                            <input type="radio" name="default_state"
                                                                value="{{ $state->id }}"
                                                                {{ $state->is_default ? 'checked' : '' }}>
                                                            {{ $state->name }}
                                                        </label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <button type="button" class="btn btn-primary mt-3"
                                                onclick="submitDefaultStateForm()">Save Default State</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        // Order sequence of state
        new Sortable(document.getElementById('menu-list'), {
            animation: 200,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            onEnd: function(evt) {
                let order = [];
                document.querySelectorAll('#menu-list li').forEach((el, index) => {
                    order.push({
                        id: el.getAttribute('data-id'),
                        sequence_id: index + 1
                    });
                });

                fetch('/statesequence', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            order
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    });
            }
        });

        // Default state select alert message
        function submitDefaultStateForm() {
            const selected = document.querySelector('input[name="default_state"]:checked');

            if (!selected) {
                alert('No state selected');
                return;
            }

            if (selected.value === "") {
                alert('No default state selected');
            } else {
                // Get the label text (state name) by traversing DOM
                const label = selected.closest('label');
                const stateName = label ? label.textContent.trim() : 'Selected';

                alert('Default state selected: ' + stateName);
            }

            document.getElementById('default-state-form').submit();
        }
    </script>
@endsection
