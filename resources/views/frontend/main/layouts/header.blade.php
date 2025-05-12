<header class="navbar-light header-sticky">
	<nav class="navbar navbar-expand-xl">
        <div class="container">
			<a href="{{ route('front.index') }}" class="navbar-brand">
				<img src="{{ asset('assets/images/logo/logo.png') }}" alt="logo" class="light-mode-item navbar-brand-item">
				<img src="{{ asset('assets/images/logo/logo-light.png') }}" alt="logo" class="dark-mode-item navbar-brand-item">
			</a>
            <button type="button" class="navbar-toggler ms-auto me-3 p-0" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-animation">
					<span></span>
					<span></span>
					<span></span>
				</span>
			</button>
            <div class="navbar-collapse collapse" id="navbarCollapse">
				<ul class="navbar-nav navbar-nav-scroll ms-auto">
					<li class="nav-item"> <a href="{{ route('front.index') }}" class="nav-link">Home</a> </li>
					<li class="nav-item"> <a href="{{ route('front.schedule') }}" class="nav-link">Schedule</a> </li>
					<li class="nav-item"> <a href="{{ route('front.contact') }}" class="nav-link">Contact</a> </li>
				</ul>
			</div>
			@if (auth()->guard('customer')->check())
                <ul class="nav flex-row ms-xl-auto">
                    <li class="nav-item ms-3 dropdown">
                        <a href="#" class="avatar avatar-sm p-0" id="profileDropdown" role="button" data-bs-auto-close="outside" data-bs-display="static" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ asset('storage/customers/'. auth()->guard('customer')->user()->image) }}" alt="avatar" class="avatar-img rounded-0">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-animation rounded-0 pt-3" aria-labelledby="profileDropdown">
                            <li class="px-1 ps-3 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-2">
                                        <img src="{{ asset('storage/customers/'. auth()->guard('customer')->user()->image) }}" alt="avatar" class="avatar-img rounded-0">
                                    </div>
                                    <div>
                                        <span class="fw-normal h6 mt-2 mt-sm-0">{{ ucwords(auth()->guard('customer')->user()->name) }}</span>
                                        <p class="small m-0">{!! auth()->guard('customer')->user()->role_non_label !!}</p>
                                    </div>
                                </div>
                            </li>
                            <li> <hr class="dropdown-divider"> </li>
                            <li> <a class="dropdown-item" href="{{ route('customer.dashboard') }}"><i class="fa-sharp fa-solid fa-user me-2"></i>Dashboard</a> </li>
                            <li>
                                <a href="{{ route('customer.logout') }}" class="dropdown-item bg-danger-soft-hover">
                                    <i class="fa-sharp fa-solid fa-right-from-bracket me-2"></i>Sign Out
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            @else
                <ul class="nav flex-row ms-xl-auto">
                    <li class="nav-item ms-2 d-sm-block">
                        <a href="#" class="btn btn-sm btn-dark rounded-0 mb-0" data-bs-toggle="modal" data-bs-target="#loginModal">Sign in/up</a>
                    </li>
                </ul>
            @endif
		</div>
	</nav>
</header>
