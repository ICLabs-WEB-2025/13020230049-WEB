@extends('layouts.app')

@section('content')
    @include('components.sidebard')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="container p-0 d-flex justify-content-between">
                        <div class="container">
                            <h2 class="mb-1">Dashboard</h2>
                            <p>Selamat datang, <span class="text-info">{{ auth()->user()->username }}</span></p>
                        </div>
                        <div class="form-check form-switch ms-auto">
                            <input class="form-check-input dark" type="checkbox" role="switch" id="darkModeToggle">
                            <label class="form-check-label dark" for="darkModeToggle">
                                <i id="moon-icon" class="bi bi-moon-stars-fill" style="display: none;"></i>
                                <i id="sun-icon" class="bi bi-sun-fill"></i>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card stat-card balance">
                        <div class="stat-icon">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <div class="stat-title text-primary">SALDO TOTAL</div>
                        <div class="stat-value" id="totalBalance">Rp {{ number_format(($totalIncome ?? 0) - ($totalExpense ?? 0), 2, ',', '.') }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card income">
                        <div class="stat-icon">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <div class="stat-title text-success">TOTAL PEMASUKAN</div>
                        <div class="stat-value" id="totalIncome">Rp {{ number_format($totalIncome ?? 0, 2, ',', '.') }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card expense">
                        <div class="stat-icon">
                            <i class="bi bi-graph-down-arrow"></i>
                        </div>
                        <div class="stat-title text-danger">TOTAL PENGELUARAN</div>
                        <div class="stat-value" id="totalExpense">Rp {{ number_format($totalExpense ?? 0, 2, ',', '.') }}</div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">Analisis Keuangan Bulanan</h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="position: relative; height:300px; width:100%">
                                <canvas id="monthlyChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-white ">
                            <h5 class="card-title mb-0 text-dark">Distribusi Pengeluaran Bulan Ini</h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="position: relative; height:300px; width:100%">
                                <canvas id="expenseChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row d-none d-md-block">
                <div class="col-12">
                    @include('components.tableTransaction')
                    <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
            </div>
        </div>
    </div>
    @include('components.navigationBar')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Mendefinisikan data chart for file JS eksternal
        window.chartData = {
            monthlyLabels: @json($monthlyChartLabels ?? []),
            incomeData: @json($monthlyChartIncomeData ?? []),
            expenseData: @json($monthlyChartExpenseData ?? []),
            pieLabels: @json($pieChartLabels ?? []),
            pieData: @json($pieChartData ?? [])
        };
    </script>
    <script src="{{ asset('js/main/dashboard.js') }}"></script>
@endsection
