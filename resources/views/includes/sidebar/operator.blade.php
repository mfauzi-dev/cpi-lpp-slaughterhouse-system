<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">

        <!-- BRAND -->
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

            <li class="menu-header">Master Data</li>

            <li class="{{ Request::routeIs('operator.material.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('operator.material.index') }}">
                    <i class="bi bi-box-seam"></i>
                    <span>Material</span>
                </a>
            </li>

            <li class="menu-header">Operational</li>

            <li class="{{ Request::routeIs('operator.production-batch.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('operator.production-batch.index') }}">
                    <i class="bi bi-boxes"></i>
                    <span>Production Batch</span>
                </a>
            </li>

            <li class="{{ Request::routeIs('operator.bahan-baku.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('operator.bahan-baku.index') }}">
                    <i class="bi bi-box-seam"></i>
                    <span>Produksi Bahan Baku</span>
                </a>
            </li>

            <li class="{{ Request::routeIs('operator.hasil-produksi.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('operator.hasil-produksi.index') }}">
                    <i class="bi bi-clipboard-data"></i>
                    <span>Data Hasil Produksi</span>
                </a>
            </li>
            <li class="{{ Request::routeIs('operator.rekapitulasi-produksi.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('operator.rekapitulasi-produksi.index') }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Rekapitulasi Produksi & Monitoring Mesin</span>
                </a>
            </li>
            <li class="{{ Request::routeIs('operator.productivity.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('operator.productivity.index') }}">
                    <i class="bi bi-graph-up-arrow"></i>
                    <span>Productivity</span>
                </a>
            </li>
            <li class="{{ Request::routeIs('operator.metal-detector.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('operator.metal-detector.index') }}">
                    <i class="bi bi-shield-check"></i>
                    <span>Metal Detector</span>
                </a>
            </li>
        </ul>
    </aside>
</div>
