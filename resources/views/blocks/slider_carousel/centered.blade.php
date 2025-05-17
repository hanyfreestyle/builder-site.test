<div class="container-fluid p-0 mb-5 slider-carousel-centered">
    <div class="owl-carousel header-carousel position-relative" 
         data-autoplay="{{ $block->getData('auto_play') ? 'true' : 'false' }}"
         data-loop="{{ $block->getData('loop') ? 'true' : 'false' }}" 
         data-interval="{{ $block->getData('interval') }}">
        
        @foreach($block->getData('slides') as $slide)
            <div class="owl-carousel-item position-relative">
                @if(isset($slide['image']) && !empty($slide['image']))
                    <img class="img-fluid" src="{{ Storage::url($slide['image']) }}" alt="{{ $slide['image_alt'] ?? '' }}">
                @else
                    <div class="placeholder-image bg-light" style="height: 400px;"></div>
                @endif
                
                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center" style="background: rgba(0, 0, 0, {{ $block->getData('overlay_opacity') }});">
                    <div class="container">
                        <div class="row justify-content-center text-center">
                            <div class="col-10 col-lg-8">
                                @if(isset($slide['subtitle']) && !empty($slide['subtitle']))
                                    <h5 class="text-white text-uppercase mb-3 animated zoomIn">{{ $slide['subtitle'] }}</h5>
                                @endif
                                
                                @if(isset($slide['title']) && !empty($slide['title']))
                                    <h1 class="display-3 text-white animated zoomIn mb-4">{{ $slide['title'] }}</h1>
                                @endif
                                
                                @if(isset($slide['description']) && !empty($slide['description']))
                                    <p class="fs-5 fw-medium text-white mb-4 pb-2">{{ $slide['description'] }}</p>
                                @endif
                                
                                <div class="buttons-wrapper">
                                    @if(isset($slide['primary_button']['url']) && !empty($slide['primary_button']['url']))
                                        <a href="{{ $slide['primary_button']['url'] }}" class="btn btn-primary py-md-3 px-md-5 me-3 animated zoomIn">
                                            {{ $slide['primary_button']['text'] ?? 'Read More' }}
                                        </a>
                                    @endif
                                    
                                    @if(isset($slide['secondary_button']['url']) && !empty($slide['secondary_button']['url']))
                                        <a href="{{ $slide['secondary_button']['url'] }}" class="btn btn-secondary py-md-3 px-md-5 animated zoomIn">
                                            {{ $slide['secondary_button']['text'] ?? 'Free Quote' }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $(".header-carousel").owlCarousel({
            autoplay: {{ $block->getData('auto_play') ? 'true' : 'false' }},
            smartSpeed: 1500,
            loop: {{ $block->getData('loop') ? 'true' : 'false' }},
            nav: true,
            dots: true,
            autoplayTimeout: {{ $block->getData('interval') }},
            items: 1,
            navText: [
                '<i class="bi bi-chevron-left"></i>',
                '<i class="bi bi-chevron-right"></i>'
            ]
        });
    });
</script>
@endpush