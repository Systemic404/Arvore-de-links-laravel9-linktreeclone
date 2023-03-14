@extends('panel.page')

@section('body')
<h3> Page Stats</h3>
@if ($errors->any())
<ul>
@foreach ($errors->all() as $error)
<li>{{$error}}</li>
    
@endforeach
</ul>
@endif

@csrf


    
<table>
    <thead>

        <tr>
            <th>Date</th>
            <th width="20">Views</th>
            
        </tr>
    </thead>
    <tbody>
        @foreach ($views as $view)
        <tr>
        
        <td> {{$view->view_date}} 
        </td>
        <td>  {{$view->total}}
        </td>
        
        </tr>
        @endforeach
    </tbody>
</table>
@endsection