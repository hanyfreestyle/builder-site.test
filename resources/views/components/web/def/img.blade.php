@if( $type =='Normal')
    @if($lazy)
        <img data-src="{{getPhotoPath($row->{$rowName},$defPhoto,$defPhotoRow)}}"
             alt="{{$row->$altRow}}" title="{{$row->$altRow}}" class="lazy {{$class}}" @if($w) width="{{$w}}" @endif @if($h) height="{{$h}}" @endif >
    @else
        <img src="{{getPhotoPath($row->{$rowName},$defPhoto,$defPhotoRow)}}"
             alt="{{$row->$altRow}}" title="{{$row->$altRow}}" class="{{$class}}" @if($w) width="{{$w}}" @endif @if($h) height="{{$h}}" @endif >
    @endif
@elseif($type =='def')
    @if($lazy)
        <img data-src="{{defImagesDir($defPhoto,$defPhotoRow)}}"
             alt="{{$row->$altRow ?? $defPhoto}}" title="{{$row->$altRow ?? $defPhoto}}" class="lazy {{$class}}" @if($w) width="{{$w}}" @endif @if($h) height="{{$h}}" @endif >
    @else
        <img src="{{defImagesDir($defPhoto,$defPhotoRow)}}"
             alt="{{$row->$altRow ?? $defPhoto }}" title="{{$row->$altRow ?? $defPhoto}}" class="{{$class}}" @if($w) width="{{$w}}" @endif @if($h) height="{{$h}}" @endif >
    @endif
@endif
