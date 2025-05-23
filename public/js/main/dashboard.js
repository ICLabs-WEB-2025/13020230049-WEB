document.addEventListener('DOMContentLoaded', function () {
    // Pastikan window.chartData ada dan berisi data yang diperlukan
    if (typeof window.chartData === 'undefined') {
        console.error('Data untuk chart tidak ditemukan. Pastikan window.chartData sudah didefinisikan di Blade.');
        return;
    }

    // Ambil data dari window.chartData
    const monthlyLabels = window.chartData.monthlyLabels || ['Data Kosong'];
    const incomeData = window.chartData.incomeData || [0];
    const expenseData = window.chartData.expenseData || [0];

    const pieLabels = window.chartData.pieLabels || ['Data Kosong'];
    const pieData = window.chartData.pieData || [0];
    const pieColors = window.chartData.pieColors || [
        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
        '#FF9F40', '#E7E9ED', '#71DD37', '#FFD700', '#008080'
    ];

    // --- Inisialisasi Monthly Line Chart ---
    const ctx1 = document.getElementById('monthlyChart');
    if (ctx1) {
        new Chart(ctx1.getContext('2d'), {
            type: 'line',
            data: {
                labels: monthlyLabels.length > 0 ? monthlyLabels : ['Data Kosong'],
                datasets: [{
                    label: 'Pendapatan',
                    data: incomeData.length > 0 ? incomeData : [0],
                    borderColor: '#4CAF50',
                    backgroundColor: 'rgba(76, 175, 80, 0.1)',
                    borderWidth: 2,
                    fill: true
                }, {
                    label: 'Pengeluaran',
                    data: expenseData.length > 0 ? expenseData : [0],
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
                        beginAtZero: true,
                        ticks: {
                            callback: function(value, index, values) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    } else {
        console.error("Elemen canvas dengan ID 'monthlyChart' tidak ditemukan.");
    }

    // --- Inisialisasi Expense Distribution Pie Chart ---
    const ctx2 = document.getElementById('expenseChart');
    if (ctx2) {
        let displayPieLabels = pieLabels.length > 0 ? pieLabels : ['Tidak Ada Data'];
        let displayPieData = pieData.length > 0 ? pieData : [1];
        let displayPieColors = pieColors;

        if (pieData.length === 0) {
            displayPieColors = ['#E0E0E0'];
        } else {
            displayPieColors = pieColors.slice(0, pieData.length);
        }

        new Chart(ctx2.getContext('2d'), {
            type: 'pie',
            data: {
                labels: displayPieLabels,
                datasets: [{
                    label: 'Distribusi Pengeluaran',
                    data: displayPieData,
                    backgroundColor: displayPieColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                if (context.label === 'Tidak Ada Data' && context.parsed === 1 && pieData.length === 0) {
                                    return 'Tidak ada pengeluaran';
                                }
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                const value = context.parsed;
                                if (value !== null) {
                                    label += 'Rp ' + value.toLocaleString('id-ID');
                                }
                                return label;
                            }
                        }
                    },
                    legend: {
                        position: 'top',
                        display: pieData.length > 0
                    }
                }
            }
        });
    } else {
        console.error("Elemen canvas dengan ID 'expenseChart' tidak ditemukan.");
    }
});
