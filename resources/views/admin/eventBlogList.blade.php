<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

@extends('layouts.adminNew')

@push('style')
    <link href="{{ asset('asset/new_admin/css/main_style.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <style>
        .action_btn {
            padding-block: 3.5px;
            padding-inline: 8.5px;
            border-radius: 4px;
            color: #fff;
            font-size: 15px;
            cursor: pointer;
        }

        .btn_view {
            background-color: #0381cf;
        }

        .btn_view:hover {
            background-color: #0577bd;
            color: #fff;
        }

        .btn_edit {
            background-color: #0381cf;
            margin-left: 1.5px
        }

        .btn_edit:hover {
            background-color: #0577bd;
            color: #fff;
        }

        .btn_delete {
            background-color: #ff0000;
            margin-left: 1.5px
        }

        .btn_delete:hover {
            background-color: #ca0808;
            color: #fff;
        }

        .status-unknown {
            background-color: #e2e3e5;
            color: #6c757d;
        }


        @media (max-width: 500px) {

            .btn_edit,
            .btn_delete {
                margin-left: 5px
            }
        }
    </style>

    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">

                        <div class="card px-3">
                            <div class="card-header d-flex justify-content-between align-items-center px-5 py-2.5">
                                <h3 class="card-title mb-0 fs-5">
                                    Blogs for Event: {{ $event->title }}
                                </h3>
                            </div>

                            <div class="row pt-4 px-3 px-md-5 gap-2 gap-md-0">
                                <div class="col-sm-12 col-md-6">
                                    <form method="GET" class="d-inline-block">
                                        <label class="d-inline-flex gap-1 align-items-center">
                                            Show
                                            <select name="perPage" class="form-select select-down"
                                                onchange="this.form.submit()">
                                                <option value="30" {{ $perPage == 30 ? 'selected' : '' }}>30</option>
                                                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                            </select> entries
                                        </label>
                                        <input type="hidden" name="title" value="{{ $title }}">
                                    </form>
                                </div>

                                <div class="col-sm-12 col-md-6 d-flex justify-content-end align-items-center">
                                    <form method="GET" class="btn-group d-flex gap-2 m-0 flex-wrap flex-md-nowrap">
                                        <input class="form-control" type="text" value="{{ $title }}"
                                            placeholder="Search Blog Title" name="title" />
                                        <input type="hidden" name="perPage" value="{{ $perPage }}">
                                        <button class="btn btn-outline-primary" type="submit">
                                            <i class="fa-solid fa-magnifying-glass"></i>
                                        </button>
                                    </form>
                                    <a href="{{ url('/events/add-eventblog') }}" class="--btn" style="background: #28364f; margin-left:15px; padding: 9px 12px; color: #fff;">
                                        Link Blog to Event
                                    </a>
                                </div>
                            </div>

                            <!-- Table -->
                            <div class="card-body table-responsive py-2 px-0">
                                <table class="table text-nowrap event-table">
                                    <thead>
                                        <tr>

                                            <th>Action</th>
                                            <th>ID</th>
                                            <th>Sequence</th>
                                            <th>Blog Title</th>
                                            <th>Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($blogs->count() > 0)
                                            @foreach ($blogs as $blog)
                                                <tr>
                                                    <td class="text-align-center">
                                                        <a class="act_btn ms-3" href="{{ url('events/edit-eventblog/' . $blog->pivot->id) }}">
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </a>

                                                    </td>
                                                    <td>{{ $blog->id }}</td>
                                                    <td>{{ $blog->pivot->sort_order }}</td>
                                                    <td class="event-ttl text-left">{{ $blog->name }}
                                                    </td>
                                                    {{-- @if ($loop->iteration < 3)--}}
                                                   
                                                            {{-- @endif --}}
                                                            <!-- NL1034:23Sep2025: add remove button-->
                              
                                    <td><a class="btn btn-sm btn-danger" 
                                                            href="{{ asset('events/delete-confirmeventblog/') }}/{{ $blog->pivot->id }}" 
                                                            style="margin-left:10px;">
                                                            &times;
                                                            </a></td>
                                                          
                                                
                                        
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="2">No Blogs Found</td>
                                            </tr>
                                        @endif
                                        
                                        
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="row pagination-block px-5">
                                <div class="col-sm-12 col-md-5">
                                    <div class="dataTables_info">
                                        @if ($blogs->count() > 0)
                                            Showing {{ $blogs->firstItem() }} to {{ $blogs->lastItem() }}
                                            of {{ $blogs->total() }} blogs
                                        @else
                                            Showing 0 blogs
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-7 d-flex justify-content-end">
                                    {{ $blogs->links() }}
                                </div>
                            </div>

                        </div> <!-- card -->
                    </div> <!-- col -->
                </div> <!-- row -->
            </div> <!-- container -->
        </section>
    </div>
@endsection
