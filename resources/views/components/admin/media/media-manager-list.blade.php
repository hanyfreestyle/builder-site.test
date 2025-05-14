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

<div class="gallery_container">
    @if($allowMultipleDelete and $countItems != 0)
        <div class="select_all">
            <input type="checkbox" id="select-all" class="checkbox-custom" onclick="toggleAll_{{ $relation }}(this)">
            {{ __('default/media-manager.select_all') }}
        </div>
    @endif

    <div class="gallery_div">
        @forelse ($items as $item)
            <div class="gallery_div_photo" data-draggable data-id="{{ $item->id }}">
                <div class="__allowMultipleDelete">
                    <div class="">
                        @if($allowMultipleDelete and $countItems != 0)
                            <input type="checkbox" class="checkbox-custom media-checkbox-{{ $relation }}" value="{{ $item->id }}">
                        @endif
                    </div>
                </div>
                <div class="__deleteAction">
                    <button onclick="deleteMediaItem_{{ $relation }}({{ $item->id }})"
                            class="btn btn-danger flex items-center justify-center"
                            title="{{ __('default/media-manager.delete') }}">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke-width="1.5"
                             stroke="currentColor"
                             class="w-6 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M6 7.5V18A2.25 2.25 0 008.25 20.25h7.5A2.25 2.25 0 0018 18V7.5m-9 0V6.375C9 5.339 9.84 4.5 10.875 4.5h2.25C14.161 4.5 15 5.34 15 6.375V7.5m-9 0h12"/>
                        </svg>
                    </button>
                </div>

                <div class="__img">
                    <img src="{{ Storage::disk($disk)->url($item->photo_thumbnail ) }}" class="NeedStyle"/>
                </div>
            </div>
        @empty
            {{ __('default/media-manager.no_items') }}
        @endforelse
    </div>

    @if($allowMultipleDelete and $countItems != 0)
        <div class="deleteSelected">
            <button onclick="deleteSelected_{{ $relation }}()" class="btn btn-danger">
                {{ __('default/media-manager.delete_selected') }}
            </button>
        </div>
    @endif

</div>


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
