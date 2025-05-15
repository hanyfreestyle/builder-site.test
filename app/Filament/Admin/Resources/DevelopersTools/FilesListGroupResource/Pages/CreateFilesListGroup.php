<?php

namespace App\Filament\Admin\Resources\DevelopersTools\FilesListGroupResource\Pages;

use App\Filament\Admin\Resources\DevelopersTools\FilesListGroupResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateFilesListGroup extends CreateRecord{
    protected static string $resource = FilesListGroupResource::class;
    protected static bool $canCreateAnother = false;

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
    public function getRedirectUrl(): string{
        return $this->getResource()::getUrl('index');
    }

}
