document.addEventListener('DOMContentLoaded', function () {
    if (typeof window.pageData === 'undefined') {
        console.error('Page data (window.pageData) not found. Ensure it is defined in the Blade template.');
        return;
    }

    const pageData = window.pageData;
    console.log('Savings Goals DOMContentLoaded. Page Data:', pageData);

    const editSavingsGoalModalEl = document.getElementById('editSavingsGoalModal');
    if (editSavingsGoalModalEl) {
        console.log('Event listener untuk editSavingsGoalModal ditambahkan.');
        editSavingsGoalModalEl.addEventListener('show.bs.modal', function (event) {
            console.log('Modal Edit Tujuan dibuka.');
            const button = event.relatedTarget;
            if (!button) {
                console.error('Tombol pemicu modal edit tidak ditemukan.'); return;
            }
            const form = document.getElementById('editSavingsGoalForm');
            const goalId = button.getAttribute('data-id');

            console.log('Edit Goal ID:', goalId);

            form.action = `${pageData.baseUrl}/${goalId}`;
            console.log('Edit Form Action di-set ke:', form.action);

            form.querySelector('#edit_goal_name').value = button.getAttribute('data-goal_name');
            form.querySelector('#edit_target_amount').value = parseFloat(button.getAttribute('data-target_amount')).toFixed(2);
            form.querySelector('#edit_current_amount').value = parseFloat(button.getAttribute('data-current_amount')).toFixed(2);
            form.querySelector('#edit_target_date').value = button.getAttribute('data-target_date');

            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
        });
    } else {
        console.error("Elemen modal dengan ID 'editSavingsGoalModal' tidak ditemukan.");
    }

    // Menangani pembukaan modal tambah dana
    const addFundsModalEl = document.getElementById('addFundsModal');
    const addFundsErrorAlertPlaceholder = document.getElementById('addFundsErrorAlertPlaceholder');

    if (addFundsModalEl) {
        console.log('Event listener untuk addFundsModal ditambahkan.');
        addFundsModalEl.addEventListener('show.bs.modal', function (event) {
            console.log('Modal Tambah Dana dibuka.');
            const button = event.relatedTarget;
            if (!button) {
                console.error('Tombol pemicu modal tambah dana tidak ditemukan.'); return;
            }
            const goalId = button.getAttribute('data-goal-id');
            const goalName = button.getAttribute('data-goal-name');
            const form = document.getElementById('addFundsForm');

            if (!goalId || !goalName) {
                console.error('data-goal-id atau data-goal-name tidak ada pada tombol pemicu.');
                document.getElementById('addFundsGoalName').textContent = 'Error: Info Tujuan Tidak Lengkap';
                form.action = '#';
                return;
            }

            console.log('Tambah Dana untuk Goal ID:', goalId, 'Nama:', goalName);

            document.getElementById('addFundsGoalName').textContent = goalName;
            form.action = `${pageData.baseUrl}/${goalId}/add-funds`;
            console.log('Add Funds Form Action di-set ke:', form.action);

            if (addFundsErrorAlertPlaceholder) addFundsErrorAlertPlaceholder.innerHTML = '';
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            form.querySelector('#amount_to_add').value = '';
            form.querySelector('#add_funds_date').value = pageData.today;
        });
    } else {
        console.error("Elemen modal dengan ID 'addFundsModal' tidak ditemukan.");
    }

    // Otomatis membuka modal jika ada error validasi dari server
    if (pageData.session.openModalOnError) {
        console.log("Session 'openModalOnError' ada:", pageData.session.openModalOnError);
        let modalIdToOpen = pageData.session.openModalOnError;
        let errorGoalId = pageData.session.errorModalGoalId;
        console.log("Error Goal ID dari session:", errorGoalId);

        if (modalIdToOpen === 'addFundsModal' && errorGoalId) {
            console.log("Mencoba membuka modal Tambah Dana karena error validasi untuk Goal ID:", errorGoalId);
            const errorBagName = 'addFundsErrorBag_goal_' + errorGoalId;
            let errorsForModal = null;

            if (pageData.errors.allBags && pageData.errors.allBags[errorBagName]) {
                errorsForModal = pageData.errors.allBags[errorBagName];
                console.log("Errors untuk modal " + errorBagName + ":", errorsForModal);
            } else {
                console.warn("Error bag " + errorBagName + " tidak ditemukan di pageData.errors.allBags.");
            }

            if (errorsForModal && addFundsErrorAlertPlaceholder) {
                let errorHtml = '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Oops! Ada kesalahan:</strong><ul>';
                for (const field in errorsForModal) {
                    errorsForModal[field].forEach(message => {
                        errorHtml += `<li>${message}</li>`;
                    });
                    const inputFieldId = field === 'amount_to_add' ? 'amount_to_add' : (field === 'date' ? 'add_funds_date' : null);
                    if (inputFieldId) {
                        const inputField = document.getElementById(inputFieldId);
                        if (inputField) inputField.classList.add('is-invalid');
                    }
                }
                errorHtml += '</ul><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                addFundsErrorAlertPlaceholder.innerHTML = errorHtml;
            }

            const modalElementToOpen = document.getElementById('addFundsModal');
            if (modalElementToOpen) {
                const modalInstance = bootstrap.Modal.getOrCreateInstance(modalElementToOpen);
                modalInstance.show();
                const goalNameForErrorModal = document.querySelector(`.add-funds-btn[data-goal-id="${errorGoalId}"]`)?.getAttribute('data-goal-name');
                if (goalNameForErrorModal) document.getElementById('addFundsGoalName').textContent = goalNameForErrorModal;
                else console.warn("Tidak bisa menemukan nama goal untuk error modal Tambah Dana, ID:", errorGoalId);
            } else {
                console.error("Elemen modal 'addFundsModal' tidak ditemukan saat mencoba membuka karena error.");
            }

        } else if (modalIdToOpen && modalIdToOpen.startsWith('editSavingsGoalModal-')) {
            console.log("Mencoba membuka modal Edit Tujuan karena error validasi:", modalIdToOpen);
            const modalElementToOpen = document.getElementById(modalIdToOpen);
            if (modalElementToOpen) {
                const modalInstance = bootstrap.Modal.getOrCreateInstance(modalElementToOpen);
                modalInstance.show();
                const form = document.getElementById('editSavingsGoalForm');
                if (pageData.errors.hasUpdateErrors) { // Cek apakah ada error untuk update
                    console.log("Mengisi ulang form Edit Tujuan dengan old input dari pageData.");
                    if (pageData.oldInput.editGoalName) form.querySelector('#edit_goal_name').value = pageData.oldInput.editGoalName;
                    if (pageData.oldInput.editTargetAmount) form.querySelector('#edit_target_amount').value = pageData.oldInput.editTargetAmount;
                    if (pageData.oldInput.editCurrentAmount) form.querySelector('#edit_current_amount').value = pageData.oldInput.editCurrentAmount;
                    if (pageData.oldInput.editTargetDate) form.querySelector('#edit_target_date').value = pageData.oldInput.editTargetDate;
                }
            } else {
                console.error("Elemen modal Edit Tujuan tidak ditemukan:", modalIdToOpen);
            }
        } else if (modalIdToOpen === 'addSavingsGoalModal' && pageData.errors.hasStoreErrors) {
            console.log("Mencoba membuka modal Tambah Tujuan Baru karena error validasi.");
            const addGoalModalElement = document.getElementById('addSavingsGoalModal');
            if (addGoalModalElement) {
                const modalInstance = bootstrap.Modal.getOrCreateInstance(addGoalModalElement);
                if (!modalInstance._isShown) { 
                    modalInstance.show();
                }
            } else {
                console.error("Elemen modal 'addSavingsGoalModal' tidak ditemukan saat mencoba membuka karena error Tambah Tujuan Baru."); //DEBUG
            }
        }
    }
});