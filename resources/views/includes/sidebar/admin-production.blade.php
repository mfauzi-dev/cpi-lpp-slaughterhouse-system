<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">

        <div class="sidebar-brand mb-2">
            <a href="#">
                <img src="{{ asset('stisla/dist/assets/img/logo-2.jfif') }}" alt="" width="50px" style="mr-2">
                LPP Further
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm mb-2">
            <a href="#">LPP</a>
        </div>


        <ul class="sidebar-menu">

            <li class="{{ Request::is('dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="menu-header">MASTER DATA</li>

            <li class="{{ Request::routeIs('admin-production.product-group.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin-production.product-group.index') }}">
                    <i class="fas fa-layer-group"></i>
                    <span>Product Group</span>
                </a>
            </li>

            <li class="{{ Request::routeIs('admin-production.process-type.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin-production.process-type.index') }}">
                    <i class="fas fa-project-diagram"></i>
                    <span>Process Type</span>
                </a>
            </li>

            <li class="{{ Request::routeIs('admin-production.product.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin-production.product.index') }}">
                    <i class="fas fa-box"></i>
                    <span>Product</span>
                </a>
            </li>
        </ul>
    </aside>
</div>
