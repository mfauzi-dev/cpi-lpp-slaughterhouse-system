<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand mb-2">
            <a href="#">
                <img src="{{ asset('stisla/dist/assets/img/logo-2.jfif') }}" alt="" width="50px"
                    style="margin-right: 2px;">
                LPP SH
            </a>
        </div>

        <div class="sidebar-brand sidebar-brand-sm mb-2">
            <a href="#">LPP</a>
        </div>

        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>

            <li class="{{ Request::is('dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="bi bi-house-door"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="menu-header">Management</li>

            <li class="{{ Request::routeIs('manager.user.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('manager.user.index') }}">
                    <i class="fas fa-users"></i>
                    <span>User</span>
                </a>
            </li>

            <li class="menu-header">Operational</li>

            <li class="{{ Request::routeIs('manager.production-batch.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('manager.production-batch.index') }}">
                    <i class="bi bi-boxes"></i>
                    <span>Production Batch</span>
                </a>
            </li>

            <li class="{{ Request::routeIs('manager.bahan-baku.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('manager.bahan-baku.index') }}">
                    <i class="bi bi-box-seam"></i>
                    <span>Produksi Bahan Baku</span>
                </a>
            </li>

            <li class="{{ Request::routeIs('manager.hasil-produksi.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('manager.hasil-produksi.index') }}">
                    <i class="bi bi-clipboard-data"></i>
                    <span>Data Hasil Produksi</span>
                </a>
            </li>

            <li class="{{ Request::routeIs('manager.rekapitulasi-produksi.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('manager.rekapitulasi-produksi.index') }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Rekapitulasi Produksi & Monitoring Mesin</span>
                </a>
            </li>

            <li class="{{ Request::routeIs('manager.productivity.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('manager.productivity.index') }}">
                    <i class="bi bi-graph-up-arrow"></i>
                    <span>Productivity</span>
                </a>
            </li>

            <li class="{{ Request::routeIs('manager.metal-detector.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('manager.metal-detector.index') }}">
                    <i class="bi bi-shield-check"></i>
                    <span>Metal Detector</span>
                </a>
            </li>
        </ul>
    </aside>
</div>
