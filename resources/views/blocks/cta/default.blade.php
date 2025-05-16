<div class="cta-block py-5 mb-5">
    @php
        $style = $data['style'] ?? 'standard';
        $backgroundColor = $data['background_color'] ?? null;
        $textColor = $data['text_color'] ?? null;
        
        $containerClass = match($style) {
            'boxed' => 'container',
            'full-width' => 'container-fluid px-0',
            default => 'container'
        };
        
        $contentClass = match($style) {
            'boxed' => 'p-5 rounded shadow-sm',
            'full-width' => 'py-5',
            default => 'py-4'
        };
    @endphp
    
    <div class="{{ $containerClass }}">
        <div class="{{ $contentClass }}" id="cta-{{ $block->id }}">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <h2 class="mb-3">{{ $data['title'] ?? 'Call to Action' }}</h2>
                    
                    @if(isset($data['description']))
                    <p class="lead mb-4">{{ $data['description'] }}</p>
                    @endif
                    
                    @if(isset($data['button']) && isset($data['button']['text']) && isset($data['button']['url']))
                    <a href="{{ $data['button']['url'] }}" class="btn btn-lg btn-primary px-4 py-2">
                        {{ $data['button']['text'] }}
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    @if($backgroundColor || $textColor)
    <style>
        #cta-{{ $block->id }} {
            @if($backgroundColor)
            background-color: {{ $backgroundColor }};
            @endif
            
            @if($textColor)
            color: {{ $textColor }};
            @endif
        }
    </style>
    @endif
</div>
