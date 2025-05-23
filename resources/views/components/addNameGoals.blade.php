<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title text-dark" id="addSavingsGoalModalLabel">Tambah Tujuan Tabungan Baru</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('savings-goals.store') }}" method="POST" id="addSavingsGoalForm">
            @csrf
            <div class="modal-body">
                @if($errors->storeSavingsGoal->any() && session('open_modal_on_error') === 'addSavingsGoalModal')
                <div class="alert alert-danger py-2">
                    <ul>@foreach ($errors->storeSavingsGoal->all() as $error)<li><small>{{ $error }}</small></li>@endforeach</ul>
                </div>
                @endif
                <div class="mb-3">
                    <label for="goal_name_store" class="form-label text-dark">Nama Tujuan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('goal_name', 'storeSavingsGoal') is-invalid @enderror" id="goal_name_store" name="goal_name" value="{{ old('goal_name') }}" required>
                    @error('goal_name', 'storeSavingsGoal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="target_amount_store" class="form-label text-dark">Jumlah Target (Rp) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('target_amount', 'storeSavingsGoal') is-invalid @enderror" id="target_amount_store" name="target_amount" value="{{ old('target_amount') }}" required min="0.01" step="any">
                    @error('target_amount', 'storeSavingsGoal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="target_date_store" class="form-label text-dark">Tanggal Target <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('target_date', 'storeSavingsGoal') is-invalid @enderror" id="target_date_store" name="target_date" value="{{ old('target_date') }}" required>
                    @error('target_date', 'storeSavingsGoal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan Tujuan</button>
            </div>
        </form>
    </div>
</div>