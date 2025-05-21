<nav class="bottom-nav">
    <a href="{{ url('/savings-goals') }}" class="nav-item {{-- request()->routeIs('savings.index') || request()->is('savings*') ? 'active' : '' --}}">
        <i class="bi bi-wallet-fill"></i>
        <span>Tabungan</span>
    </a>

    <a href="{{ route('transactions.index') }}" class="nav-item {{ request()->routeIs('transactions.index') || request()->is('transactions*') ? 'active' : '' }}">
        <i class="bi bi-cash-coin"></i>
        <span>Transaksi</span>
    </a>

    <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="bi bi-house-door-fill"></i>
        <span>Dasbor</span>
    </a>

    <a href="#" class="nav-item {{-- request()->routeIs('profile.show') || request()->is('profile*') || request()->is('account*') ? 'active' : '' --}}">
        <i class="bi bi-person-fill"></i>
        <span>Account</span>
    </a>

    <a href="{{ url('/') }}" class="nav-item">
        <i class="bi bi-box-arrow-right"></i>
        <span>Keluar</span>
    </a>
</nav>