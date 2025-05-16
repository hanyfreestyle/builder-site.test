<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page->getTranslatedTitle() }} - {{ $template->name }}</title>
    
    <!-- Meta Tags -->
    @foreach($metaTags as $name => $content)
        @if(str_starts_with($name, 'og:'))
            <meta property="{{ $name }}" content="{{ $content }}">
        @else
            <meta name="{{ $name }}" content="{{ $content }}">
        @endif
    @endforeach
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Template Styles -->
    <style>
        :root {
            --primary-color: {{ $template->settings['colors']['primary'] ?? '#007bff' }};
            --secondary-color: {{ $template->settings['colors']['secondary'] ?? '#6c757d' }};
            --accent-color: {{ $template->settings['colors']['accent'] ?? '#fd7e14' }};
            --background-color: {{ $template->settings['colors']['background'] ?? '#ffffff' }};
            --text-color: {{ $template->settings['colors']['text'] ?? '#212529' }};
        }
        
        body {
            font-family: {{ $template->settings['fonts']['primary'] ?? 'Roboto, sans-serif' }};
            background-color: var(--background-color);
            color: var(--text-color);
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: {{ $template->settings['fonts']['heading'] ?? 'Roboto, sans-serif' }};
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-secondary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-accent {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            color: white;
        }
        
        .language-switcher {
            margin-right: 15px;
        }
        
        .language-switcher .dropdown-item.active {
            background-color: var(--primary-color);
            color: white;
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Header -->
    <header class="bg-dark text-white py-3">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <a href="{{ url('/') }}" class="text-decoration-none text-white">
                        <h1 class="mb-0 h4">{{ $template->name }}</h1>
                    </a>
                </div>
                <div class="col-md-8">
                    <div class="d-flex justify-content-end align-items-center">
                        <!-- Language Switcher -->
                        @if(isset($template->supported_languages) && count($template->supported_languages) > 1)
                        <div class="language-switcher dropdown">
                            <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" id="languageSwitcher" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ strtoupper(app()->getLocale()) }}
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="languageSwitcher">
                                @foreach($template->supported_languages as $lang)
                                <li>
                                    <a class="dropdown-item {{ app()->getLocale() == $lang ? 'active' : '' }}" 
                                       href="{{ route('builder.switch-language', ['locale' => $lang, 'redirect' => request()->path()]) }}">
                                        @switch($lang)
                                            @case('ar')
                                                العربية
                                                @break
                                            @case('en')
                                                English
                                                @break
                                            @case('fr')
                                                Français
                                                @break
                                            @case('es')
                                                Español
                                                @break
                                            @case('de')
                                                Deutsch
                                                @break
                                            @default
                                                {{ strtoupper($lang) }}
                                        @endswitch
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        
                        <!-- Navigation -->
                        @if(isset($menus['header']))
                        <nav class="navbar navbar-expand-lg navbar-dark">
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#headerNav" aria-controls="headerNav" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="headerNav">
                                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                                    @foreach($menus['header']->items as $item)
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ $item->getUrl() }}" {!! $item->target_blank ? 'target="_blank"' : '' !!}>
                                            @if($item->icon)
                                            <i class="{{ $item->icon }}"></i>
                                            @endif
                                            {{ $item->getTranslatedTitle() }}
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </nav>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>{{ $template->name }}</h5>
                    <p>{{ $template->description }}</p>
                </div>
                <div class="col-md-6">
                    @if(isset($menus['footer']))
                    <h5>{{ $menus['footer']->name }}</h5>
                    <ul class="list-unstyled">
                        @foreach($menus['footer']->items as $item)
                        <li class="mb-2">
                            <a href="{{ $item->getUrl() }}" class="text-white text-decoration-none" {!! $item->target_blank ? 'target="_blank"' : '' !!}>
                                @if($item->icon)
                                <i class="{{ $item->icon }}"></i>
                                @endif
                                {{ $item->getTranslatedTitle() }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 text-center">
                    <p class="mb-0">&copy; {{ date('Y') }} {{ $template->name }}. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @yield('scripts')
</body>
</html>
