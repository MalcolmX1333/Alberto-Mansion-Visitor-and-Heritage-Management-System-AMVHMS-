<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="#" class="app-brand-link">
            <img src="{{ asset('BCHATO.webp') }}" style="height: 60px" alt="Alberto Mansion Museum Logo" class="app-brand-logo">
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Welcome!</span></li>
    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item {{ request()->routeIs('home') ? ' active' : '' }}">
            <a href="{{route('home')}}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>

        <!-- Interface Section -->
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Interface</span></li>

        <!-- Visitor Information -->
    <li class="menu-item {{ request()->routeIs('admin.reservation.index') ? ' active' : '' }}">
        <a href="{{route('admin.reservation.index')}}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-user"></i>
            <div data-i18n="Visitor Information">Visitor Reservations</div>
        </a>
    </li>

        <!-- Reports -->
        <li class="menu-item {{ request()->routeIs('gallery.index') ? ' active' : '' }}">
            <a href="{{route('gallery.index')}}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-image-add"></i>
                <div data-i18n="Reports">Gallery</div>
            </a>
        </li>

        <!-- Interface Section -->
{{--        <li class="menu-header small text-uppercase"><span class="menu-header-text">Landing Page</span></li>--}}

{{--        <!-- Events -->--}}
{{--        <li class="menu-item">--}}
{{--            <a href="#" class="menu-link">--}}
{{--                <i class="menu-icon tf-icons bx bx-calendar"></i>--}}
{{--                <div data-i18n="Events">Events</div>--}}
{{--            </a>--}}
{{--        </li>--}}

{{--        <li class="menu-item">--}}
{{--            <a href="#" class="menu-link">--}}
{{--                <i class="menu-icon tf-icons bx bx-images"></i>--}}
{{--                <div data-i18n="Gallery">Gallery</div>--}}
{{--            </a>--}}
{{--        </li>--}}

        <!-- Sidebar Toggler -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
    </ul>
</aside>
