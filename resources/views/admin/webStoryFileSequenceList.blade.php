@extends('layouts.adminNew')
@push('style')
    <link href="{{ asset('asset/new_admin/css/main_style.css') }}" rel="stylesheet" />
@endpush
@section('content')
    <style>
        #category-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        #category-list li {
            padding: 12px 16px;
            margin-bottom: 8px;
            background-color: #f1f1f1;
            border: 1px solid #ccc;
            border-radius: 6px;
            cursor: grab;
            transition: background-color 0.2s, transform 0.2s;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        #category-list li:hover {
            background-color: #e3e3e3;
        }

        #category-list li.sortable-chosen {
            background-color: #d6f0ff;
            transform: scale(1.02);
        }

        #category-list li.sortable-ghost {
            opacity: 0.5;
        }

        .headerList {
            padding: 10px;
            padding-left: 0px;
        }
    </style>


    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center px-5 py-">
                                <h3 class="card-title mb-0 fs-5">Web Story List</h3>
                                <div class="card-tool ">
                                </div>
                            </div>
                            <div class="row pt-4 px-5">
                                {{-- <div class="row"> --}}
                                <div class="col-md-12">
                                    <h4 class="headerList">Manage Each Web Stories File Sequence</h4>
                                    <ul id="category-list">
                                        @foreach ($webstories as $webstory)
                                            <li class="d-flex gap-3 col-12 col-md-4 align-items-center --btn text-dark rounded"
                                                data-id="{{ $webstory->id }}" data-category-id="{{ $webstory->categories_id }}">

                                                <span class="icon text-dark me-2"><i class="fas fa-grip-lines"></i></span>

                                                <div class="row w-100">
                                                    <div class="col-6">
                                                        {{ $webstory->name }}
                                                    </div>
                                                </div>

                                                <div class="actions">
                                                    <a href="{{ asset('webstory/file-sequence') }}/{{ $webstory->id }}"
                                                        class="btn btn-sm btn-primary">View</a>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                {{-- </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
