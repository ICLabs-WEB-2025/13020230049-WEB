@extends('layouts.app')

@section('content')
    @include('components.sidebard')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12 d-flex justify-content-bettween">
                    <div class="container-fluid">
                        <h2 class="mb-1 fs-bold">Kelola Transaksi</h2>
                        <p class="">Tambah dan lihat riwayat transaksi Anda</p>
                    </div>
                    <div class="form-check form-switch ms-auto">
                        <input class="form-check-input dark" type="checkbox" role="switch" id="darkModeToggle">
                        <label class="form-check-label dark" for="darkModeToggle">
                            <i id="moon-icon" class="bi bi-moon-stars-fill" style="display: none;"></i>
                            <i id="sun-icon" class="bi bi-sun-fill"></i>
                        </label>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Form Tambah Transaksi -->
                <div class="col-lg-4 col">
                    @include('components.addTransaksi')
                </div>
                <!-- Tabel Transaksi -->
                <div class="col-lg-8 col">
                    @include('components.tableTransaction')
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