<div class="hero-block py-5 mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2 class="display-4">{{ $data['title'] ?? 'Hero Title' }}</h2>
                
                @if(isset($data['subtitle']))
                <p class="lead my-4">{{ $data['subtitle'] }}</p>
                @endif
                
                <div class="mt-4">
                    @if(isset($data['link1']))
                    <a href="{{ $data['link1']['url'] }}" class="btn btn-primary btn-lg me-2">
                        {{ $data['link1']['text'] }}
                    </a>
                    @endif
                    
                    @if(isset($data['link2']))
                    <a href="{{ $data['link2']['url'] }}" class="btn btn-outline-secondary btn-lg">
                        {{ $data['link2']['text'] }}
                    </a>
                    @endif
                </div>
            </div>
            
            <div class="col-md-6">
                @if(isset($data['photo']) && $data['photo'])
                <img src="{{ asset('storage/' . $data['photo']) }}" alt="{{ $data['title'] }}" class="img-fluid rounded shadow">
                @elseif(isset($data['video_url']) && $data['video_url'])
                <div class="ratio ratio-16x9">
                    @php
                        $videoUrl = $data['video_url'];
                        if (strpos($videoUrl, 'youtube.com') !== false || strpos($videoUrl, 'youtu.be') !== false) {
                            // Extract YouTube ID
                            preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $videoUrl, $matches);
                            $youtubeId = $matches[1] ?? '';
                            echo '<iframe src="https://www.youtube.com/embed/'.$youtubeId.'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                        } elseif (strpos($videoUrl, 'vimeo.com') !== false) {
                            // Extract Vimeo ID
                            preg_match('/vimeo\.com\/(?:video\/)?([0-9]+)/', $videoUrl, $matches);
                            $vimeoId = $matches[1] ?? '';
                            echo '<iframe src="https://player.vimeo.com/video/'.$vimeoId.'" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>';
                        } else {
                            echo '<div class="alert alert-warning">Unsupported video URL</div>';
                        }
                    @endphp
                </div>
                @else
                <div class="bg-light rounded p-5 d-flex align-items-center justify-content-center" style="height: 300px;">
                    <i class="fas fa-image fa-3x text-muted"></i>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    @if(isset($data['background_color']) || isset($data['text_color']))
    <style>
        .hero-block {
            @if(isset($data['background_color']))
            background-color: {{ $data['background_color'] }};
            @endif
            
            @if(isset($data['text_color']))
            color: {{ $data['text_color'] }};
            @endif
        }
    </style>
    @endif
</div>
