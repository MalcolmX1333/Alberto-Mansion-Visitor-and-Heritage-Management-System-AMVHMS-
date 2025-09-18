<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
        <a class="sidebar-brand brand-logo d-flex align-items-center" href="{{route('home')}}">
            <img src="{{asset('binanlogo.png')}}" alt="logo" width="36" height="36" style="object-fit: contain;" />
            <span class="ml-2 font-weight-bold" style="font-size: 1.5rem; color: #fff;">AMVHMS</span>
        </a>
        <a class="sidebar-brand brand-logo-mini" href="{{route('home')}}">
            <img src="{{asset('binanlogo.png')}}" alt="logo mini" width="32" height="32" style="object-fit: contain;" />
        </a>
    </div>
    <ul class="nav">
        <li class="nav-item nav-category">
            <span class="nav-link">Welcome!</span>
        </li>
        <li class="nav-item menu-items {{ request()->routeIs('home') ? 'active' : '' }}">
            <a class="nav-link" href="{{route('home')}}">
                <span class="menu-icon">
                    <i class="mdi mdi-view-dashboard-outline"></i>
                </span>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item nav-category">
            <span class="nav-link">Interface</span>
        </li>
        <li class="nav-item menu-items {{ request()->routeIs('admin.reservation.index') ? 'active' : '' }}">
            <a class="nav-link" href="{{route('admin.reservation.index')}}">
                <span class="menu-icon">
                    <i class="mdi mdi-account"></i>
                </span>
                <span class="menu-title">Visitor Reservations</span>
            </a>
        </li>
        <li class="nav-item menu-items {{ request()->routeIs('gallery.index') ? 'active' : '' }}">
            <a class="nav-link" href="{{route('gallery.index')}}">
                <span class="menu-icon">
                    <i class="mdi mdi-image-multiple"></i>
                </span>
                <span class="menu-title">Gallery</span>
            </a>
        </li>
        <li class="nav-item menu-items {{ request()->routeIs('event.index') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('event.index') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-calendar"></i>
                </span>
                <span class="menu-title">Events</span>
            </a>
        </li>
    </ul>
</nav>
