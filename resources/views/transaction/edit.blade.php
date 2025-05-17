@extends('layouts.app')

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="mb-1">Edit Transaksi</h2>
                    <p class="text-muted">Ubah detail transaksi Anda</p>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">Edit Transaksi</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('transactions.update', $transaction->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="amount" class="form-label">Jumlah (Rp)</label>
                                    <input type="number" class="form-control" id="amount" name="amount" value="{{ old('amount', $transaction->amount) }}" required min="1">
                                </div>

                                <div class="mb-3">
                                    <label for="category" class="form-label">Kategori</label>
                                    <select class="form-select" id="category" name="category_id" required>
                                        <option value="" disabled selected>Pilih Kategori</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $transaction->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->category_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="date" class="form-label">Tanggal</label>
                                    <input type="date" class="form-control" id="date" name="date"
                                        value="{{ old('date', $transaction->date ? \Carbon\Carbon::parse($transaction->date)->format('Y-m-d') : '') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Deskripsi</label>
                                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $transaction->description) }}</textarea>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">Perbarui Transaksi</button>
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Jumlah (Rp)</label>
                                    <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount', $transaction->amount) }}" required min="1">
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
