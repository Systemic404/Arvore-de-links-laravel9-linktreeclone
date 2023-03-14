<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<title> {{$title}}</title>
<link rel="icon" type="image/png" href="http://127.0.0.1:8000/assets/images/favicon.png" />


<style type="text/css">
body{
     display: flex;
     flex-direction: column;
     align-items: center;
     margin 0;
     padding: 20px;
     font-family: Helvetica, Arial;
     color: {{$font_color}};
     background: {{$bg}};
     background-image:url({{$op_background_image}});

}
.profileImage img {
         width: auto;
         height: 100px;
         border-radius: 10px 10px;
     }
.LinkImg img{
    margin: 0 auto;
         height: 100px;
         border-radius: 10px 10px;
         display: flex;
  justify-content: center;

}
.logo img{
    margin: 0 auto;
    width: 131px;
         height: 43px;
             display: flex;
  justify-content: center;   
         

}
.profileTitle{
    font-size: 25px;
    font-weight: bold;
    maring-top: 10px;
}
.profileDescription{
    font-size: 15px;
    margin-top: 5px;
}
.linkArea{

    width: 100%;
    margin: 50px 0;   
}
.linkArea a {
    display: block;
    padding: 20px;
    text-align: center;
    text-decoration: none;
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 20px;
}
.linkArea a.linksquare{
    border-radius: 0px;

}
.linkArea a.linkrounded{
    border-radius: 50px;

}

.banner a{
    color: {{$font_color}}
}
audio{
         max-height: 100%;
         max-width: 100%;
         margin: auto;
         object-fit: contain;
         }

li.page-item {

    display: none;
}

.page-item:first-child,
.page-item:nth-child( 2 ),
.page-item:nth-child( 3 ),



.page-item:nth-last-child( 2 ),
.page-item:last-child,
.page-item.active,
.page-item.disabled {

    display: block;
}
}
</style>
</head>
<body>
<div class="profileImage">
        <img src=" {{ $profile_image }}" />


</div>
<div  class="profileTitle"> {{ $title }}</div>
<div  class="profileDescription"> {{ $description }}</div>

<div  class="linkArea">
@foreach ($links as $link)
<a

 href="{{$link->href}}"
 class="link{{$link->op_border_type}}"
 style="background-color:{{$link->op_bg_color}};color:{{$link->op_text_color}};"
 taget="_blank"
>{{$link->title}}</a>
<div class="LinkImg">
@if(!empty($link->op_link_image))

<a  class="link{{$link->op_border_type}}"style="background-color:{{$link->op_bg_color}}"; href="{{$link->href}}"><img src="{{url('media/uploads/', $link->op_link_image)}}" alt="img" ></a>

@endif
@if(!empty($link->op_video))

<a  class="link{{$link->op_border_type}}"style="background-color:{{$link->op_bg_color}}"; href="{{$link->href}}"><video width="100%" height="240" controls> <source src="{{url('media/uploads/', $link->op_video)}}" alt="VIDEO" ></a>

@endif
@if(!empty($link->op_audio))

<a  class="link{{$link->op_border_type}}"style="background-color:{{$link->op_bg_color}}"; href="{{$link->href}}"><audio  width="100%" height="240" controls> <source src="{{url('media/uploads/', $link->op_audio)}}" type="audio/mpeg" alt="AUDIO" ></a>

@endif
@if(!empty($link->op_post_image))


<a  class="link{{$link->op_border_type}}"style="background-color:{{$link->op_bg_color}}"; href="{{$link->href}}"><img src="{{url('media/uploads/', $link->op_post_image)}}" alt="IMAGE" > {{$link->posttitle}}</a>
    @endif
    
          @if(!empty($link->op_post_video))

        <a  class="link{{$link->op_border_type}}"style="background-color:{{$link->op_bg_color}}"; href="{{$link->href}}"><video width="100%" height="240" controls> <source src="{{url('media/uploads/', $link->op_post_video)}}" alt="VIDEO" >{{$link->posttitle}}</a></video>
        
        @endif
  
@endforeach
      <div class="pagination-block">
        <?php //{{ $countries->links('layouts.paginationlinks') }} ?>
        {{  $links->links('layouts.paginationlinks') }}
    </div>
</div>
</div>



@if (!empty($fb_pixel))
<!-- Facebook Pixeç Code -->
<script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                n.callMethod.apply(n,arguments):n.queue.push(arguments)};
                if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
                n.queue=[];t=b.createElement(e);t.async=!0;
                t.src=v;s=b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t,s)}(window, document, 'script',
                'https://connect.facebook.net/en_US/fbevents.js');
                fbq('init', '{{$fb_pixel}}');
                fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none" 
        src="https://www.facebook.com/tr?=id={{$fb_pixel}}&ev=PageView&noscript=1"
        /></noscript>
    <!-- End Facebook Pixel Code -->
@endif
</body>
<footer>
    

</footer>
</html>