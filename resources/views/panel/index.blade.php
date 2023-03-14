@extends('panel.template')

@section('title', '4tagme - HOME')
@section('content')

<header>
    <h2> Your Pages </h2>
</header>
<a class="bigbutton" href="{{url('/panel/npage')}}">New Page </a>

<table>
    <thead>

        <tr>
            <th>Title</th>
            <th width="20">Actions</th>
            
        </tr>
    </thead>
    <tbody>
        @foreach ($pages as $page)
        <tr>
        <td>{{$page->op_title}} ({{$page->slug}}) </td>
        <td> <a href="{{url('/'.$page->slug)}}" target="_blank">Open</a>
            <a href="{{url('/panel/'.$page->slug.'/links')}}">Links</a>
            <a href="{{url('/panel/'.$page->slug.'/design')}}">Design</a>
           <a href="{{url('/panel/'.$page->slug.'/stats')}}">Stats</a>
        </td>
        
        </tr>
        @endforeach
    </tbody>
</table>
@endsection