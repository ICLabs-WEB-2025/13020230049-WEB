<!-- Sidebar Container -->
<div class="sidebar d-flex flex-column justify-content-between bg-dark text-white" id="sidebar">
    <ul class="sidebar-menu list-unstyled">
        <div class="sidebar-brand text-center py-4">
            <h2 class="text-light">Finance Tracker</h2>
        </div>

        <li>
            <a href="{{ url('/') }}" class="d-flex align-items-center p-3 text-light">
                <i class="bi bi-house-door-fill me-3"></i>
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
            <a href="{{ url('/savings-goals') }}" class="d-flex align-items-center p-3 text-light">
                <i class="bi bi-wallet-fill me-3"></i>
                <span>Tabungan</span>
            </a>
        </li>

        <li>    
            <a href="{{ route('profile.show') }}" class="d-flex align-items-center p-3 text-light">
                <i class="bi bi-person-fill me-3"></i>
                <span>Profile</span>
            </a>
        </li>

        <li>
            <a href="{{ url('/') }}" class="d-flex align-items-center p-3 text-light"
                onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                <i class="bi bi-box-arrow-right me-3"></i>
                <span>Logout</span>
            </a>
            <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>

    <footer class="mt-4">
        <p class="copy text-center m-0">&copy; 2025 Ichwal.</p>
        <div class="container d-flex justify-content-center">
            <a href="https://github.com/ichwalM" target="_blank" class="text-decoration-none text-light copy text-center m-0 fs-6 my-1">
                Support by GitHub
                <i class="bi bi-github text-light"></i>
            </a>
        </div>
    </footer>
</div>
