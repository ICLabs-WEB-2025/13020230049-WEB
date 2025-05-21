@extends('layouts.app') {{-- Sesuaikan dengan layout utama kamu --}}

@section('content')
    @include('components.sidebard') {{-- Sesuaikan dengan cara kamu meng-include sidebar --}}

    <div class="main-content">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="mb-1">Profil Saya</h2>
                    <p class="text-muted">Lihat dan kelola informasi akunmu.</p>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            {{-- Menampilkan error validasi spesifik untuk modal hapus akun --}}
            @if($errors->deleteAccount->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Oops! Ada kesalahan saat mencoba menghapus akun:</strong>
                    <ul>
                        @foreach ($errors->deleteAccount->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif


            <div class="row">
                <div class="col-lg-4 col-md-5 mb-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-person-circle me-2"></i>Informasi Akun</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Username:</strong> {{ $user->username }}</p>
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                            <p><strong>Bergabung Sejak:</strong> {{ $user->created_at->format('d F Y') }}</p>
                            {{-- <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm mt-2">Edit Profil</a> --}}
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="bi bi-star-fill me-2"></i>Poin Saya</h5>
                        </div>
                        <div class="card-body text-center">
                            <h3 class="display-4 fw-bold">{{ $totalPoints ?? 0 }}</h3>
                            <p class="text-muted mb-0">Total Poin</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8 col-md-7 mb-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-list-stars me-2"></i>Riwayat Poin Terbaru</h5>
                        </div>
                        <div class="card-body">
                            @if($recentPointActivities->isEmpty())
                                <p class="text-muted">Belum ada aktivitas poin yang tercatat.</p>
                            @else
                                <ul class="list-group list-group-flush">
                                    @foreach($recentPointActivities as $activity)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong class="d-block">{{ $activity->description ?: ($activity->achievement_type ? Str::title(str_replace('_', ' ', $activity->achievement_type)) : 'Aktivitas Poin') }}</strong>
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar-event"></i> {{ $activity->achievement_date ? \Carbon\Carbon::parse($activity->achievement_date)->format('d M Y') : $activity->created_at->format('d M Y') }}
                                                </small>
                                            </div>
                                            <span class="badge bg-success rounded-pill">+{{ $activity->points_earned }} Poin</span>
                                        </li>
                                    @endforeach
                                </ul>
                                @if($recentPointActivities->count() >= 5)
                                    {{-- <a href="#" class="btn btn-outline-secondary btn-sm mt-3 d-block">Lihat Semua Riwayat Poin</a> --}}
                                @endif
                            @endif
                        </div>
                    </div>

                    

                    @if(isset($activeSavingsGoals) && !$activeSavingsGoals->isEmpty())
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-bullseye me-2"></i>Tujuan Tabungan Aktif</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                @foreach($activeSavingsGoals as $goal)
                                    <li class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">{{ $goal->goal_name }}</h6>
                                            <small class="text-muted">
                                                @if($goal->target_date)
                                                    Target: {{ \Carbon\Carbon::parse($goal->target_date)->format('d M Y') }}
                                                @endif
                                            </small>
                                        </div>
                                        @php
                                            $progressPercentage = ($goal->target_amount > 0) ? ($goal->current_amount / $goal->target_amount) * 100 : 0;
                                            $progressPercentage = min(round($progressPercentage), 100);
                                            $progressBarClass = 'bg-success';
                                            if ($progressPercentage < 30) { $progressBarClass = 'bg-danger'; }
                                            elseif ($progressPercentage < 70) { $progressBarClass = 'bg-warning text-dark'; }
                                        @endphp
                                        <div class="progress mt-1 mb-1" style="height: 12px;">
                                            <div class="progress-bar {{ $progressBarClass }}" role="progressbar" style="width: {{ $progressPercentage }}%;" aria-valuenow="{{ $progressPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <small class="text-muted d-block">
                                            Rp {{ number_format($goal->current_amount, 0, ',', '.') }} / Rp {{ number_format($goal->target_amount, 0, ',', '.') }}
                                            <span class="float-end">{{ $progressPercentage }}%</span>
                                        </small>
                                    </li>
                                @endforeach
                            </ul>
                            <a href="{{ route('savings-goals.index') }}" class="btn btn-outline-secondary btn-sm mt-3 d-block">Lihat Semua Tujuan Tabungan</a>
                        </div>
                    </div>
                    @endif
                    <div class="card shadow-sm mb-4 border-danger">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0"><i class="bi bi-exclamation-triangle-fill me-2"></i>Hapus Akun</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-danger"><strong>Peringatan:</strong> Tindakan ini akan menghapus semua data Anda secara permanen dan tidak dapat dibatalkan.</p>
                            <p>Jika Anda yakin ingin melanjutkan, silakan konfirmasi dengan menekan tombol di bawah ini.</p>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                Hapus Akun Saya
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteAccountModalLabel"><i class="bi bi-exclamation-triangle-fill"></i> Konfirmasi Hapus Akun</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('profile.destroyAccount') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p><strong>Anda yakin ingin menghapus akun Anda secara permanen?</strong></p>
                        <p class="text-danger">Semua data Anda, termasuk transaksi, tujuan tabungan, dan poin akan dihapus dan tidak dapat dipulihkan.</p>
                        <div class="mb-3">
                            <label for="password_delete" class="form-label">Untuk melanjutkan, masukkan password Anda:</label>
                            <input type="password" class="form-control @error('password_delete', 'deleteAccount') is-invalid @enderror" id="password_delete" name="password_delete" required>
                            @error('password_delete', 'deleteAccount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Ya, Hapus Akun Saya</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('components.navigationBar')
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Jika ada error pada modal hapus akun dan modalnya perlu dibuka kembali
    @if($errors->deleteAccount->any() && session('open_modal_on_error') === 'deleteAccountModal')
        const deleteModalElement = document.getElementById('deleteAccountModal');
        if (deleteModalElement) {
            const modal = new bootstrap.Modal(deleteModalElement);
            modal.show();
        }
    @endif
});
</script>
@endpush