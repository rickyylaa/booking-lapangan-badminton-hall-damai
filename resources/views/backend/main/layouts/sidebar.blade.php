<nav class="navbar sidebar navbar-expand-xl navbar-light">
    <div class="d-flex align-items-center">
        <a class="navbar-brand" href="{{ route('dashboard.index') }}">
            <img src="{{ asset('assets/images/logo/logo.png') }}" alt="logo" class="light-mode-item navbar-brand-item h-40px">
            <img src="{{ asset('assets/images/logo/logo-light.png') }}" alt="logo" class="dark-mode-item navbar-brand-item h-40px">
        </a>
    </div>
    <div class="offcanvas offcanvas-start flex-row custom-scrollbar h-100" data-bs-backdrop="true" tabindex="-1" id="offcanvasSidebar">
        <div class="offcanvas-body sidebar-content d-flex flex-column pt-4">
            <ul class="navbar-nav flex-column" id="navbar-sidebar">
                <li class="nav-item fw-normal small ms-2 my-2">HOME</li>
                <li class="nav-item"> <a href="{{ route('dashboard.index') }}" class="nav-link @yield('active-home-dashboard')">DASHBOARD</a> </li>
                <li class="nav-item fw-normal small ms-2 my-2">PAGES</li>
                <li class="nav-item"> <a href="{{ route('field.index') }}" class="nav-link @yield('active-pages-field')">FIELD</a> </li>
                <li class="nav-item fw-normal small ms-2 my-2">MENU</li>
                <li class="nav-item"> <a href="{{ route('customer.index') }}" class="nav-link @yield('active-menu-customer')">CUSTOMER</a> </li>
                <li class="nav-item"> <a href="{{ route('member.index') }}" class="nav-link @yield('active-menu-member')">MEMBER</a> </li>
                <li class="nav-item fw-normal small ms-2 my-2">MISC</li>
                <li class="nav-item"> <a href="{{ route('transaction.index') }}" class="nav-link @yield('active-misc-transaction')">TRANSACTION</a> </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#reportCollapse" role="button" aria-expanded="@yield('true-misc-report')" aria-controls="reportCollapse">
                        REPORT
                    </a>
                    <ul class="nav collapse flex-column @yield('show-misc-report')" id="reportCollapse" data-bs-parent="#navbar-sidebar">
                        <li class="nav-item"> <a class="nav-link @yield('active-misc-report-transaction')" href="{{ route('report.transaction') }}">TRANSACTION</a> </li>
                    </ul>
                </li>
                <li class="nav-item fw-normal small ms-2 my-2">OTHER</li>
                <li class="nav-item"> <a href="{{ route('banner.index') }}" class="nav-link @yield('active-other-banner')">BANNER</a> </li>
                <li class="nav-item"> <a href="{{ route('message.index') }}" class="nav-link @yield('active-other-message')">MESSAGE</a> </li>
            </ul>
        </div>
    </div>
</nav>
