<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title')</title>

    <link rel="shortcut icon" href="{{ asset('assets/images/favicon/icon.png') }}" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Poppins:wght@400;500;700&display=swap">

    <link rel="stylesheet" href="{{ asset('assets/vendor/font-awesome/css/fontawesome.min.css') }}" type="text/css">
	<link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-icons/css/bootstrapicons.min.css') }}" type="text/css">
	<link rel="stylesheet" href="{{ asset('assets/vendor/overlay-scrollbar/css/overlayscrollbars.min.css') }}" type="text/css">
    @yield('css')

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" type="text/css">
</head>
<body>
    <main>
        @include('backend.main.layouts.sidebar')
		<div class="page-content">
            @include('backend.main.layouts.header')
            <div class="page-content-wrapper px-xxl-2 pe-xxl-2 p-xxl-4">
                @yield('content')
            </div>
        </div>
    </main>

    @yield('modal')

	<div class="back-top"></div>
    @include('sweetalert::alert')

    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/overlay-scrollbar/js/overlayscrollbars.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    @yield('js')

    <script src="{{ asset('assets/js/functions.js') }}"></script>

    @yield('script')

    <script>
        function markAsReadAndNavigate(notificationId) {
            console.log('Notification ID:', notificationId);

            $.ajax({
                type: 'POST',
                url: '{{ route("mark-as-read") }}',
                data: { id: notificationId },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Notification marked as read:', response);
                    window.location.href = '{{ route("transaction.index") }}';
                },
                error: function(error) {
                    console.error('Error marking notification as read:', error);
                }
            });
        }

        function markAllAsRead() {
            console.log('Marking all notifications as read');

            $.ajax({
                type: 'POST',
                url: '{{ route("mark-all-as-read") }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('All notifications marked as read:', response);
                    location.reload();
                },
                error: function(error) {
                    console.error('Error marking all notifications as read:', error);
                }
            });
        }
    </script>

    <script>
    const storedTheme = localStorage.getItem('theme')

    const getPreferredTheme = () => {
        if (storedTheme) {
            return storedTheme
        }
        return window.matchMedia('(prefers-color-scheme: light)').matches ? 'dark' : 'light'
    }

    const setTheme = function (theme) {
        if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: light)').matches) {
            document.documentElement.setAttribute('data-bs-theme', 'light')
        } else {
            document.documentElement.setAttribute('data-bs-theme', theme)
        }
    }

    setTheme(getPreferredTheme())

    window.addEventListener('DOMContentLoaded', () => {
        var el = document.querySelector('.theme-icon-active');
        if(el != 'undefined' && el != null) {
            const showActiveTheme = theme => {
            const activeThemeIcon = document.querySelector('.theme-icon-active use')
            const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`)
            const svgOfActiveBtn = btnToActive.querySelector('.mode-switch use').getAttribute('href')

            document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
                element.classList.remove('active')
            })

            btnToActive.classList.add('active')
            activeThemeIcon.setAttribute('href', svgOfActiveBtn)
        }

        window.matchMedia('(prefers-color-scheme: light)').addEventListener('change', () => {
            if (storedTheme !== 'light' || storedTheme !== 'dark') {
                setTheme(getPreferredTheme())
            }
        })

        showActiveTheme(getPreferredTheme())

        document.querySelectorAll('[data-bs-theme-value]')
            .forEach(toggle => {
                toggle.addEventListener('click', () => {
                    const theme = toggle.getAttribute('data-bs-theme-value')
                    localStorage.setItem('theme', theme)
                    setTheme(theme)
                    showActiveTheme(theme)
                })
            })

        }
    })
	</script>
</body>
</html>
