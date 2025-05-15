<x-filament::page>
    <div class="{{ $gridClass }}">
        @foreach ($this->getFileListGroup() as $group)
            @if($group->files_list_count > 0)
                <h1>
                    <a target="_blank" href="{{ \App\Filament\Admin\Resources\DevelopersTools\FilesListGroupResource::getUrl('edit', ['record' => $group->id]) }}">
                        {{ $group->name }}
                    </a>
                </h1>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-6">
                    @foreach($group->files_list as $fileItem)
                        @php
                            $logInfo = $this->getLastLog($fileItem->cat_id) ;
                        @endphp


                        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 transition duration-300 hover:shadow-lg">
                            <h3 class="font-bold text-lg mb-2 text-gray-800 dark:text-gray-100">
                                <a target="_blank" href="{{ \App\Filament\Admin\Resources\DevelopersTools\FilesListResource::getUrl('edit', ['record' => $fileItem->id]) }}">
                                    {{ $fileItem->title }}
                                </a>
                            </h3>
                            <p class="mb-5">
                                {!! $this->formatSmartDateWithTime(intval($logInfo['soursLogPrint'])) !!}
                            </p>

                            <div class="mt-4 flex gap-3">
                                @if(File::exists(base_path($fileItem->is_exist ?? null)))
                                    @if ($fileItem->copy)
                                        @if(isset($logInfo['soursLogTime']) and isset($logInfo['backUpLogTime']) and ($logInfo['soursLogTime'] == $logInfo['backUpLogTime']))
                                            <x-filament::button wire:click="copyFiles('{{ $fileItem->cat_id }}')" color="primary">
                                                {{__('default/lang.but.copy')}}
                                            </x-filament::button>
                                        @elseif($logInfo['soursLogTime'] == null and $logInfo['backUpLogTime'] == null)
                                            <x-filament::button wire:click="copyFiles('{{ $fileItem->cat_id }}')" color="info">
                                                {{__('default/lang.but.create')}}
                                            </x-filament::button>
                                        @elseif(isset($logInfo['soursLogTime']) and isset($logInfo['backUpLogTime']) and ($logInfo['soursLogTime'] < $logInfo['backUpLogTime']))
                                            @if ($fileItem->import)
                                                <x-filament::button wire:click="ImportFolder('{{ $fileItem->cat_id }}')" color="info">
                                                    {{__('default/lang.but.import')}}
                                                </x-filament::button>
                                            @endif
                                        @endif
                                    @endif

                                    @if ($fileItem->delete)
                                        <x-filament::button
                                            wire:click="deleteFiles('{{ $fileItem->cat_id }}')"
                                            onclick="if(!confirm('{{ __('هل أنت متأكد من الحذف؟') }}')){ event.stopImmediatePropagation(); return false; }"
                                            color="danger">
                                            {{ __('default/lang.but.delete') }}
                                        </x-filament::button>
                                    @endif
                                @else
                                    @if ($fileItem->import)
                                        <x-filament::button wire:click="ImportFolder('{{ $fileItem->cat_id }}')" color="info">
                                            {{__('default/lang.but.import')}}
                                        </x-filament::button>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endforeach
    </div>

</x-filament::page>

