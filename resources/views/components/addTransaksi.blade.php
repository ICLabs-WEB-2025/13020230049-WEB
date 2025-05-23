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
                    <label for="amount" class="form-label text-dark">Jumlah (Rp)</label>
                    <input type="number" class="form-control" id="amount" name="amount" required min="1" placeholder="9000">
                </div>
                
                <div class="mb-3">
                    <label for="category" class="form-label text-dark">Kategori</label>
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
                    <label for="date" class="form-label text-dark">Tanggal</label>
                    <input type="date" class="form-control" id="date" name="date" required>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label text-dark">Deskripsi</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary w-100">Simpan Transaksi</button>
            </form>
        </div>
    </div>