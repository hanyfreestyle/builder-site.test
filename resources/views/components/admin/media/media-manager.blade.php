@props([
    'record',
    'relation' => 'photos',
    'modelName' => 'product',
    'baseRoute' => 'filament.media',
    'disk' => 'root_folder',
    'showStatus' => true,
    'allowMultipleDelete' => true,
])

@php
     $items = $record->{$relation};
      $countItems = count($items) ;
@endphp

<table class="table-custom">
    <thead>
    <tr>
        @if($allowMultipleDelete and $countItems != 0)
            <th class="px-4 py-2 text-center w-5">
                <input type="checkbox" id="select-all" class="checkbox-custom" onclick="toggleAll_{{ $relation }}(this)">
            </th>
        @endif
        <th class="px-4 py-2 text-right">#</th>
        <th class="px-4 py-2 text-right"> {{ __('default/media-manager.photo') }}</th>
        @if($showStatus)
            <th class="px-4 py-2 text-center">{{ __('default/media-manager.active') }}</th>
        @endif
        <th class="px-4 py-2 text-center"></th>
    </tr>
    </thead>
    <tbody>
    @forelse ($items as $item)
        <tr data-draggable data-id="{{ $item->id }}" class="bg-white border-b border-gray-200 dark:bg-gray-900 dark:border-gray-600">
            @if($allowMultipleDelete and $countItems != 0)
                <td class="px-4 py-2 text-right">
                    <input type="checkbox" class="checkbox-custom media-checkbox-{{ $relation }}" value="{{ $item->id }}">
                </td>
            @endif
            <td class="px-4 py-2 text-right">{{ $item->id }}</td>
            <td class="px-4 py-2">
                <img src="{{ Storage::disk($disk)->url($item->photo_thumbnail ) }}"
                     class="h-20 w-20 object-cover rounded border dark:border-gray-600"/>
            </td>
            @if($showStatus)
                <td class="px-4 py-2 text-center">
                    <button onclick="toggleStatus_{{ $relation }}({{ $item->id }})" class="text-lg">
                        {!! $item->is_active ? '✅' : '❌' !!}
                    </button>
                </td>
            @endif
            <td class="px-4 py-2 text-center">
                <button onclick="deleteMediaItem_{{ $relation }}({{ $item->id }})" class="btn btn-danger">
                    {{ __('default/media-manager.delete') }}
                </button>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">
                {{ __('default/media-manager.no_items') }}
            </td>
        </tr>
    @endforelse
    </tbody>
</table>

@if($allowMultipleDelete and $countItems != 0)
    <div class="mt-4 ">
        <button onclick="deleteSelected_{{ $relation }}()" class="btn btn-danger">
            {{ __('default/media-manager.delete_selected') }}
        </button>
    </div>
@endif

@once
    @push('scripts')
        <script>
            function toggleAll_{{ $relation }}(source) {
                document.querySelectorAll('.media-checkbox-{{ $relation }}').forEach(cb => cb.checked = source.checked);
            }

            function deleteSelected_{{ $relation }}() {
                const selected = [...document.querySelectorAll('.media-checkbox-{{ $relation }}:checked')].map(cb => cb.value);
                if (selected.length === 0) return alert('{{ __('default/media-manager.not_selected') }}');
                if (!confirm('{{ __('default/media-manager.confirm_bulk_delete') }}')) return;

                fetch(`{{ route($baseRoute . '.bulk-delete', ['model' => $modelName, 'relation' => $relation]) }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ids: selected}),
                }).then(response => {
                    if (response.ok) {
                        selected.forEach(id => {
                            document.querySelector(`[data-id="${id}"]`).remove();
                        });
                        window.dispatchEvent(new CustomEvent('toast', {
                            detail: {message: '{{ __('default/media-manager.bulk_deleted_successfully') }}', color: 'green'}
                        }));
                    } else {
                        alert('{{ __('default/media-manager.bulk_deleted_error') }}');
                    }
                });
            }

            function deleteMediaItem_{{ $relation }}(id) {
                if (!confirm('{{ __('default/media-manager.confirm_delete') }}')) return;
                fetch(`{{ route($baseRoute . '.delete', ['model' => $modelName, 'relation' => $relation, 'id' => '__ID__']) }}`.replace('__ID__', id), {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                }).then(response => {
                    if (response.ok) {
                        document.querySelector(`[data-id="${id}"]`).remove();
                        window.dispatchEvent(new CustomEvent('toast', {
                            detail: {message: '{{ __('default/media-manager.deleted_successfully') }}', color: 'green'}
                        }));
                    } else {
                        alert('{{ __('default/media-manager.deleted_error') }}');
                    }
                });
            }

            function toggleStatus_{{ $relation }}(id) {
                fetch(`{{ route($baseRoute . '.toggle-status', ['model' => $modelName, 'relation' => $relation, 'id' => '__ID__']) }}`.replace('__ID__', id), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const icon = document.querySelector(`[data-id="${id}"] td:nth-child({{ $allowMultipleDelete ? 4 : 3 }}) button`);
                            icon.innerHTML = data.active ? '✅' : '❌';
                            window.dispatchEvent(new CustomEvent('toast', {
                                detail: {message: '{{ __('default/media-manager.status_updated') }}', color: 'green'}
                            }));
                        }
                    });
            }

            document.addEventListener('DOMContentLoaded', function () {
                const rows = document.querySelectorAll('[data-draggable]');
                let dragged;

                rows.forEach(row => {
                    row.setAttribute('draggable', true);

                    row.addEventListener('dragstart', () => {
                        dragged = row;
                        row.classList.add('opacity-50');
                    });

                    row.addEventListener('dragend', () => {
                        row.classList.remove('opacity-50');
                    });

                    row.addEventListener('dragover', (e) => {
                        e.preventDefault();
                        const target = e.currentTarget;
                        if (dragged !== target) {
                            target.parentNode.insertBefore(dragged, target.nextSibling);
                        }
                    });

                    row.addEventListener('drop', async () => {
                        const ids = [...document.querySelectorAll('[data-draggable]')].map((r, index) => ({
                            id: r.dataset.id,
                            position: index,
                        }));

                        await fetch(`{{ route($baseRoute . '.reorder', ['model' => $modelName, 'relation' => $relation]) }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({items: ids})
                        });

                        window.dispatchEvent(new CustomEvent('toast', {
                            detail: {message: '{{ __('default/media-manager.reorder_saved') }}', color: 'green'}
                        }));
                    });
                });
            });
        </script>
    @endpush
@endonce
