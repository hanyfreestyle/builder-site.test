<!DOCTYPE html>
<html lang="{{ thisCurrentLocale() }}" {!!htmlDir()!!} >
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="index, follow">
    <x-web.def.google-tags type="web_master_meta"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {!! SEO::generate() !!}
    <x-web.def.fav-icon/>
    {!! $MinifyTools->setDir('fonts')->MinifyCss('fontawesome/css/all.css',$cssMinifyType,false) !!}
    {!! $MinifyTools->setDir('fonts')->MinifyCss('bootstrap-icons/bootstrap-icons.css',$cssMinifyType,$cssReBuild) !!}

    <!-- Vendor CSS Files -->
    {!! $MinifyTools->setDir('web-def')->MinifyCss('vendor/bootstrap/css/bootstrap.min.css',$cssMinifyType,$cssReBuild) !!}
    {!! $MinifyTools->setDir('web-def')->MinifyCss('vendor/aos/aos.css',$cssMinifyType,$cssReBuild) !!}
    {!! $MinifyTools->setDir('web-def')->MinifyCss('vendor/glightbox/css/glightbox.min.css',$cssMinifyType,$cssReBuild) !!}
    {!! $MinifyTools->setDir('web-def')->MinifyCss('vendor/swiper/swiper-bundle.min.css',$cssMinifyType,$cssReBuild) !!}

    <!-- Main CSS File -->
    {!! $MinifyTools->setDir('web-def')->MinifyCss('css/main.css',$cssMinifyType,$cssReBuild) !!}

    @yield('AddStyle')
    @stack('StyleFile')

    @if(thisCurrentLocale() == 'ar')

    @endif

</head>

<body>


@yield('content')




<!-- Vendor JS Files -->
{!!  $MinifyTools->setDir('web-def')->MinifyJs('vendor/bootstrap/js/bootstrap.bundle.min.js',"Web",false) !!}
{!!  $MinifyTools->setDir('web-def')->MinifyJs('vendor/aos/aos.js',"Web",false) !!}
{!!  $MinifyTools->setDir('web-def')->MinifyJs('vendor/glightbox/js/glightbox.min.js',"Web",false) !!}
{!!  $MinifyTools->setDir('web-def')->MinifyJs('vendor/swiper/swiper-bundle.min.js',"Web",false) !!}
{!!  $MinifyTools->setDir('web-def')->MinifyJs('vendor/purecounter/purecounter_vanilla.js',"Web",false) !!}


<!-- Main JS File -->
{!!  $MinifyTools->setDir('web-def')->MinifyJs('js/main.js',"Web",false) !!}


@yield('TempScript')
@stack('ScriptCode')
<x-web.font.load-web-font/>
</body>
</html>
