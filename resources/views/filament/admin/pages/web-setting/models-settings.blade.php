<x-filament-panels::page>
    <x-filament::actions
        :actions="$this->getFormActions()"
        alignment="right"
        class="mt-6"
    />

    {{ $this->form }}

    {{-- إضافة الأزرار تحت الفورم --}}
    <x-filament::actions
        :actions="$this->getFormActions()"
        alignment="right"
        class="mt-6"
    />
{{--    <button wire:click="submit">اختبار مباشر</button>--}}
</x-filament-panels::page>
