@extends('panel.template')

@section('title', ' 4tagme - '.$page->op_title.' - Links')
 

@section('content')
<div class="preheader">
    Pagina: {{$page->op_title}}
</div>

<div class="area">
    <div class="leftside">
        <header>

            <ul>

                <li @if ($menu=='links')class="active" @endif><a href="{{url('/panel/'.$page->slug.'/links')}}">Links</a> </li>
                <li @if ($menu=='design')class="active" @endif><a href="{{url('/panel/'.$page->slug.'/design')}}">Design</a> </li>
                <li @if ($menu=='stats')class="active" @endif> <a href="{{url('/panel/'.$page->slug.'/stats')}}">Stats</a> </li>

            </ul>

        </header>

        @yield('body')
    </div>

    <div class="rightside">
        <iframe frameborder="0" src="{{url('/'.$page->slug)}}"></iframe>
    </div>

</div>

@endsection