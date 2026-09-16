@extends('layouts.frontend')

@section('content')
<!-- Load Bootstrap CSS & Icons secara manual untuk memastikan styling bekerja -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
                <div class="mb-3">
                    <span class="badge bg-primary-subtle text-primary p-3 rounded-circle">
                        <i class="bi bi-credit-card fs-2"></i>
                    </span>
                </div>

                <h4 class="fw-bold mb-1 text-dark">Selesaikan Pembayaran</h4>
                <p class="text-muted small">Nomor Pesanan: <strong class="text-dark">{{ $order->order_number }}</strong></p>

                <div class="bg-light p-3 rounded-3 my-3 text-start">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary small">Total Tagihan</span>
                        <strong class="text-primary fs-5">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-secondary small">Status</span>
                        <span class="badge bg-warning text-dark text-uppercase px-2 py-1">{{ $order->status }}</span>
                    </div>
                </div>

                <button id="pay-button" class="btn btn-primary btn-lg w-100 fw-bold rounded-3 shadow-sm py-2 mt-2">
                    Bayar Sekarang
                </button>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT MIDTRANS SNAP --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY', 'Mid-client-ULPHEgqilel36aEd') }}"></script>

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function () {
        const payButton = document.getElementById('pay-button');
        
        if (payButton) {
            payButton.addEventListener('click', function (e) {
                e.preventDefault();
                
                // Triger Midtrans Snap Popup
                window.snap.pay('{{ $order->snap_token }}', {
                    onSuccess: function (result) {
                        alert("Pembayaran berhasil!");
                        window.location.href = "/";
                    },
                    onPending: function (result) {
                        alert("Menunggu pembayaran kamu!");
                        location.reload();
                    },
                    onError: function (result) {
                        alert("Pembayaran gagal!");
                    },
                    onClose: function () {
                        alert('Kamu menutup halaman pembayaran sebelum selesai.');
                    }
                });
            });
        }
    });
</script>
@endsection