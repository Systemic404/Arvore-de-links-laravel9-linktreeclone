<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>4tagme Login </title>
    <link rel="icon" type="image/png" href="http://127.0.0.1:8000/assets/images/favicon.png" />
    <link rel="stylesheet" href="{{url('assets/css/panel.login.css')}}" />
<!-- Global site tag (gtag.js) - Google Analytics -->

</head>

<body>
<div class="loginArea">
    <h1> Login </h1>

    @if ($error)
    <div class="error">{{$error}}  </div>

    @endif

    <form method="POST">
        @csrf

        <input type="email" name="email" placeholder="E-mail" />

        <input type="password" name="password" placeholder="Password" />
        
        <input type="submit" value="LOGIN" />

        Don't have an account? <a href="{{url('panel/register')}}">Register</a>
    </form>
</div>
</body>

</html>