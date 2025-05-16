<div class="features-block features-default">
    <div class="container">
        @if(isset($data['title']))
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <h2>{{ $data['title'] }}</h2>
                    
                    @if(isset($data['subtitle']))
                        <p class="lead">{{ $data['subtitle'] }}</p>
                    @endif
                </div>
            </div>
        @endif
        
        @if(isset($data['features']) && is_array($data['features']))
            <div class="row">
                @foreach($data['features'] as $feature)
                    <div class="col-md-4 mb-4">
                        <div class="feature-item">
                            @if(isset($feature['icon']))
                                <div class="feature-icon">
                                    <i class="{{ $feature['icon'] }}"></i>
                                </div>
                            @endif
                            
                            <h4>{{ $feature['title'] ?? 'Feature Title' }}</h4>
                            
                            @if(isset($feature['description']))
                                <p>{{ $feature['description'] }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>