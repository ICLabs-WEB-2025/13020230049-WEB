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
                    <h1>Finance Tracker</h1>
                </div>

                <!-- Form Login -->
                <form action="{{ url('login') }}" method="POST" id="loginForm">
                    @csrf <!-- CSRF Token -->
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
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="rememberMe" name="remember">
                        <label class="form-check-label" for="rememberMe">Ingat Saya</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>

                <div class="register-link">
                    <p>Belum punya akun? <a href="{{ url('register') }}">Daftar sekarang</a></p>
                </div>
                <p class="copy text-center">&copy; 2025 Ichwal.</p>
            </div>
        </div>
    </div>
@endsection
