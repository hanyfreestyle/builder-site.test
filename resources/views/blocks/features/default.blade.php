<div class="features-block py-5 mb-5">
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
            @php
                $columns = isset($data['columns']) ? (int) $data['columns'] : 3;
                $colClass = match($columns) {
                    2 => 'col-md-6',
                    3 => 'col-md-4',
                    4 => 'col-md-3',
                    default => 'col-md-4'
                };
            @endphp
            
            <div class="row g-4">
                @foreach($data['features'] as $feature)
                <div class="{{ $colClass }}">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            @if(isset($feature['icon']))
                            <div class="mb-3">
                                <i class="{{ $feature['icon'] }} fa-3x text-primary"></i>
                            </div>
                            @endif
                            
                            <h4>{{ $feature['title'] ?? 'Feature' }}</h4>
                            
                            @if(isset($feature['description']))
                            <p class="text-muted">{{ $feature['description'] }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
    
    @if(isset($data['background_color']))
    <style>
        .features-block {
            background-color: {{ $data['background_color'] }};
        }
    </style>
    @endif
</div>
