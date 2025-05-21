@extends('layouts.app')

@section('content')
    @include('components.sidebard')

    <div class="main-content">
        <div class="container-fluid">
            
            <div class="row mb-3">
                <div class="col-sm-10">
                    <div class="">
                        <h1 class="mb-1 fs-bold ">Tujuan Tabungan Saya</h1>
                        <p class="text-muted">Lacak dan kelola semua target keuanganmu.</p>
                    </div>
                </div>
                <div class="col-sm-2 row">
                    <div class="col-12 text-center">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSavingsGoalModal">
                            <i class="bi bi-plus-circle-fill"></i> Tambah Tabungan Baru
                        </button>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->storeSavingsGoal->any() && !(session('open_modal_on_error') === 'addSavingsGoalModal'))
                <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                    <strong>Oops! Ada kesalahan saat menambah tujuan baru:</strong>
                    <ul>
                        @foreach ($errors->storeSavingsGoal->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if($errors->updateSavingsGoal->any() && !(session('open_modal_on_error') && Str::startsWith(session('open_modal_on_error'), 'editSavingsGoalModal-')))
                <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                    <strong>Oops! Ada kesalahan saat mengupdate tujuan:</strong>
                    <ul>
                        @foreach ($errors->updateSavingsGoal->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($savingsGoals->isEmpty())
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-emoji-frown fs-1 text-muted mb-3"></i>
                        <h5 class="card-title">Belum Ada Tujuan Tabungan</h5>
                        <p class="card-text">Mulai buat tujuan tabungan pertamamu untuk meraih impian finansialmu!</p>
                    </div>
                </div>
            @else
                <div class="row">
                    @foreach ($savingsGoals as $goal)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-lg goal-card">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title fw-bold">{{ $goal->goal_name }}</h5>
                                    <p class="card-text text-muted small mb-1">
                                        Target: <span class="fw-semibold">Rp {{ number_format($goal->target_amount, 0, ',', '.') }}</span>
                                    </p>
                                    @if($goal->target_date)
                                        <p class="card-text text-muted small mb-2">
                                            <i class="bi bi-calendar-event"></i> Batas Waktu: {{ \Carbon\Carbon::parse($goal->target_date)->format('d M Y') }}
                                            ({{ \Carbon\Carbon::parse($goal->target_date)->locale('id')->diffForHumans() }})
                                        </p>
                                    @endif

                                    @php
                                        $progressPercentage = ($goal->target_amount > 0) ? ($goal->current_amount / $goal->target_amount) * 100 : 0;
                                        $progressPercentage = min(round($progressPercentage), 100);
                                        $progressBarClass = 'bg-success';
                                        if ($progressPercentage < 30) { $progressBarClass = 'bg-danger'; }
                                        elseif ($progressPercentage < 70) { $progressBarClass = 'bg-warning text-dark'; }
                                    @endphp

                                    <div class="progress mb-1" style="height: 22px;">
                                        <div class="progress-bar {{ $progressBarClass }} progress-bar-striped progress-bar-animated" role="progressbar"
                                            style="width: {{ $progressPercentage }}%;"
                                            aria-valuenow="{{ $progressPercentage }}" aria-valuemin="0" aria-valuemax="100">
                                            <small>{{ $progressPercentage }}%</small>
                                        </div>
                                    </div>
                                    <p class="mb-1 small">
                                        Terkumpul: <span class="fw-semibold">Rp {{ number_format($goal->current_amount, 0, ',', '.') }}</span>
                                    </p>
                                    @if($goal->current_amount < $goal->target_amount)
                                        <p class="text-danger small">
                                            Kurang: Rp {{ number_format($goal->target_amount - $goal->current_amount, 0, ',', '.') }}
                                        </p>
                                    @else
                                        <p class="text-success fw-bold small">
                                            <i class="bi bi-check-circle-fill"></i> Target Tercapai!
                                        </p>
                                    @endif

                                    <div class="mt-auto pt-2 border-top-dashed">
                                        <button type="button" class="btn btn-sm btn-outline-info me-1 add-funds-btn" title="Tambah Dana"
                                                data-bs-toggle="modal" data-bs-target="#addFundsModal"
                                                data-goal-id="{{ $goal->id }}" data-goal-name="{{ $goal->goal_name }}">
                                            <i class="bi bi-plus-lg"></i> Dana
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-warning me-1 edit-goal-btn" title="Edit Tujuan"
                                                data-bs-toggle="modal" data-bs-target="#editSavingsGoalModal"
                                                data-id="{{ $goal->id }}"
                                                data-goal_name="{{ $goal->goal_name }}"
                                                data-target_amount="{{ $goal->target_amount }}"
                                                data-current_amount="{{ $goal->current_amount }}"
                                                data-target_date="{{ $goal->target_date }}">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </button>
                                        <form action="{{ route('savings-goals.destroy', $goal->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tujuan \'{{ $goal->goal_name }}\'?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Tujuan">
                                                <i class="bi bi-trash3-fill"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            

        </div>
    </div>

    <div class="modal fade" id="addSavingsGoalModal" tabindex="-1" aria-labelledby="addSavingsGoalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSavingsGoalModalLabel">Tambah Tujuan Tabungan Baru</h5>
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
                            <label for="goal_name_store" class="form-label">Nama Tujuan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('goal_name', 'storeSavingsGoal') is-invalid @enderror" id="goal_name_store" name="goal_name" value="{{ old('goal_name') }}" required>
                            @error('goal_name', 'storeSavingsGoal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="target_amount_store" class="form-label">Jumlah Target (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('target_amount', 'storeSavingsGoal') is-invalid @enderror" id="target_amount_store" name="target_amount" value="{{ old('target_amount') }}" required min="0.01" step="any">
                            @error('target_amount', 'storeSavingsGoal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="target_date_store" class="form-label">Tanggal Target <span class="text-danger">*</span></label>
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
    </div>

    <div class="modal fade" id="editSavingsGoalModal" tabindex="-1" aria-labelledby="editSavingsGoalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSavingsGoalModalLabel">Edit Tujuan Tabungan</h5>
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
                            <label for="edit_goal_name" class="form-label">Nama Tujuan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('goal_name', 'updateSavingsGoal') is-invalid @enderror" id="edit_goal_name" name="goal_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_target_amount" class="form-label">Jumlah Target (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('target_amount', 'updateSavingsGoal') is-invalid @enderror" id="edit_target_amount" name="target_amount" required min="0.01" step="any">
                        </div>
                        <div class="mb-3">
                            <label for="edit_current_amount" class="form-label">Jumlah Terkumpul (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('current_amount', 'updateSavingsGoal') is-invalid @enderror" id="edit_current_amount" name="current_amount" required min="0" step="any">
                        </div>
                        <div class="mb-3">
                            <label for="edit_target_date" class="form-label">Tanggal Target <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('target_date', 'updateSavingsGoal') is-invalid @enderror" id="edit_target_date" name="target_date" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addFundsModal" tabindex="-1" aria-labelledby="addFundsModalLabel" aria-hidden="true">
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
    </div>

    @include('components.navigationBar')
@endsection

@push('scripts')
    <script>
        window.pageData = {
            baseUrl: "{{ url('savings-goals') }}",
            today: "{{ date('Y-m-d') }}",
            session: {
                openModalOnError: @json(session('open_modal_on_error')),
                errorModalGoalId: @json(session('error_modal_goal_id')),
            },
            errors: {
                allBags: @json(session('errors') ? session('errors')->getBags() : new \Illuminate\Support\ViewErrorBag()),
                hasStoreErrors: @json($errors->storeSavingsGoal->any()),
                hasUpdateErrors: @json($errors->updateSavingsGoal->any())
            },
            oldInput: {
                editGoalName: @json(old('goal_name')),
                editTargetAmount: @json(old('target_amount')),
                editCurrentAmount: @json(old('current_amount')),
                editTargetDate: @json(old('target_date'))
            }
        };
    </script>
    <script src="{{ asset('js/main/savings-goals.js') }}"></script>
@endpush
