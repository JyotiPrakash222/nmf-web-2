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
                            <li class="breadcrumb-item"><a href="{{ asset('/alluserslist') }}">All Users List</a></li>
                            <li class="breadcrumb-item">Change Password</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content ">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header " style="padding-block: 20px;">
                                <h3 class="card-title mb-0 fs-5">Change Password</h3>
                            </div>
                            <div class="uploads-container row px-1">
                                <form method="post"
                                    action="{{ asset('alluserslist/allusers-change-password') }}/{{ $user->id }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group row">
                                        <div class=" input-field col-md-6">
                                            <input placeholder="" autocomplete="off" type="text"
                                                value="{{ $user->name }}" name="name" id="name" disabled>
                                            <label for="name">Name</label>
                                        </div>
                                        <div class=" input-field col-md-6">
                                            <input placeholder="" autocomplete="off" type="email"
                                                value="{{ $user->email }}" name="email" id="email" disabled>
                                            <label for="email">Email</label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class=" input-field col-md-4">
                                            <input placeholder="" autocomplete="off" type="password"
                                                value="{{ old('password') }}" name="password" id="password">
                                            <label for="password">Old Password</label>
                                            @error('password')
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('password') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                        <div class=" input-field col-md-4">
                                            <input placeholder="" autocomplete="off" type="password"
                                                value="{{ old('new_password') }}" name="new_password" id="new_password">
                                            <label for="new_password">New Password</label>
                                            @error('new_password')
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('new_password') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                        <div class=" input-field col-md-4">
                                            <input placeholder="" autocomplete="off" type="password"
                                                value="{{ old('new_password_confirmation') }}"
                                                id="new_password_confirmation" name="new_password_confirmation">
                                            <label for="new_password_confirmation">Confirm Password</label>
                                            @error('new_password_confirmation')
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('new_password_confirmation') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <button class="--btn btn-submit mt-1 " type="submit">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
