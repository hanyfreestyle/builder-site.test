<x-filament-panels::page>
    <x-filament::actions
        :actions="$this->getFormActions()"
        alignment="right"
        class="mt-6"
    />
    {{ $this->form }}

    <x-filament::actions
        :actions="$this->getFormActions()"
        alignment="right"
        class="mt-6"
    />

</x-filament-panels::page>
