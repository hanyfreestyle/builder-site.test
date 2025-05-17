@extends('templates.themeforest-hany.layout')

@section('content')
    <div class="container">
        @if(count($page->blocks) > 0)
            @foreach($page->blocks as $block)
                {!! app(\App\Services\Builder\BlockRenderer::class)->render($block) !!}
            @endforeach
        @else
            <div class="alert alert-info my-5">
                هذه الصفحة لا تحتوي على محتوى بعد. قم بإضافة بلوكات من لوحة التحكم.
            </div>
        @endif
    </div>
@endsection