<?php

namespace App\Filament\Admin\Resources\DevelopersTools\FilesListResource\Pages;


use App\Filament\Admin\Resources\DevelopersTools\FilesListResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditFilesList extends EditRecord {
    protected static string $resource = FilesListResource::class;

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected function mutateFormDataBeforeFill(array $data): array {
        // تحويل `files` إلى نص داخل `Textarea`
        if (!empty($data['files'])) {
            $data['files_text'] = implode("\n", array_column($data['files'], 'value'));
        }

        // تحويل `folders` إلى نص داخل `Textarea`
        if (!empty($data['folders'])) {
            $data['folders_text'] = implode("\n", array_column($data['folders'], 'value'));
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array {
        // تحويل `files_text` إلى مصفوفة `files`
        if (!empty($data['files_text'])) {
            $fileList = array_filter(array_map('trim', explode("\n", $data['files_text'])));
            $data['files'] = array_map(fn($line) => ['value' => $line], $fileList);
        }else{
            $data['files'] = [];
        }

        // تحويل `folders_text` إلى مصفوفة `folders`
        if (!empty($data['folders_text'])) {
            $folderList = array_filter(array_map('trim', explode("\n", $data['folders_text'])));
            $data['folders'] = array_map(fn($line) => ['value' => $line], $folderList);
        }else{
            $data['folders'] = [];
        }

        // إزالة النصوص الأصلية بعد التحويل
        unset($data['files_text'], $data['folders_text']);

        return $data;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected function getHeaderActions(): array {
        return [
            Actions\DeleteAction::make(),
        ];
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected function getFormActions(): array {
        return [
            ...parent::getFormActions(),
            Action::make('saveAndClose')
                ->label(__('default/lang.but.save_and_close'))
                ->color('warning')
                ->action('saveAndClose'),
        ];
    }

    public function saveAndClose(): void {
        $this->save(); // حفظ التعديلات
        $this->redirect($this->getResource()::getUrl('index')); // التوجيه إلى index
    }

}
