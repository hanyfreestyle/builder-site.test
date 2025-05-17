<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page->seo_title ?? $page->title }} - {{ config('app.name') }}</title>

    <!-- SEO Tags -->
    <meta name="description" content="{{ $page->seo_description ?? '' }}">
    <meta name="keywords" content="{{ $page->seo_keywords ?? '' }}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;700&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS for Template -->
    <style>
        :root {
            --primary-color: {{ $template->primary_color ?? '#3490dc' }};
            --secondary-color: {{ $template->secondary_color ?? '#38c172' }};
            --font-primary: {{ $template->primary_font ?? 'Roboto' }}, sans-serif;
            --font-secondary: {{ $template->secondary_font ?? 'Cairo' }}, sans-serif;
        }

        body {
            font-family: var(--font-primary);
            margin: 0;
            padding: 0;
            color: #333;
        }

        html[dir="rtl"] body {
            font-family: var(--font-secondary);
        }

        .site-header {
            background-color: var(--primary-color);
            color: white;
            padding: 1rem 0;
        }

        .site-footer {
            background-color: #333;
            color: white;
            padding: 2rem 0;
            margin-top: 3rem;
        }

        .main-content {
            min-height: 70vh;
            padding: 2rem 0;
        }

        /* Navigation */
        .main-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .main-nav .navbar-nav {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .main-nav .nav-item {
            margin: 0 1rem;
        }

        .main-nav .nav-link {
            color: white;
            text-decoration: none;
            font-weight: 500;
        }

        .main-nav .nav-link:hover {
            text-decoration: underline;
        }

        /* Language Switcher */
        .language-switcher {
            margin-left: 1rem;
        }

        .language-switcher a {
            color: white;
            text-decoration: none;
            margin: 0 0.5rem;
        }
    </style>
</head>
<body>
    <header class="site-header">
        <div class="container">
            <div class="main-nav">
{{--                <a href="{{ route('home') }}" class="navbar-brand">--}}
{{--                    {{ config('app.name') }}--}}
{{--                </a>--}}

                <nav>
                    <ul class="navbar-nav">
                        @if(isset($headerMenu))
                            @foreach($headerMenu->items as $item)
                                <li class="nav-item">
                                    <a href="{{ $item->getUrl() }}" class="nav-link">{{ $item->title }}</a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </nav>

                <div class="language-switcher">
                    @include('components.language-switcher')
                </div>
            </div>
        </div>
    </header>

    <main class="main-content">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h4>{{ config('app.name') }}</h4>
                    <p>ثيم مميز تم إنشاؤه باستخدام Site Builder</p>
                </div>
                <div class="col-md-6">
                    <h4>روابط سريعة</h4>
                    <ul>
                        @if(isset($footerMenu))
                            @foreach($footerMenu->items as $item)
                                <li>
                                    <a href="{{ $item->getUrl() }}">{{ $item->title }}</a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 text-center">
                    <p>&copy; {{ date('Y') }} {{ config('app.name') }}. كل الحقوق محفوظة.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
