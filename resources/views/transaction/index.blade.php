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
                <div class="col-lg-5 col">
                    @include('components.addTransaksi')
                </div>

                <!-- Tabel Transaksi -->
                <div class="col-lg-7 col">
                    @include('components.tabletransaction')
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Transaksi -->
    <div class="modal fade" id="editTransaksiModal" tabindex="-1" aria-labelledby="editTransaksiModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editTransaksiModalLabel">Edit Transaksi</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editTransactionForm" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="editAmount" class="form-label">Jumlah (Rp)</label>
                            <input type="number" class="form-control" id="editAmount" name="amount" required min="1">
                        </div>

                        <div class="mb-3">
                            <label for="editCategory" class="form-label">Kategori</label>
                            <select class="form-select" id="editCategory" name="category_id" required>
                                <option value="" disabled>Pilih Kategori</option>
                                @if(isset($categories))
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->category_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="editTransactionType" class="form-label">Tipe Transaksi</label>
                            <select class="form-select" id="editTransactionType" name="transaction_type" required>
                                <option value="income">Pemasukan</option>
                                <option value="expense">Pengeluaran</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="editDate" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="editDate" name="date" required>
                        </div>

                        <div class="mb-3">
                            <label for="editDescription" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="editDescription" name="description" rows="3"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- @include('components.editTransaction') -->
    </div>
    @include('components.navigationBar')
    <script src="{{ asset('js/transactions.js') }}"></script>
@endsection