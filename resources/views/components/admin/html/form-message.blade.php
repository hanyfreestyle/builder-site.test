@props([
    'type' => 'danger', // default
    'bag' => 'default',
    'field' => '__form',
])

@php
    $messages = $errors->getBag($bag)->get($field);
    $alertTypes = [
        'danger' => 'alert-danger',
        'warning' => 'alert-warning',
        'info' => 'alert-info',
        'success' => 'alert-success',
    ];

    $iconTypes = [
        'danger' => '❌',
        'warning' => '⚠️',
        'info' => 'ℹ️',
        'success' => '✅',
    ];

    $class = $alertTypes[$type] ?? 'alert-danger';
    $icon = $iconTypes[$type] ?? '❌';
@endphp

@if ($messages)
    <div class="alert {{ $class }} alert-dismissible fade show d-flex flex-column gap-2" role="alert">
        @foreach ($messages as $message)
            <div class="d-flex align-items-center gap-2">
                <span>{{ $icon }}</span>
                <span>{{ $message }}</span>
            </div>
        @endforeach

        <button type="button" class="btn-close align-self-end mt-1" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
