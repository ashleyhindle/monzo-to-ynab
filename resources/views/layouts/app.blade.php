<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Monzo to YNAB - @yield('title')</title>

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#ffc40d">
    <meta name="msapplication-TileColor" content="#ffc40d">
    <meta name="theme-color" content="#ffffff">

    <meta property="og:image:height" content="1257">
    <meta property="og:image:width" content="2400">
    <meta property="og:description" content="Automatically send Monzo transactions into YNAB for easier budgeting">
    <meta property="og:title" content="Monzo to YNAB Syncing">
    <meta property="og:url" content="https://monzo-to-ynab.ashleyhindle.com">
    <meta property="og:image" content="https://monzo-to-ynab.ashleyhindle.com/og-image.jpg">

    <link href="/css/app.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container">
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #c40295;">
        <a class="navbar-brand" href="/">Monzo To YNAB</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarColor02">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/">Home</a>
                </li>
                <li class="nav-item @if (ends_with(url()->current(), "/about")) active @endif">
                    <a class="nav-link" href="/about">About</a>
                </li>
                <li class="nav-item @if (ends_with(url()->current(), "/privacy")) active @endif">
                    <a class="nav-link" href="/privacy">Privacy</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://github.com/ashleyhindle/monzo-to-ynab">GitHub</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="content pt-3">
        @include('flash::message')
        @yield('content')
    </div>

    <footer class="pt-2 my-md-4 pt-md-4 border-top">
        <div class="row">
            <div class="col-12 col-md">
                <img class="mb-1" src="/monzo-to-ynab.png" alt="Monzo to YNAB logo" width="240">
                <small class="d-block mb-3 text-muted">&copy; {{ date('Y') }}</small>
            </div>
            <div class="col-6 col-md">
                <h5>Resources</h5>
                <ul class="list-unstyled text-small">
                    <li><a class="text-muted" href="https://monzo.com">Monzo</a></li>
                    <li><a class="text-muted" href="https://www.youneedabudget.com">YNAB</a></li>
                    <li><a class="text-muted" href="https://github.com/ashleyhindle/monzo-to-ynab">GitHub</a></li>
                </ul>
            </div>
            <div class="col-6 col-md">
                <h5>About</h5>
                <ul class="list-unstyled text-small">
                    <li><a class="text-muted" href="mailto:monzotoynab@ashleyhindle.com">Contact</a></li>
                    <li><a class="text-muted" href="/privacy">Privacy</a></li>
                    <li><a class="text-muted" href="/about">About</a></li>
                </ul>
            </div>
        </div>
    </footer>
</div>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-65325064-6"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-65325064-6');
</script>
</body>
</html>
