<footer>
	<div class="container">
		<div class="bg-light rounded-0 py-5 p-sm-5 mx-0">
            <div class="row g-4 align-items-center text-center text-lg-start">
                <div class="col-lg-4">
                    <ul class="nav list-inline text-dark-hover justify-content-center justify-content-lg-start mb-0">
                        <li class="list-inline-item"> <a href="{{ route('front.contact') }}" class="nav-link">Contact</a> </li>
                        <li class="list-inline-item"> <a href="{{ route('front.schedule') }}" class="nav-link">Schedule</a> </li>
                    </ul>
                </div>
                <div class="col-lg-4 text-center">
                    <a href="{{ route('front.index') }}" class="navbar-brand me-0">
                        <img src="{{ asset('assets/images/logo/logo.png') }}" alt="logo" class="light-mode-item navbar-brand-item h-60px">
                        <img src="{{ asset('assets/images/logo/logo-light.png') }}" alt="logo" class="dark-mode-item navbar-brand-item h-60px">
                    </a>
                    <div class="text-dark-hover mt-3">Copyright © Booking <script>document.write(new Date().getFullYear())</script></div>
                </div>
                <div class="col-lg-4">
                    <ul class="nav text-dark-hover hstack gap-2 justify-content-center justify-content-lg-end">
                        <li class="nav-item">
                            <a href="https://www.facebook.com" class="nav-link fs-5"> <i class="fab fa-facebook-f"></i> </a>
                        </li>
                        <li class="nav-item">
                            <a href="https://www.instagram.com" class="nav-link fs-5"> <i class="fab fa-instagram"></i> </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
	</div>
</footer>
