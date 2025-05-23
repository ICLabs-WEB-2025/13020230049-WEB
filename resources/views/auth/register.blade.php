@extends('layouts.app')

@section('content')
    <style>
        body{
            margin : 0;
        }
    </style>
    <div class="auth vh-100 d-flex justify-content-center align-items-center">
        <div class="container">
            <div class="register-container bg-light p-4 rounded shadow">
                <div class="logo text-center">
                    <h1>Finance Tracker</h1>
                    <p class="text-muted">Buat Akun Baru</p>
                </div>

                <!-- Menampilkan pesan flash jika ada -->
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ url('register') }}" method="POST" id="registerForm">
                    @csrf
                    <div class="mb-3">
                        <label for="fullName" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="fullName" name="full_name" value="{{ old('full_name') }}" required>
                        @error('full_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="confirmPassword" name="password_confirmation" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Daftar</button>
                </form>

                <div class="login-link mt-3 text-center">
                    <p>Sudah punya akun? <a href="{{ url('') }}">Login disini</a></p>
                </div>
                <footer class="mt-4">
                    <p class="copy text-center m-0">&copy; 2025 Ichwal.</p>
                    <div class="container d-flex justify-content-center">
                        <a href="https://github.com/ichwalM" target="_blank" class="text-decoration-none text-dark copy text-center m-0 fs-6 my-1">
                            Support by GitHub
                            <i class="bi bi-github text-dark"></i>
                        </a>
                    </div>
                </footer>
            </div>
        </div>
    </div>
@endsection
