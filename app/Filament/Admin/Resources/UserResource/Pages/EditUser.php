<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditUser extends EditRecord {
    protected static string $resource = UserResource::class;

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function canAccess(array $parameters = []): bool {
        // الحصول على كائن المستخدم الجاري تعديله
        $record = $parameters['record'] ?? null;

        // التأكد أن السجل موجود ثم استخراج الـ ID
        $recordId = $record ? $record->getKey() : null;

        // السماح فقط إذا كان المستخدم الحالي هو رقم 1 ويحاول تعديل نفسه
        return $recordId == 1 ? Auth::id() === 1 : true;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected function getHeaderActions(): array {
        return [
            Actions\DeleteAction::make()->visible(fn($record) => $record->id !== 1),
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
        $this->save();
        $this->redirect($this->getResource()::getUrl('index'));
    }
}
