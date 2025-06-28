    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Riwayat Transaksi</h5>
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
                            @if(request()->routeIs('transactions.index'))
                            <th scope="col">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody id="transactionsTable">
                        @foreach($transactions as $transaction)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($transaction->date)->format('d-m-Y') }}</td>
                                <td>{{ $transaction->category->category_name }}</td>
                                <td>{{ $transaction->description ?? '-' }}</td>
                                <td class="{{ $transaction->transaction_type === 'income' ? 'income-text' : 'expense-text' }}" >
                                    {{ $transaction->transaction_type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                                </td>
                                <td>Rp {{ number_format($transaction->amount, 2) }}</td>
                                <td>
                                    <div class="container-fluid d-flex align-items-center">
                                        @if(request()->routeIs('transactions.index'))
                                        <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" class="d-flex gap-1">
                                            @csrf
                                            <button type="button" class="btn btn-sm btn-warning btn-edit"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editTransaksiModal"
                                                    data-id="{{ $transaction->id }}"
                                                    data-amount="{{ $transaction->amount }}"
                                                    data-category_id="{{ $transaction->category_id }}"  
                                                    data-transaction_type="{{ $transaction->transaction_type }}"
                                                    data-date="{{ $transaction->date }}"
                                                    data-description="{{ $transaction->description }}">
                                                Edit
                                            </button>
                                            @method('DELETE')
                                            @csrf
                                            <button id="btn-delete" type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    @if(request()->routeIs('transactions.index'))
                    <tfoot>
                        <tr>
                            <td class="income-text">Total Pemasukan</td>
                            <td>:</td>
                            <td class="income-text">Rp {{ number_format($totalIncome ?? 0, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="expense-text">Total Pengeluaran</td>
                            <td>:</td>
                            <td class="expense-text">Rp {{ number_format($totalExpense ?? 0, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="text-primary">Total Saldo</td>
                            <td>:</td>
                            <td class="{{number_format(($totalIncome ?? 0) - ($totalExpense ?? 0), 2, ',', '.') > 0 ? 'income-text' : 'expense-text' }}">
                                Rp {{ number_format(($totalIncome ?? 0) - ($totalExpense ?? 0), 2, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>