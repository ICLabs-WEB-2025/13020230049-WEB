<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="addFundsModalLabel">Tambah Dana ke Tujuan: <span id="addFundsGoalName" class="fw-bold"></span></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="addFundsForm" method="POST">
            @csrf
            <div class="modal-body">
                <div id="addFundsErrorAlertPlaceholder"></div>
                <div class="mb-3">
                    <label for="amount_to_add" class="form-label">Jumlah Dana Ditambahkan (Rp) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="amount_to_add" name="amount_to_add" value="{{ old('amount_to_add') }}" required min="0.01" step="any">
                </div>
                <div class="mb-3">
                    <label for="add_funds_date" class="form-label">Tanggal Penambahan Dana <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="add_funds_date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Tambah Dana</button>
            </div>
        </form>
    </div>
</div>