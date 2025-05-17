@extends('layouts.app')

@section('content')
    @extends('layouts.sidebard')
    <div class="main-content">
        <div class="container-fluid">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="container p-0 d-flex justify-content-between">
                        <div class="container">
                            <h2 class="mb-1">Dashboard</h2>
                            <p>Selamat datang, <span class="text-info">{{ auth()->user()->username }}</span></p>
                        </div>
                        <div class="form-check form-switch ms-auto">
                            <input class="form-check-input dark" type="checkbox" role="switch" id="darkModeToggle">
                            <label class="form-check-label dark" for="darkModeToggle"><i class="bi bi-moon-stars-fill"></i> <i class="bi bi-sun-fill"></i></label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card stat-card balance">
                        <div class="stat-icon">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <div class="stat-title">SALDO TOTAL</div>
                        <div class="stat-value" id="totalBalance">Rp {{ number_format($totalIncome - $totalExpense, 2) }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card income">
                        <div class="stat-icon">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <div class="stat-title">TOTAL PEMASUKAN</div>
                        <div class="stat-value" id="totalIncome">Rp {{ number_format($totalIncome, 2) }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card expense">
                        <div class="stat-icon">
                            <i class="bi bi-graph-down-arrow"></i>
                        </div>
                        <div class="stat-title">TOTAL PENGELUARAN</div>
                        <div class="stat-value" id="totalExpense">Rp {{ number_format($totalExpense, 2) }}</div>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">Analisis Keuangan Bulanan</h5>
                        </div>
                        <div class="chart-container">
                            <canvas id="monthlyChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">Distribusi Pengeluaran</h5>
                        </div>
                        <div class="chart-container">
                            <canvas id="expenseChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- for Transactions -->
            <div class="row d-none d-md-block">
                <div class="col-12">
                    <div class="card ">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Transaksi Terbaru</h5>
                            <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                        </div>
                        <div class="transaction-table">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">Tanggal</th>
                                            <th scope="col">Kategori</th>
                                            <th scope="col">Deskripsi</th>
                                            <th scope="col">Tipe</th>
                                            <th scope="col">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody id="recentTransactionsTable">
                                        @forelse($transactions as $transaction)
                                            <tr>
                                                <td>{{ $transaction->created_at->format('d-m-Y') }}</td>
                                                <td>{{ $transaction->category->category_name }}</td>
                                                <td>{{ $transaction->description ?? '-' }}</td>
                                                <td>
                                                    <span class="transaction-badge {{ $transaction->transaction_type === 'income' ? 'badge-income' : 'badge-expense' }}">
                                                        {{ ucfirst($transaction->transaction_type) }}
                                                    </span>
                                                </td>
                                                <td class="{{ $transaction->transaction_type === 'income' ? 'income-text' : 'expense-text' }}">
                                                    {{ $transaction->transaction_type === 'income' ? '+' : '-' }} 
                                                    Rp {{ number_format($transaction->amount, 2) }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">Belum ada transaksi</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Grafik untuk Analisis Keuangan Bulanan
        const ctx1 = document.getElementById('monthlyChart').getContext('2d');
        const monthlyChart = new Chart(ctx1, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'], 
                datasets: [{
                    label: 'Pendapatan',
                    data: [3000, 4000, 2500, 3500, 4500, 5000],
                    borderColor: '#4CAF50',
                    backgroundColor: 'rgba(76, 175, 80, 0.1)',
                    borderWidth: 2,
                    fill: true
                }, {
                    label: 'Pengeluaran',
                    data: [1500, 1800, 1000, 1300, 2000, 2200], // Data pengeluaran
                    borderColor: '#F44336',
                    backgroundColor: 'rgba(244, 67, 54, 0.1)',
                    borderWidth: 2,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Grafik untuk Distribusi Pengeluaran
        const ctx2 = document.getElementById('expenseChart').getContext('2d');
        const expenseChart = new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: ['Belanja', 'Makanan', 'Tagihan', 'Transportasi'], // Kategori pengeluaran
                datasets: [{
                    data: [1200, 800, 500, 300], // Data pengeluaran
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>
@endsection
