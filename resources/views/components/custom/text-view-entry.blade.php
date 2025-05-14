@php
    use Illuminate\Support\Carbon;
@endphp

<div>
    <label class="font-bold  text-primary-600 text-lg">
        {{ $getLabel() }}
    </label>

    @if(isset($dataSource) && is_array($dataSource) && array_key_exists($getState(), $dataSource))
        <p>{{ $dataSource[$getState()] }}</p>
    @elseif(isset($viewType) and $viewType == 'date')
        <p>{{ is_numeric($getState()) ? Carbon::createFromTimestamp($getState())->format('d-m-Y') : $getState() }}</p>
    @elseif(isset($viewType) and $viewType == 'dateOnly')
        <p>{{  Carbon::parse($getState())->format('Y-m-d')}}</p>
    @elseif(isset($viewType) and $viewType == 'number_format')
        <p class="font-bold text-lg"> {{ number_format($getState()) }}</p>
    @else
        <p> {{ $getState() }}</p>
    @endif

{{--    @if($viewType ?? null)--}}
{{--        @if($viewType == 'date')--}}
{{--            <p>--}}
{{--                {{ Carbon::createFromTimestamp($getState())->format('d-m-Y H:i:s') }}--}}
{{--            </p>--}}
{{--        @endif--}}
{{--    @else--}}
{{--        <p> {{ $getState() }}</p>--}}
{{--    @endif--}}

</div>
