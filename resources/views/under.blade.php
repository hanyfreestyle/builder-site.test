<!DOCTYPE html>
<html lang="{{ thisCurrentLocale() }}" {!!htmlDir()!!} >
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name='robots' content='noindex,nofollow'/>
    {!! SEO::generate() !!}
    <x-web.def.fav-icon/>
    <x-web.font.load-google-font :fonts-to-load="['Alexandria', 'Zain']"/>
    <style>
        .comming-soon {
            background: url("{{ app('url')->asset('assets/under/css/bg.jpg',$httpsSecure) }}") no-repeat bottom center;
        }
    </style>
{!! $minifyTools->setWebAssets('assets/under/')->MinifyCss('css/bootstrap.min.css',$cssMinifyType,$cssReBuild) !!}
{!! $minifyTools->setWebAssets('assets/under/')->MinifyCss('css/custom.css',$cssMinifyType,$cssReBuild) !!}
<body>
<div class="comming-soon">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="coming-soon-box">
                    <div class="logo">
                        {{--                        <img src="{{getDefPhotoPath($DefPhotoList,'logo_dark')}}" alt="{{$WebConfig->name}}">--}}
                    </div>
                    <div class="coming-text">
                        <h2>{{$webConfig->name}}</h2>
                        <div class="typing-titleX">
                            <p>{!! nl2br($webConfig->closed_mass) !!}</p>
                        </div>
                    </div>
                    <div class="countdown-timer-wrapper">
                        <div class="timer" id="countdown"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ app('url')->asset('assets/under/js/jquery-1.12.4.min.js',$httpsSecure) }}"></script>
<script src="{{ app('url')->asset('assets/under/js/countdown-timer.js',$httpsSecure) }}"></script>
<script src="{{ app('url')->asset('assets/under/js/SmoothScroll.js',$httpsSecure) }}"></script>
<script src="{{ app('url')->asset('assets/under/js/bootstrap.min.js',$httpsSecure) }}"></script>
<script src="{{ app('url')->asset('assets/under/js/function.js',$httpsSecure) }}"></script>
<script>
    $(document).ready(function () {
        var myDate = new Date("{{$formattedDate}}");
        $("#countdown").countdown(myDate, function (event) {
            $(this).html(
                event.strftime(
                    '<div class="timer-wrapper">' +
                    '<div class="time">%D</div><span class="text">{{__('default/under-construction.days')}}</span></div>' +
                    '<div class="timer-wrapper"><div class="time">%H</div><span class="text">{{__('default/under-construction.hours')}}</span></div>' +
                    '<div class="timer-wrapper"><div class="time">%M</div><span class="text">{{__('default/under-construction.minutes')}}</span></div>' +
                    '<div class="timer-wrapper"><div class="time">%S</div><span class="text">{{__('default/under-construction.seconds')}}</span></div>'
                )
            );
        });
    });
</script>
</body>
</html>
