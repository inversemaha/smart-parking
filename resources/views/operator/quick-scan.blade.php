@extends('layouts.app')

@section('content')
<div class="container-fluid h-100 d-flex flex-column py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 text-dark mb-0">{{ __('admin.operator.quick_scan') }}</h1>
                    <p class="text-muted mb-0">Scan QR Codes for Quick Entry/Exit Operations</p>
                </div>
                <a href="{{ route('operator.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="row flex-grow-1 mb-4">
        <!-- QR Scanner -->
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white border-bottom">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-camera"></i> QR Code Scanner
                    </h6>
                </div>
                <div class="card-body d-flex flex-column justify-content-center align-items-center" style="min-height: 300px;">
                    <div id="scanner" style="width: 100%; max-width: 400px;"></div>
                    <p class="text-muted mt-3 text-center">Point camera at QR code to scan</p>
                </div>
            </div>
        </div>

        <!-- Manual Input -->
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white border-bottom">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-keyboard"></i> Manual Entry
                    </h6>
                </div>
                <div class="card-body d-flex flex-column justify-content-center">
                    <form id="manualForm">
                        <div class="mb-4">
                            <label class="form-label">License Plate</label>
                            <input type="text" id="manualInput" class="form-control form-control-lg text-center" 
                                placeholder="Tap or type license plate..."
                                style="font-size: 1.5rem; letter-spacing: 2px;">
                        </div>
                        <button type="submit" class="btn btn-primary w-100 btn-lg">
                            <i class="fas fa-arrow-right"></i> Process
                        </button>
                    </form>

                    <div id="lastScan" class="mt-4 d-none">
                        <div class="alert alert-info">
                            <h6 class="mb-2">Last Scan Result:</h6>
                            <div id="scanResult"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Scans -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white border-bottom">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Scan Results</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Time</th>
                                <th>Plate</th>
                                <th>Action</th>
                                <th>Result</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody id="recentScans">
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">Waiting for scans...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>
<script>
    // QR Scanner Setup
    const video = document.createElement('video');
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    const scanner = document.getElementById('scanner');
    
    // Add video element styling
    video.style.width = '100%';
    video.style.maxWidth = '400px';
    video.style.border = '2px solid #007bff';
    video.style.borderRadius = '8px';
    scanner.appendChild(video);

    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
        .then(stream => {
            video.srcObject = stream;
            video.play();
            
            // Start scanning
            function scan() {
                if (video.readyState === video.HAVE_ENOUGH_DATA) {
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                    
                    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                    const code = jsQR(imageData.data, imageData.width, imageData.height, {
                        inversionAttempts: 'dontInvert',
                    });
                    
                    if (code) {
                        processQRCode(code.data);
                    }
                }
                requestAnimationFrame(scan);
            }
            scan();
        })
        .catch(err => {
            scanner.innerHTML = '<div class="alert alert-warning">Camera access denied. Please use manual entry below.</div>';
        });

    // Process QR Code
    async function processQRCode(data) {
        try {
            const response = await fetch("{{ route('operator.scan') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ qr_code: data })
            });

            const result = await response.json();
            addScanResult(result);
        } catch (error) {
            console.error('Scan error:', error);
        }
    }

    // Process Manual Entry
    document.getElementById('manualForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const plate = document.getElementById('manualInput').value;
        
        if (plate.length < 3) {
            alert('Please enter a valid license plate');
            return;
        }

        try {
            const response = await fetch("{{ route('operator.entry') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ license_plate: plate })
            });

            const result = await response.json();
            addScanResult(result);
            document.getElementById('manualInput').value = '';
        } catch (error) {
            console.error('Entry error:', error);
        }
    });

    // Add scan to recent table
    function addScanResult(result) {
        const tbody = document.getElementById('recentScans');
        
        if (tbody.rows[0].cells[0].innerHTML === 'Time') {
            tbody.innerHTML = '';
        }

        const row = tbody.insertRow(0);
        const time = new Date().toLocaleTimeString();
        
        row.innerHTML = `
            <td>${time}</td>
            <td><strong>${result.license_plate || 'N/A'}</strong></td>
            <td><span class="badge bg-info">${result.action || 'Processing'}</span></td>
            <td>
                <span class="badge bg-${result.success ? 'success' : 'danger'}">
                    ${result.success ? '✓ Success' : '✗ Failed'}
                </span>
            </td>
            <td>${result.amount ? '৳ ' + result.amount : '-'}</td>
        `;
    }
</script>
@endpush
@endsection
