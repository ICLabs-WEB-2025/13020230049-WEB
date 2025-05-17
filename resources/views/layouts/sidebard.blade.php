<!-- Sidebar Container -->
<div class="sidebar d-flex flex-column justify-content-between bg-dark text-white" id="sidebar">
    <ul class="sidebar-menu list-unstyled">
        <div class="sidebar-brand text-center py-4">
            <h2>Finance Tracker</h2>
        </div>

        <li>
            <a href="{{ url('/dashboard') }}" class="d-flex align-items-center p-3 text-light">
                <i class="bi bi-speedometer2 me-3"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li>
            <a href="{{ url('/transactions') }}" class="d-flex align-items-center p-3 text-light">
                <i class="bi bi-cash-coin me-3"></i>
                <span>Transaksi</span>
            </a>
        </li>

        <li>
            <a href="{{ url('/') }}" class="d-flex align-items-center p-3 text-light">
                <i class="bi bi-box-arrow-right me-3"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>

    <footer class="mt-4">
        <p class="copy text-center m-0">&copy; 2025 Ichwal.</p>
        <div class="container d-flex justify-content-center">
            <a href="https://github.com/ichwalM" target="_blank" class="btn fs-5">
                <i class="bi bi-github"></i>
            </a>
            <a href="https://www.linkedin.com/in/ichwal/" class="btn fs-5" target="_blank">
                <i class="bi bi-linkedin"></i>
            </a>
        </div>
    </footer>
</div>
