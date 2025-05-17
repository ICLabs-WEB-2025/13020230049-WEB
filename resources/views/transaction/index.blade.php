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
                    <div class="col-lg-5 mb-4">
                        <div class="card">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0">Tambah Transaksi Baru</h5>
                            </div>
                            <div class="card-body">
                                <div class="transaction-type-toggle d-flex justify-content-center gap-2 mb-3">
                                    <button class="btn btn-outline-success active" id="incomeBtn">
                                        <i class="bi bi-graph-up-arrow"></i> Pemasukan
                                    </button>
                                    <button class="btn btn-outline-danger" id="expenseBtn">
                                        <i class="bi bi-graph-down-arrow"></i> Pengeluaran
                                    </button>
                                </div>

                                <form action="{{ route('transactions.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" id="transactionType" name="transaction_type" value="income">
                                    
                                    <div class="mb-3">
                                        <label for="amount" class="form-label">Jumlah (Rp)</label>
                                        <input type="number" class="form-control" id="amount" name="amount" required min="1">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="category" class="form-label">Kategori</label>
                                        <select class="form-select" id="category" name="category_id" required>
                                            <option value="" disabled selected>Pilih Kategori</option>
                                            <optgroup label="Pemasukan" id="incomeCategories">
                                                <option value="1">Gaji</option>
                                                <option value="2">Bisnis</option>
                                                <option value="3">Investasi</option>
                                                <option value="4">Hadiah</option>
                                                <option value="13">Lainnya</option>
                                            </optgroup>
                                            <optgroup label="Pengeluaran" id="expenseCategories" style="display:none;">
                                                <option value="6">Makanan & Minuman</option>
                                                <option value="7">Transportasi</option>
                                                <option value="8">Belanja</option>
                                                <option value="9">Tagihan</option>
                                                <option value="10">Hiburan</option>
                                                <option value="11">Kesehatan</option>
                                                <option value="12">Pendidikan</option>
                                                <option value="13">Lainnya</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="date" class="form-label">Tanggal</label>
                                        <input type="date" class="form-control" id="date" name="date" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Deskripsi</label>
                                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary w-100">Simpan Transaksi</button>
                                </form>

                            </div>
                        </div>
                    </div>

                    <!-- Tabel Transaksi -->
                    <div class="col-lg-7 d-none d-none d-md-block">
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
                                                    <td>{{ $transaction->created_at->format('d-m-Y') }}</td>
                                                    <td>{{ $transaction->category->category_name }}</td>
                                                    <td>{{ $transaction->description ?? '-' }}</td>
                                                    <td>{{ $transaction->transaction_type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}</td>
                                                    <td>Rp {{ number_format($transaction->amount, 2) }}</td>
                                                    <td>
                                                        <!-- Update Button: Arahkan ke halaman Edit -->
                                                        <a href="{{ route('transactions.edit', $transaction->id) }}" class="btn btn-warning btn-sm">Update</a>
                                                        <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" class="d-flex">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                        </form>
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
@endsection

@section('transaction-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('incomeBtn').addEventListener('click', function() {
                setTransactionType('income');
                console.log('Income button clicked');
            });
            
            document.getElementById('expenseBtn').addEventListener('click', function() {
                setTransactionType('expense');
                console.log('Expense button clicked');
            });
            
        });
        function setTransactionType(type) {
            if (type === 'income') {
                document.getElementById('incomeBtn').classList.add('active');
                document.getElementById('expenseBtn').classList.remove('active');
                document.getElementById('incomeCategories').style.display = 'block';
                document.getElementById('expenseCategories').style.display = 'none';
                document.getElementById('category').selectedIndex = 0;
            } else {
                document.getElementById('incomeBtn').classList.remove('active');
                document.getElementById('expenseBtn').classList.add('active');
                document.getElementById('incomeCategories').style.display = 'none';
                document.getElementById('expenseCategories').style.display = 'block';
                document.getElementById('category').selectedIndex = 0;
            }
        }
    </script>
@endsection
