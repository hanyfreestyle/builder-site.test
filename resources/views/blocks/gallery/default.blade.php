<div class="gallery-block py-5 mb-5">
    <div class="container">
        @if(isset($data['title']))
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2>{{ $data['title'] }}</h2>
                
                @if(isset($data['description']))
                <p class="lead">{{ $data['description'] }}</p>
                @endif
            </div>
        </div>
        @endif
        
        @if(isset($data['images']) && is_array($data['images']))
            @php
                $columns = isset($data['columns']) ? (int) $data['columns'] : 3;
                $colClass = match($columns) {
                    2 => 'col-md-6',
                    3 => 'col-md-4',
                    4 => 'col-md-3',
                    default => 'col-md-4'
                };
                
                $enableLightbox = $data['enable_lightbox'] ?? true;
            @endphp
            
            <div class="row g-4">
                @foreach($data['images'] as $image)
                <div class="{{ $colClass }}">
                    <div class="card border-0 shadow-sm h-100">
                        @if(isset($image['image']) && $image['image'])
                            @if($enableLightbox)
                            <a href="{{ asset('storage/' . $image['image']) }}" data-lightbox="gallery" data-title="{{ $image['caption'] ?? '' }}">
                                <img src="{{ asset('storage/' . $image['image']) }}" alt="{{ $image['alt'] ?? 'Gallery image' }}" class="card-img-top">
                            </a>
                            @else
                                <img src="{{ asset('storage/' . $image['image']) }}" alt="{{ $image['alt'] ?? 'Gallery image' }}" class="card-img-top">
                            @endif
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-image fa-2x text-muted"></i>
                            </div>
                        @endif
                        
                        @if(isset($image['caption']) && $image['caption'])
                        <div class="card-body">
                            <p class="card-text text-center">{{ $image['caption'] }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            
            @if($enableLightbox)
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
            <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
            @endif
        @endif
    </div>
</div>
