<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>4tagme - New Page </title>
    <link rel="stylesheet" href="{{url('assets/css/panel.login.css')}}" />
<!-- Global site tag (gtag.js) - Google Analytics -->

</head>

<body>
<div class="loginArea">
    <h1> PAGE CREATION </h1>
    @if ($errors->any())
    <ul>
    @foreach ($errors->all() as $error)
   
        
    @endforeach
    </ul>
    @endif
    @if ($error)
    <div class="error">{{$error}}  </div>

    @endif

    <form method="POST">
        @csrf
        Example: Your page will be 127.0.0.1:8000/PageName
        <input type="text" name="slug" placeholder="Page Name" />
       
        
        <input type="submit" value="Submit Page" />

        <a href="{{url('panel/')}}">Go to panel</a>
    </form>
</div>
</body>
</html>