@extends('layouts.app')

@section('content')
    @include('components.sidebard')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="mb-1">Kelola Transaksi</h2>
                    <p class="text-muted">Tambah dan lihat riwayat transaksi Anda</p>
                </div>
            </div>

            <div class="row">
                <!-- Form Tambah Transaksi -->
                <div class="col-lg-4 col">
                    @include('components.addTransaksi')
                </div>
                <!-- Tabel Transaksi -->
                <div class="col-lg-8 col">
                    @include('components.tabletransaction')
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Transaksi -->
    <div class="modal fade" id="editTransaksiModal" tabindex="-1" aria-labelledby="editTransaksiModalLabel" aria-hidden="true">
        @include('components.editTransaction')
    </div>
    @include('components.navigationBar')
    <script src="{{ asset('js/transactions.js') }}"></script>
@endsection