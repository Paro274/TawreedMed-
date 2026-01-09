@extends('frontend.layouts.app')

@section('title', 'من نحن - توريد ميد')

@push('styles')
    <link rel="stylesheet" href="{{ asset('frontend/css/about.css') }}">
@endpush

@section('content')
    @include('frontend.about.story')
    @include('frontend.about.vmv')
    @include('frontend.about.team')
    @include('frontend.about.journey')
    @include('frontend.about.partners')
    @include('frontend.about.awards')
    @include('frontend.homepage.cta')
@endsection

@push('scripts')
    <script src="{{ asset('frontend/js/about.js') }}"></script>
@endpush
