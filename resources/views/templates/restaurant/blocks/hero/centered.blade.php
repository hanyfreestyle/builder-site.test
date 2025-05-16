<div class="hero-block hero-centered text-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
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
        </div>
        
        @if(isset($data['photo']))
            <div class="row mt-4">
                <div class="col-md-10 mx-auto">
                    <img src="{{ $data['photo'] }}" alt="{{ $data['title'] ?? 'Hero Image' }}" class="img-fluid">
                </div>
            </div>
        @endif
    </div>
</div>