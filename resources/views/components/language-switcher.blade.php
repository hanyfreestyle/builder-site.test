@if(count($languages) > 1)
    @if(count($languages) == 2)
        {{-- إذا كان هناك لغتين فقط، نعرض رابط اللغة الأخرى فقط --}}
        @foreach($languages as $locale => $properties)
            @if(app()->getLocale() != $locale)
                <a href="{{ LaravelLocalization::getLocalizedURL($locale, null, [], true) }}" 
                   class="lang-link">
                    {{ $properties['native'] }}
                </a>
            @endif
        @endforeach
    @else
        {{-- إذا كان هناك أكثر من لغتين، نستخدم قائمة منسدلة --}}
        <div class="lang-dropdown">
            <button class="lang-dropdown-btn" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-globe"></i>
                {{ __('اللغات') }}
            </button>
            <ul class="dropdown-menu" aria-labelledby="languageDropdown">
                @foreach($languages as $locale => $properties)
                    @if(app()->getLocale() != $locale)
                        <li>
                            <a class="dropdown-item" href="{{ LaravelLocalization::getLocalizedURL($locale, null, [], true) }}">
                                {{ $properties['native'] }}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    @endif
@endif

<style>
    /* أنماط اللغة الواحدة */
    .lang-link {
        padding: 5px 10px;
        text-decoration: none;
        color: #333;
        border: 1px solid #ddd;
        border-radius: 4px;
        display: inline-block;
        font-size: 0.9rem;
        transition: all 0.3s;
    }
    
    .lang-link:hover {
        background-color: #f8f9fa;
        color: #007bff;
    }
    
    /* أنماط القائمة المنسدلة */
    .lang-dropdown {
        position: relative;
        display: inline-block;
    }
    
    .lang-dropdown-btn {
        padding: 5px 10px;
        background-color: transparent;
        border: 1px solid #ddd;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.9rem;
    }
    
    .lang-dropdown-btn:hover {
        background-color: #f8f9fa;
    }
    
    /* RTL adjustments */
    html[dir="rtl"] .lang-link, 
    html[dir="rtl"] .lang-dropdown-btn {
        margin-right: 0;
        margin-left: 10px;
    }

    /* تنسيق بوتستراب للقائمة المنسدلة */
    .dropdown-menu {
        min-width: 8rem;
    }
</style>