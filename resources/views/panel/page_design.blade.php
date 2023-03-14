@extends('panel.page')

@section('body')
<h3> Page Update</h3>
@if ($errors->any())
<ul>
@foreach ($errors->all() as $error)
<li>{{$error}}</li>
    
@endforeach
</ul>
@endif
<form method="POST" enctype="multipart/form-data">
@csrf
<label>
   Page Profile Image <br/>
    <input type="file" name="op_profile_image" value="{{$page->op_profile_image}}"/>
</label>


    <label>
       Page Title <br/>
        <input type="text" name="op_title" value="{{$page->op_title}}"/>
    </label>
    
    <label>
       Bio Description <br/>
        <input type="text" name="op_description" value="{{$page->op_description}}"/>
    </label>
    <label>
        Cor da Fonte <br/>
         <input type="color" name="op_font_color" value="#FFFFFF" value="{{$page->op_font_color}}"/>
     </label>

    <label>
      Background Color <br/>
        <input type="color" name="op_bg_value" value="#FFFFFF" value="{{$page->op_bg_color}}"/>
    </label>

    <label>
        Background Image <br/>
         <input type="file" name="op_background_image" value="{{$page->op_background_image}}"/>
     </label>



    <label>
      Facebook Pixel Code <br/>
      <input type="text" name="op_fb_pixel" value="{{$page->op_fb_pixel}}" />
  </label>

    <label>

        <input type="submit" value="Salvar" />
    </label>
</form>
    
@endsection