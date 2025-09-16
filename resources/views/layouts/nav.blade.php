<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
        <a class="sidebar-brand brand-logo" href="#"><img src="{{asset('logo.png')}}" alt="logo" width="200" height="200" /></a>
        <a class="sidebar-brand brand-logo-mini" href="#"><img src="{{asset('mini-logo.png')}}" alt="logo mini" width="100" height="200" /></a>
    </div>
    <ul class="nav">
        <li class="nav-item nav-category">
            <span class="nav-link">Welcome!</span>
        </li>
        <li class="nav-item menu-items">
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
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{route('admin.reservation.index')}}">
                <span class="menu-icon">
                    <i class="mdi mdi-account"></i>
                </span>
                <span class="menu-title">Visitor Reservations</span>
            </a>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{route('gallery.index')}}">
                <span class="menu-icon">
                    <i class="mdi mdi-image-multiple"></i>
                </span>
                <span class="menu-title">Gallery</span>
            </a>
        </li>
    </ul>
</nav>
