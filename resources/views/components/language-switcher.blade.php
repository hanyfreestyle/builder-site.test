@if(count($languages) > 1)
<div class="language-switcher">
    @foreach($languages as $locale => $properties)
        <a href="{{ LaravelLocalization::getLocalizedURL($locale, null, [], true) }}" 
           class="lang-link {{ app()->getLocale() == $locale ? 'active' : '' }}">
            {{ $properties['native'] }}
        </a>
        @if(!$loop->last)
            <span class="lang-separator">|</span>
        @endif
    @endforeach
</div>
@endif

<style>
    .language-switcher {
        display: inline-block;
        margin-left: 15px;
    }
    .lang-link {
        padding: 5px;
        text-decoration: none;
    }
    .lang-link.active {
        font-weight: bold;
        color: #007bff;
    }
    .lang-separator {
        margin: 0 5px;
    }

    /* RTL adjustments */
    html[lang="ar"] .language-switcher {
        margin-right: 15px;
        margin-left: 0;
    }
</style>