<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=0.6" />
  
    <title>@yield('title') </title>
    <link rel="icon" type="image/png" href="http://127.0.0.1:8000/assets/images/favicon.png" />
    <link rel="stylesheet" href="{{url('assets/css/panel.template.css')}}" />

</head>

<body>
    <nav>
        <div class="nav--top">
            <a href="{{url('/panel')}}">
                <img src="{{url('assets/images/pages.png')}}" width="30" />
            </a>
            <a href="{{url('/panel/npage')}}">
                <img src="{{url('assets/images/new-page.png')}}" width="30" />
            </a>
        </div>
        <div class="nav--bottom">
            <a href="{{url('/panel/logout')}}">
                <img src="{{url('assets/images/logout.png')}}" width="30" />
            </a>
        </div>
    </nav>

    <section class="container">
        @yield('content')
    </section>
</body>

</html>