@if($filter->is_notes and $filter->getLocalizedNote() ?? null)
    <div class="file_upload_notes">
        {{$filter->getLocalizedNote()}}
    </div>
@endif
