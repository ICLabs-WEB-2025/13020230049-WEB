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

document.getElementById('btn-delete').addEventListener('click',function(){
        confirm('Yakin Ingin Menghapus Transakasi!');
        console.log("data di hapus");
    })
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


document.addEventListener('DOMContentLoaded', function () {
    let editTransaksiModal = document.getElementById('editTransaksiModal');
    let editTransactionForm = document.getElementById('editTransactionForm');

    editTransaksiModal.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget;

        let transactionId = button.getAttribute('data-id');
        let amount = button.getAttribute('data-amount');
        let categoryId = button.getAttribute('data-category_id');
        let transactionType = button.getAttribute('data-transaction_type');
        let date = button.getAttribute('data-date');
        let description = button.getAttribute('data-description') || '';

        editTransactionForm.action = '/transactions/' + transactionId;

        editTransactionForm.querySelector('#editAmount').value = amount;
        editTransactionForm.querySelector('#editCategory').value = categoryId;
        editTransactionForm.querySelector('#editTransactionType').value = transactionType;
        editTransactionForm.querySelector('#editDate').value = date;
        editTransactionForm.querySelector('#editDescription').value = description;
    });
});
