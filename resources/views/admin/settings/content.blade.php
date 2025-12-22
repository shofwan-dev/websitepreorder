@extends('layouts.admin')

@section('title', 'Kelola Konten')
@section('page-title', 'Kelola Konten Website')

@section('content')
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.settings.index') }}">Pengaturan</a></li>
            <li class="breadcrumb-item active">Konten</li>
        </ol>
    </nav>
    <h1 class="page-title">
        <i class="fas fa-file-alt me-2"></i>Kelola Konten Website
    </h1>
    <p class="text-muted">Edit konten halaman Tentang, Cara Kerja, FAQ, Refund Policy, dan Syarat & Ketentuan</p>
</div>

<!-- Tab Navigation -->
<ul class="nav nav-tabs mb-4" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="about-tab" data-bs-toggle="tab" data-bs-target="#about" type="button">
            <i class="fas fa-info-circle me-2"></i>Tentang Kami
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="how-it-works-tab" data-bs-toggle="tab" data-bs-target="#how-it-works" type="button">
            <i class="fas fa-tasks me-2"></i>Cara Kerja
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="faq-tab" data-bs-toggle="tab" data-bs-target="#faq" type="button">
            <i class="fas fa-question-circle me-2"></i>FAQ
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="refund-tab" data-bs-toggle="tab" data-bs-target="#refund" type="button">
            <i class="fas fa-undo-alt me-2"></i>Refund Policy
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="terms-tab" data-bs-toggle="tab" data-bs-target="#terms" type="button">
            <i class="fas fa-file-contract me-2"></i>Syarat & Ketentuan
        </button>
    </li>
</ul>

<!-- Tab Content -->
<div class="tab-content">
    <!-- About Tab -->
    <div class="tab-pane fade show active" id="about" role="tabpanel">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Konten Halaman Tentang Kami
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.content.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="section" value="about">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Halaman</label>
                        <input type="text" class="form-control" name="about_title" value="{{ $settings['about_title'] ?? 'Tentang Kami' }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi Singkat (Lead Text)</label>
                        <textarea class="form-control" name="about_description" rows="2">{{ $settings['about_description'] ?? 'Kami adalah pengrajin kaligrafi lampu yang berdedikasi menciptakan karya seni islami dengan kualitas terbaik.' }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Visi</label>
                        <textarea class="form-control" name="about_vision" rows="3">{{ $settings['about_vision'] ?? 'Menjadi pelopor dalam menyebarkan keindahan kaligrafi islami melalui karya seni yang fungsional dan bermakna.' }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Misi (Pisahkan dengan baris baru)</label>
                        <textarea class="form-control" name="about_mission" rows="5">{{ $settings['about_mission'] ?? "Menciptakan kaligrafi lampu dengan desain elegan dan bermakna\nMenggunakan bahan-bahan berkualitas tinggi untuk hasil yang optimal\nMemberikan pengalaman pre-order yang transparan dan terpercaya\nMenyebarkan cahaya keberkahan melalui karya seni islami" }}</textarea>
                        <div class="form-text">Masukkan satu poin misi per baris</div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- How It Works Tab -->
    <div class="tab-pane fade" id="how-it-works" role="tabpanel">
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Tutorial:</strong> Edit langkah-langkah cara kerja Pre-Order. Gunakan format JSON untuk icon FontAwesome.
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-tasks me-2"></i>Langkah-Langkah Cara Kerja
                </h5>
                <button type="button" class="btn btn-sm btn-success" onclick="addStep()">
                    <i class="fas fa-plus me-1"></i>Tambah Langkah
                </button>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.content.update') }}" method="POST" id="stepsForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="section" value="how_it_works">
                    
                    <div id="stepsContainer">
                        @php
                            $defaultSteps = [
                                ['number' => 1, 'title' => 'Ikut Pre-Order', 'description' => 'Pilih kaligrafi yang Anda suka dan ikut pre-order dengan klik tombol "Ikut PO"', 'icon' => 'fas fa-cart-plus'],
                                ['number' => 2, 'title' => 'Tunggu Kuota Terpenuhi', 'description' => 'Produksi akan dimulai setelah kuota minimal (misal: 10 pemesan) terpenuhi', 'icon' => 'fas fa-users'],
                                ['number' => 3, 'title' => 'Proses Produksi', 'description' => 'Pengrajin kami akan membuat kaligrafi dengan ketelitian dan doa', 'icon' => 'fas fa-hammer'],
                                ['number' => 4, 'title' => 'Terima Notifikasi', 'description' => 'Anda akan mendapat update via WhatsApp di setiap tahap produksi', 'icon' => 'fas fa-bell'],
                                ['number' => 5, 'title' => 'Barang Dikirim', 'description' => 'Kaligrafi dikirim dengan pengemasan eksklusif dan aman', 'icon' => 'fas fa-shipping-fast'],
                                ['number' => 6, 'title' => 'Terima & Nikmati', 'description' => 'Kaligrafi lampu siap menghiasi rumah dengan cahaya yang menenangkan', 'icon' => 'fas fa-home']
                            ];
                            $steps = json_decode($settings['how_it_works_steps'] ?? json_encode($defaultSteps), true);
                        @endphp

                        @foreach($steps as $index => $step)
                        <div class="card mb-3 step-item">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h6 class="mb-0">Langkah {{ $index + 1 }}</h6>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="removeStep(this)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Judul</label>
                                        <input type="text" class="form-control" name="steps[{{ $index }}][title]" value="{{ $step['title'] }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Icon (FontAwesome Class)</label>
                                        <input type="text" class="form-control" name="steps[{{ $index }}][icon]" value="{{ $step['icon'] }}" placeholder="fas fa-cart-plus">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Deskripsi</label>
                                        <textarea class="form-control" name="steps[{{ $index }}][description]" rows="2" required>{{ $step['description'] }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- FAQ Tab -->
    <div class="tab-pane fade" id="faq" role="tabpanel">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-question-circle me-2"></i>Daftar Pertanyaan FAQ
                </h5>
                <button type="button" class="btn btn-sm btn-success" onclick="addFaq()">
                    <i class="fas fa-plus me-1"></i>Tambah FAQ
                </button>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.content.update') }}" method="POST" id="faqForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="section" value="faq">
                    
                    <div id="faqContainer">
                        @php
                            $defaultFaqs = [
                                ['question' => 'Apa itu Pre-Order (PO)?', 'answer' => 'Pre-Order adalah sistem pemesanan di mana produk akan diproduksi setelah mencapai kuota minimal pemesan. Ini memastikan kualitas produksi tetap terjaga dan menghindari overproduksi.'],
                                ['question' => 'Berapa lama waktu produksi?', 'answer' => 'Setelah kuota terpenuhi, proses produksi membutuhkan 7-10 hari kerja ditambah 2-5 hari untuk pengiriman. Total estimasi 9-15 hari kerja.'],
                                ['question' => 'Apakah bisa request desain custom?', 'answer' => 'Ya, untuk batch tertentu kami membuka customisasi. Silakan chat admin melalui WhatsApp untuk konsultasi desain.'],
                                ['question' => 'Bagaimana sistem pembayarannya?', 'answer' => 'Pembayaran dilakukan melalui iPaymu yang aman dan terpercaya. Setelah pembayaran diverifikasi, nama Anda akan muncul di daftar PO.'],
                                ['question' => 'Apakah ada garansi?', 'answer' => 'Ya, semua produk kami bergaransi 1 tahun untuk kerusakan non-kecelakaan. Untuk klaim garansi, silakan hubungi admin.'],
                                ['question' => 'Bisa dikirim ke mana saja?', 'answer' => 'Kami bisa kirim ke seluruh Indonesia via ekspedisi terpercaya. Biaya pengiriman disesuaikan dengan kota tujuan.']
                            ];
                            $faqs = json_decode($settings['faq_items'] ?? json_encode($defaultFaqs), true);
                        @endphp

                        @foreach($faqs as $index => $faq)
                        <div class="card mb-3 faq-item">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h6 class="mb-0">FAQ {{ $index + 1 }}</h6>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="removeFaq(this)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Pertanyaan</label>
                                    <input type="text" class="form-control" name="faqs[{{ $index }}][question]" value="{{ $faq['question'] }}" required>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label">Jawaban</label>
                                    <textarea class="form-control" name="faqs[{{ $index }}][answer]" rows="3" required>{{ $faq['answer'] }}</textarea>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Refund Policy Tab -->
    <div class="tab-pane fade" id="refund" role="tabpanel">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-undo-alt me-2"></i>Konten Kebijakan Refund
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Info:</strong> Halaman ini penting untuk verifikasi payment gateway. Pastikan konten mencakup ketentuan pembatalan, pengembalian dana, dan kondisi khusus.
                </div>
                
                <form action="{{ route('admin.settings.content.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="section" value="refund_policy">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Konten Kebijakan Refund</label>
                        <textarea class="form-control tinymce-editor summernote-editor" name="refund_policy_content" rows="20">{!! $settings['refund_policy_content'] ?? '' !!}</textarea>
                        <div class="form-text">
                            <i class="fas fa-lightbulb me-1"></i>
                            Konten default sudah generic dan siap digunakan. Edit sesuai kebutuhan bisnis Anda. Gunakan toolbar untuk formatting (Bold, List, Heading, dll).
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>
                    <a href="{{ route('refund-policy') }}" target="_blank" class="btn btn-outline-secondary">
                        <i class="fas fa-external-link-alt me-2"></i>Preview Halaman
                    </a>
                </form>
            </div>
        </div>
    </div>

    <!-- Terms & Conditions Tab -->
    <div class="tab-pane fade" id="terms" role="tabpanel">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-file-contract me-2"></i>Konten Syarat & Ketentuan
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Info:</strong> Halaman ini penting untuk verifikasi payment gateway. Pastikan konten mencakup ketentuan umum, sistem PO, pembayaran, pengiriman, garansi, dan hukum yang berlaku.
                </div>
                
                <form action="{{ route('admin.settings.content.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="section" value="terms_conditions">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Konten Syarat & Ketentuan</label>
                        <textarea class="form-control tinymce-editor summernote-editor" name="terms_conditions_content" rows="20">{!! $settings['terms_conditions_content'] ?? '' !!}</textarea>
                        <div class="form-text">
                            <i class="fas fa-lightbulb me-1"></i>
                            Konten default sudah generic dan siap digunakan. Edit sesuai kebutuhan bisnis Anda. Gunakan toolbar untuk formatting (Bold, List, Heading, dll).
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>
                    <a href="{{ route('terms-conditions') }}" target="_blank" class="btn btn-outline-secondary">
                        <i class="fas fa-external-link-alt me-2"></i>Preview Halaman
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let stepCounter = {{ count($steps) }};
let faqCounter = {{ count($faqs) }};

function addStep() {
    const container = document.getElementById('stepsContainer');
    const stepHtml = `
        <div class="card mb-3 step-item">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h6 class="mb-0">Langkah ${stepCounter + 1}</h6>
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeStep(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Judul</label>
                        <input type="text" class="form-control" name="steps[${stepCounter}][title]" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Icon (FontAwesome Class)</label>
                        <input type="text" class="form-control" name="steps[${stepCounter}][icon]" placeholder="fas fa-star">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="steps[${stepCounter}][description]" rows="2" required></textarea>
                    </div>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', stepHtml);
    stepCounter++;
}

function removeStep(btn) {
    if (confirm('Hapus langkah ini?')) {
        btn.closest('.step-item').remove();
        updateStepNumbers();
    }
}

function updateStepNumbers() {
    document.querySelectorAll('.step-item').forEach((item, index) => {
        item.querySelector('h6').textContent = `Langkah ${index + 1}`;
    });
}

function addFaq() {
    const container = document.getElementById('faqContainer');
    const faqHtml = `
        <div class="card mb-3 faq-item">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h6 class="mb-0">FAQ ${faqCounter + 1}</h6>
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeFaq(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="mb-3">
                    <label class="form-label">Pertanyaan</label>
                    <input type="text" class="form-control" name="faqs[${faqCounter}][question]" required>
                </div>
                <div class="mb-0">
                    <label class="form-label">Jawaban</label>
                    <textarea class="form-control" name="faqs[${faqCounter}][answer]" rows="3" required></textarea>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', faqHtml);
    faqCounter++;
}

function removeFaq(btn) {
    if (confirm('Hapus FAQ ini?')) {
        btn.closest('.faq-item').remove();
        updateFaqNumbers();
    }
}

function updateFaqNumbers() {
    document.querySelectorAll('.faq-item').forEach((item, index) => {
        item.querySelector('h6').textContent = `FAQ ${index + 1}`;
    });
}

// Initialize Summernote WYSIWYG Editor (Free, no API key needed)
document.addEventListener('DOMContentLoaded', function() {
    // Load Summernote CSS dan JS dari CDN
    const summernoteCss = document.createElement('link');
    summernoteCss.rel = 'stylesheet';
    summernoteCss.href = 'https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css';
    document.head.appendChild(summernoteCss);
    
    const summernoteJs = document.createElement('script');
    summernoteJs.src = 'https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js';
    summernoteJs.onload = function() {
        // Initialize Summernote setelah library loaded
        $('.tinymce-editor').summernote({
            height: 500,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            styleTags: [
                'p',
                { title: 'Heading 4', tag: 'h4', className: 'fw-bold mb-4', value: 'h4' },
                { title: 'Heading 5', tag: 'h5', className: 'fw-semibold mb-3', value: 'h5' },
                { title: 'Heading 6', tag: 'h6', className: 'fw-semibold', value: 'h6' }
            ],
            popover: {
                image: [],
                link: [],
                air: []
            },
            callbacks: {
                onInit: function() {
                    console.log('Summernote initialized successfully');
                }
            }
        });
    };
    document.head.appendChild(summernoteJs);
});
</script>
@endpush
