@extends('templates.restaurant.layout')

@section('content')
    <!-- Page Content -->
    <div class="container mt-4 mb-4">
        @foreach($renderedBlocks as $block)
            <div class="block-wrapper">
                {!! $block !!}
            </div>
        @endforeach
    </div>
@endsection