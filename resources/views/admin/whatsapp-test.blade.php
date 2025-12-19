@extends('layouts.admin')

@section('title', 'Test WhatsApp API')
@section('page-title', 'Test WhatsApp API')

@section('content')
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.settings.index') }}">Pengaturan</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.settings.whatsapp') }}">WhatsApp</a></li>
            <li class="breadcrumb-item active">Test API</li>
        </ol>
    </nav>
    <h1 class="page-title">
        <i class="fab fa-whatsapp text-success me-2"></i>Test WhatsApp API
    </h1>
    <p class="text-muted">Gunakan halaman ini untuk menguji koneksi dan pengiriman pesan WhatsApp.</p>
</div>

<div class="row g-4">
    <!-- Connection Status Card -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center">
                <i class="fas fa-plug text-primary me-2"></i>
                <span>Status Koneksi</span>
            </div>
            <div class="card-body">
                <div id="connectionStatus" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="text-muted mt-3 mb-0">Memeriksa koneksi...</p>
                </div>
            </div>
            <div class="card-footer bg-transparent">
                <button onclick="testConnection()" class="btn btn-primary w-100" id="btnTestConnection">
                    <i class="fas fa-sync-alt me-2"></i>Cek Koneksi
                </button>
            </div>
        </div>
    </div>

    <!-- Send Test Message Card -->
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center">
                <i class="fas fa-paper-plane text-success me-2"></i>
                <span>Kirim Pesan Test</span>
            </div>
            <div class="card-body">
                <form id="sendMessageForm" onsubmit="sendTestMessage(event)">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="testPhone" class="form-label">
                                <i class="fas fa-phone me-1"></i>Nomor WhatsApp
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">+62</span>
                                <input type="text" class="form-control" id="testPhone" 
                                       placeholder="8123456789" 
                                       pattern="[0-9]{9,13}"
                                       title="Masukkan nomor tanpa awalan 0 atau +62">
                            </div>
                            <div class="form-text">Contoh: 81234567890 (tanpa 0 atau +62)</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-info-circle me-1"></i>Tips
                            </label>
                            <div class="alert alert-info mb-0 py-2" style="font-size: 0.85rem;">
                                Pastikan nomor terdaftar di WhatsApp dan API Key sudah dikonfigurasi.
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="testMessage" class="form-label">
                                <i class="fas fa-comment me-1"></i>Pesan
                            </label>
                            <textarea class="form-control" id="testMessage" rows="4" 
                                      placeholder="Tulis pesan test Anda di sini...">Assalamu'alaikum! 🕌

Ini adalah pesan test dari sistem PO Kaligrafi Lampu.

Terima kasih telah menggunakan layanan kami.</textarea>
                        </div>
                    </div>
                </form>
                
                <!-- Message Result -->
                <div id="messageResult" class="mt-4" style="display: none;"></div>
            </div>
            <div class="card-footer bg-transparent">
                <button type="button" onclick="sendTestMessage(event)" class="btn btn-success" id="btnSendMessage">
                    <i class="fab fa-whatsapp me-2"></i>Kirim Pesan Test
                </button>
                <button type="button" onclick="clearForm()" class="btn btn-outline-secondary ms-2">
                    <i class="fas fa-eraser me-2"></i>Reset
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Production Stage Test -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <i class="fas fa-industry text-warning me-2"></i>
                    <span>Test Notifikasi Produksi</span>
                </div>
                <span class="badge bg-secondary">Simulasi</span>
            </div>
            <div class="card-body">
                <p class="text-muted mb-4">
                    Klik tombol di bawah untuk mengirim contoh notifikasi berdasarkan tahap produksi ke nomor test (081234567890).
                </p>
                
                <div class="row g-3">
                    <!-- PO Open -->
                    <div class="col-6 col-md-3 col-lg-2">
                        <button onclick="testProductionStage('po_open')" class="btn btn-outline-primary w-100 h-100 py-3">
                            <i class="fas fa-door-open d-block mb-2" style="font-size: 1.5rem;"></i>
                            <span class="d-block small">PO Dibuka</span>
                        </button>
                    </div>
                    
                    <!-- Waiting Quota -->
                    <div class="col-6 col-md-3 col-lg-2">
                        <button onclick="testProductionStage('waiting_quota')" class="btn btn-outline-info w-100 h-100 py-3">
                            <i class="fas fa-hourglass-half d-block mb-2" style="font-size: 1.5rem;"></i>
                            <span class="d-block small">Menunggu Kuota</span>
                        </button>
                    </div>
                    
                    <!-- Production -->
                    <div class="col-6 col-md-3 col-lg-2">
                        <button onclick="testProductionStage('production')" class="btn btn-outline-warning w-100 h-100 py-3">
                            <i class="fas fa-hammer d-block mb-2" style="font-size: 1.5rem;"></i>
                            <span class="d-block small">Produksi</span>
                        </button>
                    </div>
                    
                    <!-- QC -->
                    <div class="col-6 col-md-3 col-lg-2">
                        <button onclick="testProductionStage('qc')" class="btn btn-outline-secondary w-100 h-100 py-3">
                            <i class="fas fa-search d-block mb-2" style="font-size: 1.5rem;"></i>
                            <span class="d-block small">Quality Control</span>
                        </button>
                    </div>
                    
                    <!-- Packaging -->
                    <div class="col-6 col-md-3 col-lg-2">
                        <button onclick="testProductionStage('packaging')" class="btn btn-outline-dark w-100 h-100 py-3">
                            <i class="fas fa-box d-block mb-2" style="font-size: 1.5rem;"></i>
                            <span class="d-block small">Pengemasan</span>
                        </button>
                    </div>
                    
                    <!-- Shipping -->
                    <div class="col-6 col-md-3 col-lg-2">
                        <button onclick="testProductionStage('shipping')" class="btn btn-outline-success w-100 h-100 py-3">
                            <i class="fas fa-truck d-block mb-2" style="font-size: 1.5rem;"></i>
                            <span class="d-block small">Pengiriman</span>
                        </button>
                    </div>
                    
                    <!-- Delivered -->
                    <div class="col-6 col-md-3 col-lg-2">
                        <button onclick="testProductionStage('delivered')" class="btn btn-success w-100 h-100 py-3">
                            <i class="fas fa-check-circle d-block mb-2" style="font-size: 1.5rem;"></i>
                            <span class="d-block small">Terkirim</span>
                        </button>
                    </div>
                </div>
                
                <!-- Production Result -->
                <div id="productionResult" class="mt-4" style="display: none;"></div>
            </div>
        </div>
    </div>
</div>

<!-- API Log Card -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <i class="fas fa-history text-secondary me-2"></i>
                    <span>Log Aktivitas</span>
                </div>
                <button onclick="clearLog()" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-trash me-1"></i>Hapus Log
                </button>
            </div>
            <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                <div id="apiLog" class="font-monospace small">
                    <div class="text-muted text-center py-3">
                        <i class="fas fa-info-circle me-1"></i>
                        Log aktivitas akan muncul di sini setelah Anda melakukan test.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
let logStarted = false;

// Test Connection
function testConnection() {
    const btn = document.getElementById('btnTestConnection');
    const resultDiv = document.getElementById('connectionStatus');
    
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memeriksa...';
    
    addLog('INFO', 'Menguji koneksi API WhatsApp...');
    
    fetch('{{ route("api.admin.whatsapp.test") }}')
        .then(response => response.json())
        .then(data => {
            addLog('DEBUG', 'Response: ' + JSON.stringify(data));
            
            if (data.connection_test && data.connection_test.connected) {
                resultDiv.innerHTML = `
                    <div class="text-center">
                        <div class="mb-3">
                            <span class="d-inline-flex align-items-center justify-content-center bg-success-subtle rounded-circle" style="width: 80px; height: 80px;">
                                <i class="fas fa-check-circle text-success" style="font-size: 2.5rem;"></i>
                            </span>
                        </div>
                        <h5 class="text-success mb-2">Terhubung!</h5>
                        <p class="text-muted mb-0 small">
                            <strong>API URL:</strong> ${data.connection_test.api_url || 'N/A'}<br>
                            <strong>Sender:</strong> ${data.connection_test.sender || 'Tidak dikonfigurasi'}
                        </p>
                    </div>
                `;
                addLog('SUCCESS', 'API terhubung dengan sukses');
            } else {
                resultDiv.innerHTML = `
                    <div class="text-center">
                        <div class="mb-3">
                            <span class="d-inline-flex align-items-center justify-content-center bg-danger-subtle rounded-circle" style="width: 80px; height: 80px;">
                                <i class="fas fa-times-circle text-danger" style="font-size: 2.5rem;"></i>
                            </span>
                        </div>
                        <h5 class="text-danger mb-2">Gagal Terhubung</h5>
                        <p class="text-muted mb-0 small">${data.connection_test?.error || 'Tidak dapat terhubung ke API'}</p>
                    </div>
                `;
                addLog('ERROR', 'Gagal terhubung: ' + (data.connection_test?.error || 'Unknown error'));
            }
        })
        .catch(error => {
            resultDiv.innerHTML = `
                <div class="text-center">
                    <div class="mb-3">
                        <span class="d-inline-flex align-items-center justify-content-center bg-warning-subtle rounded-circle" style="width: 80px; height: 80px;">
                            <i class="fas fa-exclamation-triangle text-warning" style="font-size: 2.5rem;"></i>
                        </span>
                    </div>
                    <h5 class="text-warning mb-2">Error</h5>
                    <p class="text-muted mb-0 small">${error.message}</p>
                </div>
            `;
            addLog('ERROR', 'Exception: ' + error.message);
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-sync-alt me-2"></i>Cek Koneksi';
        });
}

// Send Test Message
function sendTestMessage(event) {
    if (event) event.preventDefault();
    
    let phone = document.getElementById('testPhone').value.trim();
    const message = document.getElementById('testMessage').value.trim();
    const btn = document.getElementById('btnSendMessage');
    const resultDiv = document.getElementById('messageResult');
    
    if (!phone) {
        showResult(resultDiv, 'warning', 'Harap masukkan nomor WhatsApp!');
        return;
    }
    
    if (!message) {
        showResult(resultDiv, 'warning', 'Harap masukkan pesan!');
        return;
    }
    
    // Format phone number
    phone = phone.replace(/\D/g, '');
    if (phone.startsWith('0')) {
        phone = '62' + phone.substring(1);
    } else if (!phone.startsWith('62')) {
        phone = '62' + phone;
    }
    
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...';
    
    addLog('INFO', `Mengirim pesan ke ${phone}...`);
    
    fetch('{{ route("api.admin.whatsapp.send-test") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ phone, message })
    })
    .then(response => response.json())
    .then(data => {
        addLog('DEBUG', 'Response: ' + JSON.stringify(data));
        
        if (data.success) {
            showResult(resultDiv, 'success', `
                <strong><i class="fas fa-check-circle me-2"></i>Pesan Berhasil Dikirim!</strong>
                <p class="mb-0 mt-2">
                    <small><strong>Ke:</strong> ${data.to || phone}</small><br>
                    <small><strong>Status:</strong> ${data.status || 'Sent'}</small>
                </p>
            `);
            addLog('SUCCESS', `Pesan terkirim ke ${data.to || phone}`);
        } else {
            showResult(resultDiv, 'danger', `
                <strong><i class="fas fa-times-circle me-2"></i>Gagal Mengirim Pesan</strong>
                <p class="mb-0 mt-2">${data.error || 'Terjadi kesalahan yang tidak diketahui'}</p>
            `);
            addLog('ERROR', 'Gagal mengirim: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        showResult(resultDiv, 'danger', `
            <strong><i class="fas fa-exclamation-triangle me-2"></i>Error</strong>
            <p class="mb-0 mt-2">${error.message}</p>
        `);
        addLog('ERROR', 'Exception: ' + error.message);
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fab fa-whatsapp me-2"></i>Kirim Pesan Test';
    });
}

// Test Production Stage
function testProductionStage(stage) {
    const resultDiv = document.getElementById('productionResult');
    
    const stageNames = {
        'po_open': 'PO Dibuka',
        'waiting_quota': 'Menunggu Kuota',
        'production': 'Produksi',
        'qc': 'Quality Control',
        'packaging': 'Pengemasan',
        'shipping': 'Pengiriman',
        'delivered': 'Terkirim'
    };
    
    showResult(resultDiv, 'info', `
        <div class="d-flex align-items-center">
            <span class="spinner-border spinner-border-sm me-2"></span>
            Mengirim notifikasi ${stageNames[stage]}...
        </div>
    `);
    
    addLog('INFO', `Mengirim notifikasi produksi: ${stageNames[stage]}`);
    
    fetch('{{ route("api.admin.whatsapp.test-production") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ stage })
    })
    .then(response => response.json())
    .then(data => {
        addLog('DEBUG', 'Response: ' + JSON.stringify(data));
        
        if (data.success) {
            showResult(resultDiv, 'success', `
                <strong><i class="fas fa-check-circle me-2"></i>Notifikasi "${stageNames[stage]}" Terkirim!</strong>
                <p class="mb-2 mt-2">
                    <small><strong>Ke:</strong> ${data.to || '081234567890'}</small>
                </p>
                ${data.message_preview ? `<div class="bg-light p-2 rounded small"><em>${data.message_preview}</em></div>` : ''}
            `);
            addLog('SUCCESS', `Notifikasi ${stageNames[stage]} terkirim`);
        } else {
            showResult(resultDiv, 'danger', `
                <strong><i class="fas fa-times-circle me-2"></i>Gagal Mengirim Notifikasi</strong>
                <p class="mb-0 mt-2">${data.error || 'Terjadi kesalahan yang tidak diketahui'}</p>
            `);
            addLog('ERROR', 'Gagal mengirim notifikasi: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        showResult(resultDiv, 'danger', `
            <strong><i class="fas fa-exclamation-triangle me-2"></i>Error</strong>
            <p class="mb-0 mt-2">${error.message}</p>
        `);
        addLog('ERROR', 'Exception: ' + error.message);
    });
}

// Utility Functions
function showResult(element, type, message) {
    element.style.display = 'block';
    element.className = `alert alert-${type}`;
    element.innerHTML = message;
}

function clearForm() {
    document.getElementById('testPhone').value = '';
    document.getElementById('testMessage').value = `Assalamu'alaikum! 🕌

Ini adalah pesan test dari sistem PO Kaligrafi Lampu.

Terima kasih telah menggunakan layanan kami.`;
    document.getElementById('messageResult').style.display = 'none';
}

function addLog(type, message) {
    const logDiv = document.getElementById('apiLog');
    const now = new Date().toLocaleTimeString('id-ID');
    
    if (!logStarted) {
        logDiv.innerHTML = '';
        logStarted = true;
    }
    
    const typeColors = {
        'INFO': 'text-info',
        'SUCCESS': 'text-success',
        'ERROR': 'text-danger',
        'WARNING': 'text-warning',
        'DEBUG': 'text-secondary'
    };
    
    const logEntry = document.createElement('div');
    logEntry.className = 'mb-1 pb-1 border-bottom';
    logEntry.innerHTML = `
        <span class="text-muted">[${now}]</span>
        <span class="${typeColors[type] || 'text-dark'} fw-bold">[${type}]</span>
        <span>${message}</span>
    `;
    
    logDiv.insertBefore(logEntry, logDiv.firstChild);
}

function clearLog() {
    document.getElementById('apiLog').innerHTML = `
        <div class="text-muted text-center py-3">
            <i class="fas fa-info-circle me-1"></i>
            Log aktivitas akan muncul di sini setelah Anda melakukan test.
        </div>
    `;
    logStarted = false;
}

// Auto-check connection on page load
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(testConnection, 500);
});
</script>
@endpush

@push('styles')
<style>
.bg-success-subtle {
    background-color: rgba(25, 135, 84, 0.1);
}
.bg-danger-subtle {
    background-color: rgba(220, 53, 69, 0.1);
}
.bg-warning-subtle {
    background-color: rgba(255, 193, 7, 0.1);
}
</style>
@endpush