<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
@extends('layouts.adminNew')
@push('style')
    <link href="{{ asset('asset/new_admin/css/main_style.css') }}" rel="stylesheet" />
@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">

                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ asset('/home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ asset('/rashiphal') }}">Rashi</a></li>
                            <li class="breadcrumb-item active">Edit Rashi</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <!-- Main content -->
        <section class="content ">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header " style="padding-block: 20px;">
                                <h3 class="card-title mb-0 fs-5">Edit Rashi</h3>
                            </div>
                            <div class="uploads-container row">
                                <form action="{{ asset('rashiphal/edit') }}/{{ $rashiphal->id }}" method="post"
                                    class="col-md-10 mt-1">
                                    @csrf
                                    <div class=" input-field mb-4">
                                        <input placeholder="" autocomplete="off" type="text"
                                            value="{{ $rashiphal->name }}" name="name" id="name">
                                        <label for="name">Name</label>
                                        @error('name')
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                        {{ $errors->first('name') }}
                                                    </span>
                                                </div>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class=" input-field mb-4">
                                        <label for="name">Description</label>
                                        <textarea class="txtar"  name="description" id="description" rows="10" class="form-control">{{ $rashiphal->description }}</textarea>
                                        @error('description')
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                        {{ $errors->first('description') }}
                                                    </span>
                                                </div>
                                            </div>
                                        @enderror
                                    </div>
                                    <button class="--btn btn-submit mt-4 " type="submit">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
