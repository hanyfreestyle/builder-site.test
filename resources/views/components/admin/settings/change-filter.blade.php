@php
    use App\Filament\Admin\Resources\WebSetting\UploadFilterResource;
@endphp


<div class="file_upload_filter_notes">
    {{__('filament/settings/uploadFilter.err.filter_name')}}
    <span>{{$filter->name}}</span>

    <a href="{{ UploadFilterResource::getUrl('edit', ['record' => $filter]) }}" class="inline-block py-1 text-primary-600 ">
        {{__('filament/settings/uploadFilter.err.missing_filter_btn')}}
    </a>
    {{__('filament/settings/uploadFilter.err.filter_or')}}
    <a href="{{ route('filament.admin.pages.models-settings') }}" class="inline-block py-1 text-primary-600 ">
        {{__('filament/settings/uploadFilter.err.missing_filter_btn_change')}}
    </a>
</div>




