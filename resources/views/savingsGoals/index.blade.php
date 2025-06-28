@extends('layouts.app')

@section('content')
    @include('components.sidebard')

    <div class="main-content">
        <div class="container-fluid">
            
            <div class="row mb-3">
                <div class="col-10">
                    <h1 class="mb-1 fs-bold ">Tujuan Tabungan Saya</h1>
                    <p class="">Lacak dan kelola semua target keuanganmu.</p>
                </div>
                <div class="col-2 row">
                    <div class="form-check form-switch ms-auto d-flex align-items-center">
                        <input class="form-check-input dark" type="checkbox" role="switch" id="darkModeToggle">
                        <label class="form-check-label dark" for="darkModeToggle">
                            <i id="moon-icon" class="bi bi-moon-stars-fill" style="display: none;"></i>
                            <i id="sun-icon" class="bi bi-sun-fill"></i>
                        </label>
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
                                        Terkumpul: <span class="fw-semibold">Rp {{ number_format($goal->current_amount) }}</span>
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
        <div class="fixed-bottom z-1 text-center p-3 mb-5">
            <div class="col-12 text-center mb-3">
                <button type="button" class="btn btn-primary" style="max-width: 250px;" data-bs-toggle="modal" data-bs-target="#addSavingsGoalModal">
                    <i class="bi bi-plus-circle-fill"></i> Tambah Tabungan Baru
                </button>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addSavingsGoalModal" tabindex="-1" aria-labelledby="addSavingsGoalModalLabel" aria-hidden="true">
        @include('components.addNameGoals')
    </div>

    <div class="modal fade" id="editSavingsGoalModal" tabindex="-1" aria-labelledby="editSavingsGoalModalLabel" aria-hidden="true">
        @include('components.editGoals  ')
    </div>

    <div class="modal fade" id="addFundsModal" tabindex="-1" aria-labelledby="addFundsModalLabel" aria-hidden="true">
        @include('components.addDanaGoals')
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
