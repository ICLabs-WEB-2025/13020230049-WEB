<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title text-dark" id="editSavingsGoalModalLabel">Edit Tujuan Tabungan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="editSavingsGoalForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                @if($errors->updateSavingsGoal->any() && session('open_modal_on_error') && Str::startsWith(session('open_modal_on_error'), 'editSavingsGoalModal-'))
                <div class="alert alert-danger py-2">
                    <ul>@foreach ($errors->updateSavingsGoal->all() as $error)<li><small>{{ $error }}</small></li>@endforeach</ul>
                </div>
                @endif
                <div class="mb-3">
                    <label for="edit_goal_name" class="form-label text-dark">Nama Tujuan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('goal_name', 'updateSavingsGoal') is-invalid @enderror" id="edit_goal_name" name="goal_name" required>
                </div>
                <div class="mb-3">
                    <label for="edit_target_amount" class="form-label text-dark">Jumlah Target (Rp) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('target_amount', 'updateSavingsGoal') is-invalid @enderror" id="edit_target_amount" name="target_amount" required min="0.01" step="any">
                </div>
                <div class="mb-3">
                    <label for="edit_current_amount" class="form-label text-dark">Jumlah Terkumpul (Rp) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('current_amount', 'updateSavingsGoal') is-invalid @enderror" id="edit_current_amount" name="current_amount" required min="0" step="any">
                </div>
                <div class="mb-3">
                    <label for="edit_target_date" class="form-label text-dark">Tanggal Target <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('target_date', 'updateSavingsGoal') is-invalid @enderror" id="edit_target_date" name="target_date" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>