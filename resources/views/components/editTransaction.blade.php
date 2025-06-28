    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 text-dark" id="editTransaksiModalLabel">Edit Transaksi</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editTransactionForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="editAmount" class="form-label text-dark">Jumlah (Rp)</label>
                        <input type="number" class="form-control" id="editAmount" name="amount" required min="1">
                    </div>

                    <div class="mb-3">
                        <label for="editTransactionType" class="form-label text-dark">Tipe Transaksi</label>
                        <select class="form-select" id="editTransactionType" name="transaction_type" required>
                            <option value="income">Pemasukan</option>
                            <option value="expense">Pengeluaran</option>
                        </select>
                    </div>
                                    
                    <div id="category-display" class="mb-3">
                        <label for="editCategory" class="form-label text-dark">Kategori</label>
                        <select class="form-select" id="editCategory" name="category_id" required>
                            <option value="" disabled selected>Pilih Kategori</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="editDate" class="form-label text-dark">Tanggal</label>
                        <input type="date" class="form-control" id="editDate" name="date" required>
                    </div>

                    <div class="mb-3">
                        <label for="editDescription" class="form-label text-dark">Deskripsi</label>
                        <textarea class="form-control" id="editDescription" name="description" rows="3"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        const category = document.getElementById('editCategory');
        document.getElementById('editTransactionType').addEventListener('click',(e)=>{
            category.value="";
        })
        document.addEventListener('DOMContentLoaded', function() {
        const transactionTypeSelect = document.getElementById('editTransactionType');
        const categorySelect = document.getElementById('editCategory');
        
        
        const incomeCategories = @json($incomeCategories);
        const expenseCategories = @json($expenseCategories);

        function updateCategoryOptions(selectedType) {
            
            categorySelect.innerHTML = '<option value="" disabled selected>Pilih Kategori</option>';

            
            let categoriesToShow = [];
            if (selectedType === 'income') {
                categoriesToShow = incomeCategories;
            } else if (selectedType === 'expense') {
                categoriesToShow = expenseCategories;
            }

            
            if (categoriesToShow.length > 0) {
                categoriesToShow.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.category_name;
                    categorySelect.appendChild(option);
                });
            } else {
                
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'Tidak ada kategori tersedia';
                option.disabled = true;
                categorySelect.appendChild(option);
            }
        }

        
        transactionTypeSelect.addEventListener('change', function() {
            updateCategoryOptions(this.value);
        });

        
        
        const initialType = transactionTypeSelect.value;
        if (initialType) {
            updateCategoryOptions(initialType);
        }
    });
    </script>