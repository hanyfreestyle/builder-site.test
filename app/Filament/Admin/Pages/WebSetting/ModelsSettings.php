<?php

namespace App\Filament\Admin\Pages\WebSetting;

use App\FilamentCustom\Setting\DefaultSettings;
use App\FilamentCustom\Setting\LoadDefaultSettings;
use App\Models\WebSetting\WebSiteSettings;
use App\Traits\Admin\Helper\SmartResourceTrait;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Action;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Gate;

class ModelsSettings extends Page implements HasForms, HasActions {
    use SmartResourceTrait;

    protected static ?string $navigationIcon = 'heroicon-s-wrench-screwdriver';
    protected static string $view = 'filament.admin.pages.web-setting.models-settings';
    public ?array $data = [];
    public ?array $modules = [];

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function canAccess(): bool {
        return Gate::allows('viewWebModelsSettings', WebSiteSettings::class); // ✅ استخدام Gate للتحقق من إذن الوصول
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getNavigationGroup(): ?string {
        return __('filament/settings/webSetting.navigation_group');
    }

    public function getTitle(): string|Htmlable {
        return __('default/model-setting.NavigationLabel');
    }

    public static function getNavigationLabel(): string {
        return __('default/model-setting.NavigationLabel');
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function mount(): void {
        $valueStore = getConfigJsonFile("module");
        $state = $valueStore->get('modules', []); // هنا بنجيب كل الحقول العادية
        $this->form->fill($state);
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function form(Form $form): Form {
        $sections = LoadDefaultSettings::loadConfigData();
        return $form
            ->schema([
                ...DefaultSettings::make()->getColumns($sections),
            ])
            ->columns(4)
            ->statePath('data');
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function submit(): void {
        $data = $this->form->getState();
        $valueStore = getConfigJsonFile("module");
        $valueStore->put('modules', $data);

        Notification::make()
            ->title(__('default/lang.notification.update'))
            ->success()
            ->send();
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected function getFormActions(): array {
        return [
            Action::make('submit')
                ->label(__('default/lang.but.update'))
                ->action('submit')
                ->color('primary'),
        ];
    }
}
