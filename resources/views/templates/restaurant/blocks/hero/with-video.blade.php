<div class="hero-block hero-with-video">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1>{{ $data['title'] ?? 'Welcome' }}</h1>
                
                @if(isset($data['subtitle']))
                    <p class="lead">{{ $data['subtitle'] }}</p>
                @endif
                
                <div class="hero-buttons">
                    @if(isset($data['link1']['url']) && isset($data['link1']['text']))
                        <a href="{{ $data['link1']['url'] }}" class="btn btn-primary">
                            {{ $data['link1']['text'] }}
                        </a>
                    @endif
                    
                    @if(isset($data['link2']['url']) && isset($data['link2']['text']))
                        <a href="{{ $data['link2']['url'] }}" class="btn btn-outline-secondary">
                            {{ $data['link2']['text'] }}
                        </a>
                    @endif
                </div>
            </div>
            
            <div class="col-md-6">
                @if(isset($data['video_url']))
                    <div class="ratio ratio-16x9">
                        <iframe src="{{ $data['video_url'] }}" title="{{ $data['title'] ?? 'Hero Video' }}" allowfullscreen></iframe>
                    </div>
                @elseif(isset($data['photo']))
                    <img src="{{ $data['photo'] }}" alt="{{ $data['title'] ?? 'Hero Image' }}" class="img-fluid">
                @endif
            </div>
        </div>
    </div>
</div>