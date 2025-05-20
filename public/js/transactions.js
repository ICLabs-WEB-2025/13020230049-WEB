document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('incomeBtn').addEventListener('click', function() {
        setTransactionType('income');
    });
    
    document.getElementById('expenseBtn').addEventListener('click', function() {
        setTransactionType('expense');
        const transactionType = document.getElementById('transactionType').value='expense';
    });

    const categoryName = document.getElementsByClassName('categoryName');
    console.log(categoryName);

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

// Letakkan ini di dalam <script> tag di akhir file index.blade.php atau di file JS terpisah

document.addEventListener('DOMContentLoaded', function () {
    let editTransaksiModal = document.getElementById('editTransaksiModal');
    let editTransactionForm = document.getElementById('editTransactionForm');

    // Event listener untuk modal ditampilkan
    editTransaksiModal.addEventListener('show.bs.modal', function (event) {
        // Tombol yang memicu modal
        let button = event.relatedTarget;

        // Ambil data dari atribut data-* tombol
        let transactionId = button.getAttribute('data-id');
        let amount = button.getAttribute('data-amount');
        let categoryId = button.getAttribute('data-category_id');
        let transactionType = button.getAttribute('data-transaction_type');
        let date = button.getAttribute('data-date');
        // Untuk textarea, null atau string kosong perlu ditangani
        let description = button.getAttribute('data-description') || '';

        // Atur action form
        // Pastikan URL '/transactions/' sudah benar, atau gunakan helper route jika bisa dari JS
        editTransactionForm.action = '/transactions/' + transactionId;

        // Isi field-field form di modal
        editTransactionForm.querySelector('#editAmount').value = amount;
        editTransactionForm.querySelector('#editCategory').value = categoryId;
        editTransactionForm.querySelector('#editTransactionType').value = transactionType;
        editTransactionForm.querySelector('#editDate').value = date;
        editTransactionForm.querySelector('#editDescription').value = description;
    });
});