@extends('layouts.app')

@section('content')
@stack('plugin-styles')



<div data-base-url="{{url('/')}}">

  <div class="main-wrapper" id="app">


      <div class="page-content" style="background: #fff;">
        @yield('content')
      </div>
      <div class="page-wrapper full-page">
        <div class="page-content d-flex align-items-center justify-content-center">
  
    <div class="error_ct row w-100 mx-0 auth-page ">
      {{-- <a href="{{ asset('/') }}" class="d-block text-center ">
        <img loading="lazy" src="{{ asset('asset/images/logo.png') }}" alt="" />
    </a> --}}
    {{-- <div class="headertag fs-4 d-block text-center mb-4 mt-2 fw-semibold">जिस पर देश <span class="text-danger">करता है भरोसा</span></div> --}}
      <div class="col-md-8 col-xl-6 mx-auto d-flex flex-column align-items-center mt-4 mb-3">
        <img loading="lazy" src="{{config('global.base_asset')}}asset/images/error.png" class="img-fluid mb-2" alt="404">
        <h5 class="text-muted mb-3 text-center">Sorry, It appears the page you were looking for doesn't exist anymore or might have been removed.</h5>
        <a href="{{ asset('/') }}" class="fs-5 text-danger text-decoration-underline">Back to home</a>
      </div>
    </div>
  
  </div>
      </div>
  </div>

    
    <!-- plugin js -->
    @stack('plugin-scripts')
    <!-- end plugin js -->



    @stack('custom-scripts')
</div>
@endsection