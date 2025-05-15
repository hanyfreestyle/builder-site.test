<?php

namespace App\Filament\Admin\Resources\DevelopersTools\FilesListResource\Pages;


use App\Filament\Admin\Resources\DevelopersTools\FilesListResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateFilesList extends CreateRecord {
    protected static string $resource = FilesListResource::class;
    protected static bool $canCreateAnother = false;


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected function mutateFormDataBeforeCreate(array $data): array {
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
    protected function getFormActions(): array {
        return [
            ...parent::getFormActions(),
            Action::make('saveAndCreateAnother')
                ->label(__('default/lang.but.save_and_create_another'))
                ->color('warning')
                ->action('saveAndCreateAnother'),
        ];
    }

    public function saveAndCreateAnother(): void {
        $this->createAnother();
        $this->redirect($this->getResource()::getUrl('create')); // التوجيه إلى index
    }

    //customize redirect after create
    public function getRedirectUrl(): string {
        return $this->getResource()::getUrl('index');
    }

}
