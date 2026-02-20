@extends('layouts.app')

@section('title', 'WhatsApp | Connection Status')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="p-0">WhatsApp Connection</h1>
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-row justify-content-between align-items-center">
                        <div>Status Koneksi WhatsApp</div>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-refresh-status">
                            <i class="fas fa-sync mr-2"></i>Refresh Status
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if (session()->has('result'))
                    <div class="alert alert-{{ session()->get('result')->type }}" role="alert">
                        {{ session()->get('result')->message }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif

                    <div class="text-center py-5">
                        <div id="status-loading" class="mb-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <p class="mt-2">Memeriksa status koneksi...</p>
                        </div>

                        <div id="status-connected" class="d-none">
                            <div class="mb-4">
                                <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                            </div>
                            <h3 class="text-success mb-3">WhatsApp Terhubung</h3>
                            <p class="text-muted">Koneksi WhatsApp aktif dan siap digunakan</p>
                            <div class="mt-4">
                                <p><strong>Device:</strong> <span id="device-name"></span></p>
                                <p><strong>Phone:</strong> <span id="phone-number"></span></p>
                                {{-- <button type="button" class="btn btn-avian-secondary btn-lg" id="btn-disconnect">
                                    <i class="fas fa-power-off mr-2"></i>Disconnect
                                </button> --}}
                            </div>
                        </div>

                        <div id="status-disconnected" class="d-none">
                            <div class="mb-4">
                                <i class="fas fa-times-circle text-danger" style="font-size: 5rem;"></i>
                            </div>
                            <h3 class="text-danger mb-3">WhatsApp Terputus</h3>
                            <p class="text-muted">Silakan scan QR code untuk menghubungkan WhatsApp</p>
                            <div class="mt-4">
                                <button type="button" class="btn btn-avian-primary btn-lg" id="btn-generate-qr">
                                    <i class="fas fa-qrcode mr-2"></i>Generate QR Code
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- QR Code Modal -->
<div class="modal fade" id="qrModal" tabindex="-1" role="dialog" aria-labelledby="qrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrModalLabel">Scan QR Code WhatsApp</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div id="qr-loading">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p>Generating QR Code...</p>
                </div>
                <div id="qr-container" class="d-none">
                    <img id="qr-image" src="" alt="QR Code" class="img-fluid mb-3" style="max-width: 300px;">
                    <div class="alert alert-info">
                        <p class="mb-0"><strong>Cara Scan:</strong></p>
                        <ol class="text-left mb-0">
                            <li>Buka WhatsApp di ponsel Anda</li>
                            <li>Ketuk Menu atau Settings</li>
                            <li>Pilih "Linked Devices" atau "Perangkat Tertaut"</li>
                            <li>Ketuk "Link a Device" dan scan QR code ini</li>
                        </ol>
                    </div>
                </div>
                <div id="qr-error" class="d-none">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <span id="qr-error-message">Gagal generate QR code</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-avian-primary" id="btn-regenerate-qr">
                    <i class="fas fa-sync mr-2"></i>Generate Ulang
                </button>
            </div>
        </div>
    </div>
</div>
@endsection



@section('css')
<style>
    .btn-avian-primary {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }
    .btn-avian-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
        color: white;
    }
    #qr-image {
        border: 2px solid #dee2e6;
        border-radius: 8px;
        padding: 10px;
    }
</style>
@endsection

@section('js')
<script>
$(document).ready(function() {
    // Check connection status on page load
    checkConnectionStatus();

    // Refresh status button
    $('#btn-refresh-status').on('click', function() {
        checkConnectionStatus();
    });

    // Generate QR button
    $('#btn-generate-qr').on('click', function() {
        $('#qrModal').modal('show');
        generateQRCode();
    });

    // Regenerate QR button
    $('#btn-regenerate-qr').on('click', function() {
        generateQRCode();
    });

    // Disconnect button
    $('#btn-disconnect').on('click', function() {
        disconnectWA();
    });

    // Auto-check connection status when QR modal is closed
    $('#qrModal').on('hidden.bs.modal', function() {
        setTimeout(function() {
            checkConnectionStatus();
        }, 1000);
    });

    function checkConnectionStatus() {
        // Show loading state
        $('#status-loading').removeClass('d-none');
        $('#status-connected').addClass('d-none');
        $('#status-disconnected').addClass('d-none');

        // AJAX call to check WhatsApp connection status
        $.ajax({
            url: '{{ route("whatsapp.status") }}', // Adjust this URL to your API endpoint
            method: 'GET',
            success: function(response) {
                if (response.data && response.data.is_connected) {
                    // Show connected status
                    $('#status-loading').addClass('d-none');
                    $('#status-connected').removeClass('d-none');
                    $('#device-name').text(response.data.device_id || 'Unknown');
                    $('#phone-number').text(response.data.wa_number || 'Unknown');
                } else {
                    // Show disconnected status
                    $('#status-loading').addClass('d-none');
                    $('#status-disconnected').removeClass('d-none');
                }
            },
            error: function(xhr, status, error) {
                // Show disconnected status on error
                $('#status-loading').addClass('d-none');
                $('#status-disconnected').removeClass('d-none');
                console.error('Error checking connection status:', error);
            }
        });
    }

    function generateQRCode() {
        // Reset modal state
        $('#qr-loading').removeClass('d-none');
        $('#qr-container').addClass('d-none');
        $('#qr-error').addClass('d-none');

        // AJAX call to generate QR code
        $.ajax({
            url: '{{ route("whatsapp.getQR") }}', // Adjust this URL to your API endpoint
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success && response.data) {
                    // Display QR code
                    $('#qr-loading').addClass('d-none');
                    $('#qr-container').removeClass('d-none');
                    $('#qr-image').attr('src', response.data.qr_code);
                    // Auto-check connection status every 5 seconds
                    const checkInterval = setInterval(function() {
                        checkConnectionStatus();
                        if ($('#status-connected').is(':visible')) {
                            clearInterval(checkInterval);
                            $('#qrModal').modal('hide');
                        }
                    }, 5000);

                    // Clear interval when modal is closed
                    $('#qrModal').one('hidden.bs.modal', function() {
                        clearInterval(checkInterval);
                    });
                } else {
                    // Show error
                    $('#qr-loading').addClass('d-none');
                    $('#qr-error').removeClass('d-none');
                    $('#qr-error-message').text(response.message || 'Gagal generate QR code');
                }
            },
            error: function(xhr, status, error) {
                // Show error
                $('#qr-loading').addClass('d-none');
                $('#qr-error').removeClass('d-none');
                $('#qr-error-message').text('Terjadi kesalahan saat generate QR code');
                console.error('Error generating QR code:', error);
            }
        });
    }

    function disconnectWA() {
        // Show loading state
        $('#status-loading').removeClass('d-none');
        $('#status-connected').addClass('d-none');
        $('#status-disconnected').addClass('d-none');

        $.ajax({
            url: '', // Adjust this URL to your API endpoint
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success && response.data) {
                    // Display QR code
                    $('#qr-loading').addClass('d-none');
                    $('#qr-container').removeClass('d-none');
                    $('#qr-image').attr('src', response.data.qr_code);
                    // Auto-check connection status every 5 seconds
                    const checkInterval = setInterval(function() {
                        checkConnectionStatus();
                        if ($('#status-connected').is(':visible')) {
                            clearInterval(checkInterval);
                            $('#qrModal').modal('hide');
                        }
                    }, 5000);

                    // Clear interval when modal is closed
                    $('#qrModal').one('hidden.bs.modal', function() {
                        clearInterval(checkInterval);
                    });
                } else {
                    // Show error
                    $('#qr-loading').addClass('d-none');
                    $('#qr-error').removeClass('d-none');
                    $('#qr-error-message').text(response.message || 'Gagal generate QR code');
                }
            },
            error: function(xhr, status, error) {
                // Show error
                $('#qr-loading').addClass('d-none');
                $('#qr-error').removeClass('d-none');
                $('#qr-error-message').text('Terjadi kesalahan saat generate QR code');
                console.error('Error generating QR code:', error);
            }
        });
    }
});
</script>
@endsection
