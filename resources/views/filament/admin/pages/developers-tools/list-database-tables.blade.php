<x-filament::page>
    {{count($this->getTables())}}
    <br>
    @foreach($this->getTables() as $table)
        '{{$table}}' <br>,
    @endforeach



</x-filament::page>
