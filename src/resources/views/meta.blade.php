@foreach($metaTags as $group)
    <!--{{$group['name']}}-->
    @foreach ($group['tags'] as $tag)
        {!!$tag!!}
    @endforeach
@endforeach
