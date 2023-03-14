@extends('panel.page')

@section('body')
  <a class="bigbutton" href="{{url('/panel/'.$page->slug.'/newlink')}}">New Link </a>
  <a class="bigbutton" href="{{url('/panel/'.$page->slug.'/newvideo')}}">New Video Link </a>
  <a class="bigbutton" href="{{url('/panel/'.$page->slug.'/newaudio')}}">New Audio Link </a>
   <a class="bigbutton" href="{{url('/panel/'.$page->slug.'/newpost')}}">New Post </a>
    <a class="bigbutton" href="{{url('/panel/'.$page->slug.'/newpostvideo')}}">New Video Post</a>
<ul id="links">
@foreach ($links as $link)

<li class="link--item " data-id="{{$link->id}}">
   <div class="link--item-order">
      <img src="{{url('/assets/images/sort.png')}}" alt="Order" width="18"/>
   </div>
   <div class="link--item-info">
      <div class="link--item-title">
         {{$link->title}}
      </div>
      <div class="link--item-href">
         {{$link->href}}
      </div>
   </div>
 <div class="link--item-buttons">
      @if(!empty($link->op_link_image))
      <a href="{{url('/panel/'.$page->slug.'/editlink/'.$link->id)}}">Edit</a>
      @endif
      @if(!empty($link->op_video))
      <a href="{{url('/panel/'.$page->slug.'/editvideo/'.$link->id)}}">EditVideo</a>
      @endif
      @if(!empty($link->op_audio))
      <a href="{{url('/panel/'.$page->slug.'/editaudio/'.$link->id)}}">EditAudio</a>
      @endif
      @if(!empty($link->op_post_image))
      <a href="{{url('/panel/'.$page->slug.'/editpost/'.$link->id)}}">EditPost</a>
      @endif
      @if(!empty($link->op_post_video))
      <a href="{{url('/panel/'.$page->slug.'/editpostvideo/'.$link->id)}}">EditVideoPost</a>
      
      @endif
      <a href="{{url('/panel/'.$page->slug.'/dellink/'.$link->id)}}">del</a>
   </div>

</li>
    
@endforeach
</ul>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
  <script>
     new Sortable(document.querySelector('#links'), {
     animation: 150,
     onEnd: async (e) => {
      let id = e.item.getAttribute('data-id');
      let link = `{{url('/panel/linkorder/${id}/${e.newIndex}')}}`;
      await fetch(link);
      window.location.href = window.location.href;
     }
   });

     </script>

@endsection