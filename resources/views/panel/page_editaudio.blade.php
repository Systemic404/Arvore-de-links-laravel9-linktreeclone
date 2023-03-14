@extends('panel.page')

@section('body')
<h3> {{isset($link) ? 'Editar Audio' : 'Novo  Audio'}}</h3>
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
    Audio File  <br/>
     <input type="file" name="op_audio" value=""/>
 </label>
    <label>
        Status: <br/>
        <select name="status">
            <option {{isset($link) ? ($link->status == '1' ? 'selected' : '') : ''}} value="1"> Active </option>
            <option {{isset($link) ? ($link->status == '0' ? 'selected' : '') : ''}} value="0"> Inactive </option>

        </select>

    </label>
    <label>
        Link Title <br/>
        <input type="text" name="title" value="{{$link->title ?? ''}}"/>
    </label>

    <label>
       Link URL: <br/>
        <input type="text" name="href" value="{{$link->href ?? ''}}"/>
    </label>

    <label>
        Background Color: <br/>
        <input type="color" name="op_bg_color" value="{{$link->op_bg_color ?? '#FFFFFF'}}"/>
    </label>

    <label>
        Text Color: <br/>
        <input type="color" name="op_text_color" value="{{$link->op_text_color ?? '#000000'}}"/>
    </label>

    <label>
        Border: <br/>
        <select name="op_border_type">
            <option {{isset($link) ? ($link->op_border_type == 'square' ? 'selected' : '') : ''}} value="square"> Square </option>
            <option {{isset($link) ? ($link->op_border_type == 'rounded' ? 'selected' : '') : ''}} value="rounded"> Rounded </option>

        </select>

    </label>
   
    <label>

        <input type="submit" value="Save" />
    </label>
</form>
    
@endsection