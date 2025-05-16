@extends('templates.default.layout')

@section('content')
    <div class="container py-4">
        <h1 class="mb-4">{{ $page->getTranslatedTitle() }}</h1>
        
        @if($page->getTranslatedDescription())
            <div class="lead mb-4">{{ $page->getTranslatedDescription() }}</div>
        @endif
        
        <!-- Render all blocks -->
        @foreach($renderedBlocks as $block)
            {!! $block !!}
        @endforeach
    </div>
@endsection
