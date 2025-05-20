@extends('layouts.app')

@section('content')
    @extends('layouts.sidebard')
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
                @include('components.addTransaksi')

                <!-- Tabel Transaksi -->
                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Riwayat Transaksi</h5>
                            <div class="d-flex">
                                <input type="month" class="form-control me-2" id="monthFilter" style="max-width: 200px;">
                                <select class="form-select me-2" id="typeFilter" style="max-width: 150px;">
                                    <option value="all">Semua Tipe</option>
                                    <option value="income">Pemasukan</option>
                                    <option value="expense">Pengeluaran</option>
                                </select>
                            </div>
                        </div>
                        <div class="transaction-table">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">Tanggal</th>
                                            <th scope="col">Kategori</th>
                                            <th scope="col">Deskripsi</th>
                                            <th scope="col">Tipe</th>
                                            <th scope="col">Jumlah</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="transactionsTable">
                                        @foreach($transactions as $transaction)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($transaction->date)->format('d-m-Y') }}</td>
                                                <td>{{ $transaction->category->category_name }}</td>
                                                <td>{{ $transaction->description ?? '-' }}</td>
                                                <td class="{{ $transaction->transaction_type === 'income' ? 'income-text' : 'expense-text' }}" >
                                                    {{ $transaction->transaction_type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                                                </td>
                                                <td>Rp {{ number_format($transaction->amount, 2) }}</td>
                                                <td>
                                                    <div class="container-fluid d-flex align-items-center">
                                                        <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" class="d-flex gap-1">
                                                            @csrf
                                                            <button type="button" class="btn btn-sm btn-warning btn-edit"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#editTransaksiModal"
                                                                    data-id="{{ $transaction->id }}"
                                                                    data-amount="{{ $transaction->amount }}"
                                                                    data-category_id="{{ $transaction->category_id }}"
                                                                    data-transaction_type="{{ $transaction->transaction_type }}"
                                                                    data-date="{{ $transaction->date }}"
                                                                    data-description="{{ $transaction->description }}">
                                                                Edit
                                                            </button>
                                                            @method('DELETE')
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
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
</div>

    <script src="{{ asset('js/transactions.js') }}"></script>
@endsection