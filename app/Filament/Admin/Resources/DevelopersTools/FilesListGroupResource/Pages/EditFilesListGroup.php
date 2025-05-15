<?php

namespace App\Filament\Admin\Resources\DevelopersTools\FilesListGroupResource\Pages;

use App\Filament\Admin\Resources\DevelopersTools\FilesListGroupResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditFilesListGroup extends EditRecord{
    protected static string $resource = FilesListGroupResource::class;

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
