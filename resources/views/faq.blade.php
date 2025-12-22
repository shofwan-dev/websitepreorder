@extends('layouts.app')

@section('title', 'FAQ - ' . ($site_settings['site_name'] ?? 'PO Kaligrafi Lampu'))

@section('content')
<div class="min-vh-100 py-5" style="background: linear-gradient(135deg, #fef9e7 0%, #ffffff 100%);">
    <div class="container">
        <!-- Header -->
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold mb-3" style="color: #8b6b2d;">
                <i class="fas fa-question-circle me-3"></i>Pertanyaan Umum (FAQ)
            </h1>
            <p class="lead text-muted">
                Temukan jawaban untuk pertanyaan yang sering diajukan
            </p>
        </div>

        <!-- FAQ Accordion -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="accordion" id="faqAccordion">
                    @foreach($faqs as $index => $faq)
                    <div class="accordion-item border-0 shadow-sm mb-3">
                        <h2 class="accordion-header" id="heading{{ $index }}">
                            <button class="accordion-button {{ $index !== 0 ? 'collapsed' : '' }} fw-semibold" 
                                    type="button" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#collapse{{ $index }}" 
                                    aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" 
                                    aria-controls="collapse{{ $index }}"
                                    style="color: #8b6b2d; background-color: {{ $index === 0 ? 'rgba(212, 160, 23, 0.1)' : '#ffffff' }};">
                                <i class="fas fa-chevron-circle-right me-3" style="color: #d4a017;"></i>
                                {{ $faq['question'] }}
                            </button>
                        </h2>
                        <div id="collapse{{ $index }}" 
                             class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" 
                             aria-labelledby="heading{{ $index }}" 
                             data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p class="mb-0 text-muted">{{ $faq['answer'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Still Have Questions -->
                <div class="card border-0 shadow-sm mt-5 text-center p-5"
                     style="background: linear-gradient(135deg, #d4a017 0%, #f4c542 100%);">
                    <div class="text-white">
                        <h4 class="fw-bold mb-3">
                            <i class="fas fa-headset me-2"></i>Masih Ada Pertanyaan?
                        </h4>
                        <p class="mb-4">Tim kami siap membantu Anda melalui WhatsApp</p>
                        <a href="{{ route('contact') }}" 
                           class="btn btn-light btn-lg px-5 fw-semibold"
                           style="color: #8b6b2d;">
                            <i class="fab fa-whatsapp me-2"></i>Hubungi Kami
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .accordion-button:not(.collapsed) {
        background-color: rgba(212, 160, 23, 0.1) !important;
        color: #8b6b2d !important;
        box-shadow: none;
    }

    .accordion-button:focus {
        box-shadow: 0 0 0 0.25rem rgba(212, 160, 23, 0.25);
        border-color: #d4a017;
    }

    .accordion-button::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%238b6b2d'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    }

    .accordion-item {
        border-radius: 10px !important;
        overflow: hidden;
    }

    .accordion-button {
        border-radius: 10px !important;
    }
</style>
@endpush
@endsection
