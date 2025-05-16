<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
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
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('templates/' . $template->slug . '/css/style.css') }}" rel="stylesheet">
    
    <!-- Template Settings as CSS Variables -->
    <style>
        :root {
            @if(isset($template->settings['colors']))
                @foreach($template->settings['colors'] as $name => $value)
                    --color-{{ $name }}: {{ $value }};
                @endforeach
            @endif
            
            @if(isset($template->settings['fonts']))
                @foreach($template->settings['fonts'] as $name => $value)
                    --font-{{ $name }}: {{ $value }};
                @endforeach
            @endif
            
            @if(isset($template->settings['spacing']))
                @foreach($template->settings['spacing'] as $name => $value)
                    --spacing-{{ $name }}: {{ $value }};
                @endforeach
            @endif
        }
    </style>
</head>
<body class="template-{{ $template->slug }}">
    <header>
        <!-- Main Navigation -->
        @if(isset($menus['header']))
            <nav class="navbar navbar-expand-lg">
                <div class="container">
                    <a class="navbar-brand" href="{{ route('builder.home') }}">
                        {{ $template->name }}
                    </a>
                    
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    
                    <div class="collapse navbar-collapse" id="navbarMain">
                        <ul class="navbar-nav ms-auto">
                            @foreach($menus['header']->items as $menuItem)
                                <li class="nav-item @if($menuItem->children->count() > 0) dropdown @endif">
                                    <a 
                                        class="nav-link @if($menuItem->children->count() > 0) dropdown-toggle @endif" 
                                        href="{{ $menuItem->getUrl() }}"
                                        @if($menuItem->children->count() > 0) 
                                            role="button" 
                                            data-bs-toggle="dropdown" 
                                            aria-expanded="false"
                                        @endif
                                        @if($menuItem->target_blank) 
                                            target="_blank" 
                                        @endif
                                    >
                                        @if($menuItem->icon)
                                            <i class="{{ $menuItem->icon }}"></i>
                                        @endif
                                        {{ $menuItem->getTranslatedTitle() }}
                                    </a>
                                    
                                    @if($menuItem->children->count() > 0)
                                        <ul class="dropdown-menu">
                                            @foreach($menuItem->children as $childItem)
                                                <li>
                                                    <a 
                                                        class="dropdown-item" 
                                                        href="{{ $childItem->getUrl() }}"
                                                        @if($childItem->target_blank) 
                                                            target="_blank" 
                                                        @endif
                                                    >
                                                        @if($childItem->icon)
                                                            <i class="{{ $childItem->icon }}"></i>
                                                        @endif
                                                        {{ $childItem->getTranslatedTitle() }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                            
                            <!-- Language Switcher -->
                            @if(isset($languages) && count($languages) > 1)
                                <li class="nav-item">
                                    @include('components.language-switcher', ['languages' => $languages])
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </nav>
        @endif
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <!-- Footer Navigation -->
        @if(isset($menus['footer']))
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <h5>{{ $template->name }}</h5>
                        <p>{{ $template->description }}</p>
                    </div>
                    
                    <div class="col-md-8">
                        <div class="row">
                            @foreach($menus['footer']->items as $menuItem)
                                <div class="col-sm-4">
                                    <h6>
                                        @if($menuItem->icon)
                                            <i class="{{ $menuItem->icon }}"></i>
                                        @endif
                                        {{ $menuItem->getTranslatedTitle() }}
                                    </h6>
                                    
                                    @if($menuItem->children->count() > 0)
                                        <ul class="list-unstyled">
                                            @foreach($menuItem->children as $childItem)
                                                <li>
                                                    <a 
                                                        href="{{ $childItem->getUrl() }}"
                                                        @if($childItem->target_blank) 
                                                            target="_blank" 
                                                        @endif
                                                    >
                                                        @if($childItem->icon)
                                                            <i class="{{ $childItem->icon }}"></i>
                                                        @endif
                                                        {{ $childItem->getTranslatedTitle() }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-12 text-center">
                        <p>&copy; {{ date('Y') }} {{ $template->name }}. All rights reserved.</p>
                    </div>
                </div>
            </div>
        @endif
    </footer>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('templates/' . $template->slug . '/js/script.js') }}"></script>
</body>
</html>