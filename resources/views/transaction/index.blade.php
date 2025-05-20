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
                                                <td>{{ \Carbon\Carbon::parse($transaction->date)->format('d-m-Y') }}</td>
                                                <td>{{ $transaction->category->category_name }}</td>
                                                <td>{{ $transaction->description ?? '-' }}</td>
                                                <td>{{ $transaction->transaction_type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}</td>
                                                <td>Rp {{ number_format($transaction->amount, 2) }}</td>
                                                <td>
                                                    <div class="container-fluid d-flex align-items-center">
                                                        <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" class="d-flex gap-1">
                                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editTransaksi" 
                                                                data-id="{{ $transaction->id }}"
                                                                data-amount="{{ $transaction->amount }}"
                                                                data-category_id="{{ $transaction->category_id }}"
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
    <div class="modal fade" id="editTransaksi" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Transaksi</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form Edit Transaksi -->
                    <form id="editTransactionForm" action="{{ route('transactions.update', $transaction->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <input type="hidden" id="transactionId" name="id">

                        <div class="mb-3">
                            <label for="amount" class="form-label">Jumlah (Rp)</label>
                            <input type="number" class="form-control" id="editAmount" name="amount" required>
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Kategori</label>
                            <select class="form-select" id="editCategory" name="category_id" required>
                                <!-- Kategori akan dimuat di sini menggunakan AJAX -->
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="date" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="editDate" name="date" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="editDescription" name="description" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveChanges">Save changes</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('transaction-scripts')
    <!-- Menambahkan jQuery sebelum script lainnya -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('incomeBtn').addEventListener('click', function() {
                setTransactionType('income');
            });
            
            document.getElementById('expenseBtn').addEventListener('click', function() {
                setTransactionType('expense');
            });

        });

        function setTransactionType(type) {
            if (type === 'income') {
                document.getElementById('incomeBtn').classList.add('active');
                document.getElementById('expenseBtn').classList.remove('active');
                document.getElementById('incomeCategories').style.display = 'block';
                document.getElementById('expenseCategories').style.display = 'none';
            } else {
                document.getElementById('incomeBtn').classList.remove('active');
                document.getElementById('expenseBtn').classList.add('active');
                document.getElementById('incomeCategories').style.display = 'none';
                document.getElementById('expenseCategories').style.display = 'block';
            }
        }

        // Fungsi untuk memuat kategori
        function loadCategories(selectedCategoryId) {
            $.ajax({
                url: '/categories',  // URL untuk mendapatkan kategori
                method: 'GET',
                success: function(data) {
                    var categorySelect = $('#editCategory');
                    categorySelect.empty(); // Menghapus kategori lama
                    categorySelect.append('<option value="" disabled selected>Pilih Kategori</option>'); // Tambahkan opsi default

                    // Menambahkan kategori ke dalam select
                    data.forEach(function(category) {
                        categorySelect.append('<option value="' + category.id + '">' + category.category_name + '</option>');
                    });

                    // Set kategori yang sesuai jika ada
                    if (selectedCategoryId) {
                        categorySelect.val(selectedCategoryId); // Set nilai kategori yang terpilih
                    }
                },
                error: function(error) {
                    console.log('Error loading categories:', error);
                }
            });
        }

        // Ketika tombol Edit diklik, ambil data atribut dan masukkan ke modal
        $('#editTransaksi').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Tombol yang memicu modal
            var transactionId = button.data('id');
            var amount = button.data('amount');
            var categoryId = button.data('category_id');
            var date = button.data('date');
            var description = button.data('description');

            // Isi form di dalam modal dengan data transaksi
            $('#transactionId').val(transactionId);
            $('#editAmount').val(amount);
            $('#editDate').val(date);
            $('#editDescription').val(description);

            // Muat kategori dan pilih kategori yang sesuai
            loadCategories(categoryId); // Mengisi kategori dan set yang sesuai
        });

        $('#saveChanges').click(function() {
            var formData = $('#editTransactionForm').serialize(); // Ambil data dari form modal
            var transactionId = $('#transactionId').val(); // Ambil ID transaksi

            $.ajax({
                url: '/transactions/' + transactionId,  // Pastikan URL ini benar
                method: 'PUT',  // Gunakan metode PUT untuk update
                data: formData,  // Kirim data form
                success: function(response) {
                    alert('Transaksi berhasil diperbarui');
                    location.reload(); // Reload halaman setelah update berhasil
                },
                error: function(error) {
                    console.log(error.responseText);  // Log error dari server
                    alert('Terjadi kesalahan saat memperbarui transaksi');
                }
            });
        });



    </script>
@endsection
