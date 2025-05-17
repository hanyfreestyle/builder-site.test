<div class="hero-block py-5 mb-5">

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
