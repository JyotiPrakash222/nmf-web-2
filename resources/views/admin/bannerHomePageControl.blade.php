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
            /* cursor: grab; */
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

        #loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            /* <-- nice semi-transparent black */
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .loader {
            border: 6px solid #f3f3f3;
            border-top: 6px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        .form-check-input {
            width: 2.5em !important;
            height: 1.5em !important;
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
                                <h3 class="card-title mb-0 fs-5">Banner Home Page Control</h3>
                                <div class="card-tool">
                                </div>
                            </div>
                            <div class="row pt-4 px-5">
                                <div id="loader-overlay">
                                    <div class="loader"></div>
                                </div>
                                <div class="col-sm-12">
                                    {{-- Switch for "Show Banner above Top News" --}}
                                    <div
                                        class="form-check form-switch d-flex align-items-center p-2 border rounded bg-light shadow-sm mb-2 col-md-4">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            id="toggleActiveStatus{{ $homeSectionStatus->id }}"
                                            name="active_status_{{ $homeSectionStatus->id }}" style="margin-left: 0px;"
                                            {{ $homeSectionStatus->status == 1 ? 'checked' : '' }}
                                            onchange="updateShowBannerInTop({{ $homeSectionStatus->id }}, this.checked)">

                                        <label class="form-check-label ms-3 fw-semibold text-dark" style="font-size: 1rem;"
                                            for="toggleActiveStatus{{ $homeSectionStatus->id }}">
                                            Show Banner above <span class="text-primary">Top News</span>
                                        </label>
                                    </div>

                                    {{-- Banner List --}}
                                    <div class="col-md-12">
                                        <form id="banner-getting-form">
                                            @csrf
                                            <ul id="menu-list">
                                                @foreach ($get_banner as $banner)
                                                    <li
                                                        class="d-flex gap-3 col-12 col-md-4 align-items-center --btn text-dark rounded">
                                                        <input type="checkbox" name="show_banner[]"
                                                            value="{{ $banner->id }}"
                                                            {{ $banner->status == 1 ? 'checked' : '' }}
                                                            onchange="submitBannerStatus()">
                                                        {{ $banner->title }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                            
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

    <script>
        function submitBannerStatus() {
            const form = document.getElementById('banner-getting-form');
            const formData = new FormData(form);
            const enabledBannerIds = formData.getAll('show_banner[]');

            const loader = document.getElementById('loader-overlay');
            if (loader) loader.style.display = 'flex';

            fetch('/bannersequence', {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                        "Content-Type": "application/json",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({
                        show_banner: enabledBannerIds
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        setTimeout(() => {
                            location.reload();
                        }, 300);
                    } else {
                        if (loader) loader.style.display = 'none';
                    }
                })
                .catch(error => {
                    if (loader) loader.style.display = 'none';
                    console.error("Error:", error);
                });
        }
    </script>


    <script>
        function updateShowBannerInTop(sectionId, isChecked) {
            const status = isChecked ? 1 : 0;

            document.getElementById('loader-overlay').style.display = 'flex';
            fetch('/bannersequence/bannerUpdate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        section_id: sectionId,
                        active_status: status
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        setTimeout(() => {
                            location.reload();
                        }, 300);
                    } else {
                        document.getElementById('loader-overlay').style.display = 'none';
                    }
                })
                .catch(error => {
                    document.getElementById('loader-overlay').style.display = 'none';
                });
        }
    </script>
@endsection
