@extends('layouts.app')

@section('content')
<style>
    body{
        margin : 0;
    }
</style>
    <div class="auth">
        <div class="container d-flex justify-content-center align-items-center vh-100">
            <div class="login-container">
                <div class="logo">
                    <h1 class="text-primary">Finance Tracker</h1>
                </div>

                <!-- Form Login -->
                <form action="{{ url('login') }}" method="POST" id="loginForm">
                    @csrf
                    @error('email')
                        <div class="text-danger alert alert-danger">{{ $message }}</div>
                    @enderror
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>

                <div class="register-link">
                    <p>Belum punya akun? <a href="{{ url('register') }}">Daftar sekarang</a></p>
                </div>
                <!-- <p class="copy text-center">&copy; 2025 Ichwal.</p> -->
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
