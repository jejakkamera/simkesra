@php
$configData = Helper::appClasses();
$isFront = true;
@endphp

@section('layoutContent')

@extends('layouts/commonMaster' )
@include('layouts/sections/navbar/navbar-front')

<!-- Sections:Start -->
@yield('content')
{{-- {{ $slot }} --}}
<!-- / Sections:End -->
@include('layouts/sections/footer/footer-front')
@endsection
