@php
    use Illuminate\Support\Facades\Storage;
    function formatCatId($value) {
        return ucwords(str_replace('_', ' ', $value));
    }
@endphp

<x-filament::page>
    <div id="sortable-cards" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-6">
        @foreach ($records as $record)
            @php
                $imagePath = $record->photo_thumbnail ?? $record->photo;
                $imageUrl = $imagePath ? Storage::disk('root_folder')->url($imagePath) : null;
            @endphp

            <x-filament::card>
                <div class="flex flex-col items-center text-center customCard" data-id="{{ $record->id }}">
                    <h2
                        class="text-lg font-bold cursor-pointer copy-cat-id"
                        data-value="{{ $record->cat_id }}"
                        title="اضغط لنسخ الاسم"
                    >
                        {{ formatCatId($record->cat_id) }}
                    </h2>

                    <div class="imageWrapper">
                        @if($imageUrl)
                            <img src="{{ $imageUrl }}" alt="{{ $record->cat_id }}">
                        @else
                            <div class="text-gray-400">
                                {{ __('No Image') }}
                            </div>
                        @endif
                    </div>

                    <div class="actionButtons">

                        @can('update', $record)
                            <x-filament::button
                                tag="a"
                                href="{{ $resourceClass::getUrl('edit', ['record' => $record->id]) }}"
                                icon="heroicon-s-pencil"
                                color="primary">
                                {{ __('default/lang.but.edit') }}
                            </x-filament::button>
                        @endcan

                        @can('delete', $record)
                            <form
                                action="{{ route('filament.admin.custom.def-photos.destroy', ['record' => $record->id]) }}"
                                method="POST"
                                onsubmit="return confirm('{{ __('filament-actions::delete.single.confirmation') }}');">
                                @csrf
                                @method('DELETE')
                                <x-filament::button
                                    type="submit"
                                    color="danger"
                                    icon="heroicon-s-trash"
                                >
                                    {{ __('default/lang.but.delete') }}
                                </x-filament::button>
                            </form>
                        @endcan


                    </div>
                </div>
            </x-filament::card>
        @endforeach
    </div>

    {{-- ✅ مكان دمج الاستايلات الخاصة بيك --}}
    <style>
        .fi-section {


        }

        .fi-section .fi-section-content {
            padding: 20px 10px !important;
        }

        .customCard {
            cursor: pointer;

        }

        .customCard h2 {
            border: 1px solid #ffffff22;
            padding: 6px 12px;
            border-radius: 6px;
            margin-bottom: 10px;
            width: 100%;
        }

        .imageWrapper {
            width: 100%;
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #1f2937; /* Gray-800 */

            overflow: hidden;
        }

        .imageWrapper img {
            max-height: 150px;
            max-width: 100%;
            object-fit: contain;
        }

        .actionButtons {
            margin-top: 16px;
            display: flex;
            gap: 12px;
            justify-content: center;
        }
    </style>

    <script src="{{ asset('assets/admin/vendor/sortablejs/Sortable.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sortable = new Sortable(document.getElementById('sortable-cards'), {
                animation: 150,
                onEnd: function (evt) {
                    const ids = Array.from(document.querySelectorAll('#sortable-cards [data-id]'))
                        .map(el => el.dataset.id);

                    fetch("{{ route('filament.admin.custom.def-photos.sort') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ids})
                    }).then(response => {
                        if (response.ok) {
                            console.log('Saved new order');
                        } else {
                            alert('Error saving order');
                        }
                    });
                }
            });
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.copy-cat-id').forEach(el => {
                el.addEventListener('click', function () {
                    const text = this.getAttribute('data-value');

                    navigator.clipboard.writeText(text).then(() => {
                        this.title = "✔ تم النسخ";
                        setTimeout(() => {
                            this.title = "اضغط لنسخ الاسم";
                        }, 1000);
                    });
                });
            });
        });
    </script>
</x-filament::page>
