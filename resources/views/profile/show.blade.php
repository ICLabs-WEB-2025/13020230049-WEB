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
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
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
                            {{-- Tombol untuk edit profil dasar bisa ditambahkan di sini --}}
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
                                @if($recentPointActivities->count() >= 5) {{-- Tampilkan jika ada lebih banyak dari yang ditampilkan --}}
                                    {{-- <a href="#" class="btn btn-outline-secondary btn-sm mt-3 d-block">Lihat Semua Riwayat Poin</a> --}}
                                @endif
                            @endif
                        </div>
                    </div>

                    <div class="card shadow-sm mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-shield-lock-fill me-2"></i>Keamanan Akun</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{-- route('profile.updatePassword') --}}" method="POST">
                                @csrf
                                @method('PUT') {{-- atau PATCH --}}
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Password Saat Ini</label>
                                    <input type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" id="current_password" name="current_password" required>
                                    @error('current_password', 'updatePassword')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">Password Baru</label>
                                    <input type="password" class="form-control @error('new_password', 'updatePassword') is-invalid @enderror" id="new_password" name="new_password" required>
                                    @error('new_password', 'updatePassword')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Ubah Password</button>
                            </form>
                        </div>
                    </div>

                    {{-- Opsional: Kartu Ringkasan Tujuan Tabungan --}}
                    @if(isset($activeSavingsGoals) && !$activeSavingsGoals->isEmpty())
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-bullseye me-2"></i>Tujuan Tabungan Aktif</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                @foreach($activeSavingsGoals as $goal)
                                    <li class="list-group-item">
                                        <strong>{{ $goal->goal_name }}</strong>
                                        @php
                                            $progressPercentage = ($goal->target_amount > 0) ? ($goal->current_amount / $goal->target_amount) * 100 : 0;
                                            $progressPercentage = min(round($progressPercentage), 100);
                                        @endphp
                                        <div class="progress mt-1" style="height: 10px;">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $progressPercentage }}%;" aria-valuenow="{{ $progressPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <small class="text-muted">Rp {{ number_format($goal->current_amount, 0, ',', '.') }} / Rp {{ number_format($goal->target_amount, 0, ',', '.') }} ({{ $progressPercentage }}%)</small>
                                    </li>
                                @endforeach
                            </ul>
                            <a href="{{ route('savings-goals.index') }}" class="btn btn-outline-secondary btn-sm mt-3 d-block">Lihat Semua Tujuan</a>
                        </div>
                    </div>
                    @endif 
                </div>
            </div>
        </div>
    </div>
    @include('components.navigationBar')
@endsection