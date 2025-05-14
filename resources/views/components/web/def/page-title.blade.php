@if($meta->name ?? null)
    <div class="container-fluid">
        <div class="container">
            <div class="mx-auto text-center wow fadeIn" data-wow-delay="0.1s" style="max-width: 800px;">
                @if($meta->name)
                    <h2 class=" d-inline-block p-2 titleBorder">
                        {{$meta->name}}
                    </h2>
                @endif
                @if($meta->des)
                    <p class="mb-2 titleDes"> {{$meta->des}}</p>
                @endif
            </div>
        </div>
    </div>
@endif
