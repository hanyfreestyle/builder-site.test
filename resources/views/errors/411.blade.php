<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>404</title>

    <style>
        * {
            -webkit-box-sizing: border-box;
            box-sizing: border-box
        }

        body {
            padding: 0;
            margin: 0
        }

        #notfound {
            position: relative;
            height: 100vh
        }

        #notfound .notfound-bg {
            position: absolute;
            width: 100%;
            height: 100%;
            background-image: url(../img/bg.jpg);
            background-size: cover
        }

        #notfound .notfound-bg:after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 0, 36, .7)
        }

        #notfound .notfound {
            position: absolute;
            left: 50%;
            top: 50%;
            -webkit-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%)
        }

        .notfound {
            max-width: 910px;
            width: 100%;
            line-height: 1.4;
            text-align: center
        }

        .notfound .notfound-404 {
            position: relative;
            height: 200px
        }

        .notfound .notfound-404 h1 {
            font-family: montserrat, sans-serif;
            position: absolute;
            left: 50%;
            top: 50%;
            -webkit-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            font-size: 220px;
            font-weight: 900;
            margin: 0;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 10px
        }

        .notfound h2 {
            font-family: montserrat, sans-serif;
            font-size: 22px;
            font-weight: 700;
            text-transform: uppercase;
            color: #fff;
            margin-top: 20px;
            margin-bottom: 15px
        }

        .notfound .home-btn, .notfound .contact-btn {
            font-family: montserrat, sans-serif;
            display: inline-block;
            font-weight: 700;
            text-decoration: none;
            background-color: transparent;
            border: 2px solid transparent;
            text-transform: uppercase;
            padding: 13px 25px;
            font-size: 18px;
            border-radius: 40px;
            margin: 7px;
            -webkit-transition: .2s all;
            transition: .2s all
        }

        .notfound .home-btn:hover, .notfound .contact-btn:hover {
            opacity: .9
        }

        .notfound .home-btn {
            color: rgba(255, 0, 36, .7);
            background: #fff
        }

        .notfound .contact-btn {
            border: 2px solid rgba(255, 255, 255, .9);
            color: rgba(255, 255, 255, .9)
        }

        .notfound-social {
            margin-top: 25px
        }

        .notfound-social > a {
            display: inline-block;
            height: 40px;
            line-height: 40px;
            width: 40px;
            font-size: 14px;
            color: rgba(255, 255, 255, .9);
            margin: 0 6px;
            -webkit-transition: .2s all;
            transition: .2s all
        }

        .notfound-social > a:hover {
            color: rgba(255, 0, 36, .7);
            background-color: #fff;
            border-radius: 50%
        }

        @media only screen and (max-width: 767px) {
            .notfound .notfound-404 h1 {
                font-size: 182px
            }
        }

        @media only screen and (max-width: 480px) {
            .notfound .notfound-404 {
                height: 146px
            }

            .notfound .notfound-404 h1 {
                font-size: 146px
            }

            .notfound h2 {
                font-size: 16px
            }

            .notfound .home-btn, .notfound .contact-btn {
                font-size: 14px
            }
        }
    </style>
</head>
<body>

<div id="notfound">
    <div class="notfound-bg"></div>
    <div class="notfound">
        <div class="notfound-404">
            <h1>404</h1>
        </div>
        <h2>File Not Found </h2>
        @if(session('message'))
            <h1>{{ session('message') }}</h1>
        @endif
    </div>
</div>
</body>

</html>
