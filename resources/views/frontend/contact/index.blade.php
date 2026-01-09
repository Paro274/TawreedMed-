@extends('frontend.layouts.app')

@section('title', 'اتصل بنا - توريد ميد')

@push('styles')
    <link rel="stylesheet" href="{{ asset('frontend/css/contact.css') }}">
@endpush

@section('content')
    {{-- بيانات التواصل --}}
    @include('frontend.contact.contact-info', ['contact' => $contact])

    {{-- نموذج التواصل --}}
    @include('frontend.contact.contact-form')
@endsection

@push('scripts')
    <script src="{{ asset('frontend/js/contact.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // JavaScript للنموذج (لو عندك)
            const contactForm = document.querySelector('#contactForm');
            if (contactForm) {
                contactForm.addEventListener('submit', function(e) {
                    // إضافة loading state
                    const submitBtn = this.querySelector('button[type="submit"]');
                    submitBtn.innerHTML = 'جاري الإرسال...';
                    submitBtn.disabled = true;
                });
            }
        });
    </script>
@endpush
