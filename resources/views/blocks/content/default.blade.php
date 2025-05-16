<div class="content-block py-5 mb-5">
    <div class="container">
        @php
            $imagePosition = $data['image_position'] ?? 'right';
            $textAlignment = $data['text_alignment'] ?? 'left';
            
            $contentClass = 'col-lg-' . (isset($data['image']) ? '6' : '12');
            $imageClass = 'col-lg-6';
            
            if ($imagePosition === 'left') {
                $orderContent = 'order-lg-2';
                $orderImage = 'order-lg-1';
            } elseif ($imagePosition === 'right') {
                $orderContent = 'order-lg-1';
                $orderImage = 'order-lg-2';
            } else {
                $orderContent = '';
                $orderImage = '';
            }
            
            if ($imagePosition === 'top' || $imagePosition === 'bottom') {
                $contentClass = 'col-12';
                $imageClass = 'col-12';
            }
            
            $textAlignClass = match($textAlignment) {
                'center' => 'text-center',
                'right' => 'text-end',
                default => 'text-start'
            };
        @endphp
        
        <div class="row align-items-center g-4">
            @if(isset($data['image']) && $imagePosition === 'top')
            <div class="{{ $imageClass }} mb-4">
                <img src="{{ asset('storage/' . $data['image']) }}" alt="{{ $data['title'] ?? 'Content image' }}" class="img-fluid rounded">
            </div>
            @endif
            
            <div class="{{ $contentClass }} {{ $orderContent }}">
                <div class="{{ $textAlignClass }}">
                    @if(isset($data['title']))
                    <h2 class="mb-4">{{ $data['title'] }}</h2>
                    @endif
                    
                    @if(isset($data['content']))
                    <div class="content">
                        {!! $data['content'] !!}
                    </div>
                    @endif
                </div>
            </div>
            
            @if(isset($data['image']) && ($imagePosition === 'left' || $imagePosition === 'right'))
            <div class="{{ $imageClass }} {{ $orderImage }}">
                <img src="{{ asset('storage/' . $data['image']) }}" alt="{{ $data['title'] ?? 'Content image' }}" class="img-fluid rounded">
            </div>
            @endif
            
            @if(isset($data['image']) && $imagePosition === 'bottom')
            <div class="{{ $imageClass }} mt-4">
                <img src="{{ asset('storage/' . $data['image']) }}" alt="{{ $data['title'] ?? 'Content image' }}" class="img-fluid rounded">
            </div>
            @endif
        </div>
    </div>
</div>
