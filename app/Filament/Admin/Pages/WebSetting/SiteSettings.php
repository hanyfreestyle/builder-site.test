<?php

namespace App\Filament\Admin\Pages\WebSetting;


use App\Helpers\FilamentAstrotomic\Forms\Components\TranslatableTabs;
use App\Helpers\FilamentAstrotomic\TranslatableTab;
use App\Models\WebSetting\WebSettings;
use App\Models\WebSetting\WebSiteSettings;
use App\Traits\Admin\Helper\SmartResourceTrait;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;

class SiteSettings extends Page implements HasForms, HasActions {
    use SmartResourceTrait;

    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static string $view = 'filament.admin.pages.web-setting.site-settings';
    public ?array $data = [];

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function canAccess(): bool {
        return Gate::allows('viewWebSiteSettings', WebSiteSettings::class); // ✅ استخدام Gate للتحقق من إذن الوصول
    }


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getNavigationGroup(): ?string {
        return __('filament/settings/webSetting.navigation_group');
    }

    public function getTitle(): string|Htmlable {
        return __('filament/settings/webSetting.web.NavigationLabel');
    }

    public static function getNavigationLabel(): string {
        return __('filament/settings/webSetting.web.NavigationLabel');
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function mount(): void {
        $record = WebSettings::with('translations')->first();
        if (!$record) {
            return;
        }
        $state = $record->toArray(); // هنا بنجيب كل الحقول العادية
        // دمج الترجمة مع الحقول العادية
        foreach (config('app.web_add_lang') as $locale) {
            $translation = $record->translations->firstWhere('locale', $locale);
            $state[$locale] = [
                'name' => $translation?->name,
                'closed_mass' => $translation?->closed_mass,
                'meta_des' => $translation?->meta_des,
                'whatsapp_des' => $translation?->whatsapp_des,

            ];
        }
        $this->form->fill($state);
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function form(Form $form): Form {
        return $form
            ->schema([
                Group::make()->schema([
                    TranslatableTabs::make()
                        ->availableLocales(config('app.web_add_lang'))
                        ->localeTabSchema(fn(TranslatableTab $tab) => [
                            TextInput::make($tab->makeName('name'))
                                ->label(__('filament/settings/webSetting.web.columns.name'))
                                ->extraAttributes(fn() => rtlIfArabic($tab->getLocale()))
                                ->required(),
                            Textarea::make($tab->makeName('closed_mass'))
                                ->label(__('filament/settings/webSetting.web.columns.closed_mass'))
                                ->rows(5)
                                ->extraAttributes(fn() => rtlIfArabic($tab->getLocale()))
                                ->required(),
                            TextInput::make($tab->makeName('meta_des'))
                                ->label(__('filament/settings/webSetting.web.columns.meta_des'))
                                ->extraAttributes(fn() => rtlIfArabic($tab->getLocale()))
                                ->required(),
                            TextInput::make($tab->makeName('whatsapp_des'))
                                ->label(__('filament/settings/webSetting.web.columns.whatsapp_des'))
                                ->extraAttributes(fn() => rtlIfArabic($tab->getLocale()))
                                ->required(),

                        ])->columns(1),
                ])->columnSpan(1),

                Group::make()->schema([

                    Section::make(__('filament/settings/webSetting.web.section.setting'))->schema([

                        Toggle::make('web_status')
                            ->label(__('filament/settings/webSetting.web.columns.web_status'))
                            ->inline(false)
                            ->required()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state === true) {
                                    $set('web_status_date', null);
                                }
                            }),

                        DatePicker::make('web_status_date')
                            ->label(__('filament/settings/webSetting.web.columns.web_status_date'))
                            ->nullable()
                            ->requiredIf('web_status', false) // ⬅️ مطلوب فقط لو الموقع مغلق
                            ->afterOrEqual(Carbon::tomorrow()->toDateString()) // ⬅️ لازم يكون >= بكره
                            ->validationMessages([
                                'required_if' => __('filament/settings/webSetting.web.req.required_if'),
                                'after_or_equal' => __('filament/settings/webSetting.web.req.after_or_equal'),
                            ]),

                        Toggle::make('switch_lang')
                            ->label(__('filament/settings/webSetting.web.columns.switch_lang'))
                            ->inline(false)
                            ->required(),

                        Select::make('lang')
                            ->label(__('filament/settings/webSetting.web.columns.lang'))
                            ->options(config('app.web_lang'))
                            ->searchable()
                            ->preload()
                            ->required(),

                        Toggle::make('users_login')
                            ->label(__('filament/settings/webSetting.web.columns.users_login'))
                            ->inline(false)
                            ->required(),


                        Toggle::make('users_register')
                            ->label(__('filament/settings/webSetting.web.columns.users_register'))
                            ->inline(false)
                            ->required(),

                        Toggle::make('users_forget_password')
                            ->label(__('filament/settings/webSetting.web.columns.users_forget_password'))
                            ->inline(false)
                            ->required(),


                    ])->columns(2),

                ])->columnSpan(1),

                Group::make()->schema([
                    Section::make(__('filament/settings/webSetting.web.section.phones'))->schema([
                        Group::make()->schema([
                            TextInput::make('phone_num')
                                ->label(__('filament/settings/webSetting.web.columns.phone_num'))
                                ->maxLength(255)
                                ->extraAttributes(fn() => rtlIfArabic('en'))
                                ->columnSpan(1)
                                ->required(),

                            TextInput::make('phone_call')
                                ->label(__('filament/settings/webSetting.web.columns.phone_call'))
                                ->maxLength(255)
                                ->extraAttributes(fn() => rtlIfArabic('en'))
                                ->columnSpan(1)
                                ->required(),

                            TextInput::make('whatsapp_num')
                                ->label(__('filament/settings/webSetting.web.columns.whatsapp_num'))
                                ->maxLength(255)
                                ->extraAttributes(fn() => rtlIfArabic('en'))
                                ->columnSpan(1)
                                ->required(),

                            TextInput::make('whatsapp_send')
                                ->label(__('filament/settings/webSetting.web.columns.whatsapp_send'))
                                ->maxLength(255)
                                ->extraAttributes(fn() => rtlIfArabic('en'))
                                ->columnSpan(1)
                                ->required(),


                        ])->columnSpan(2)->columns(2),

                        TextInput::make('web_url')
                            ->label(__('filament/settings/webSetting.web.columns.web_url'))
                            ->url()
                            ->maxLength(255)
                            ->columnSpan(2)
                            ->extraAttributes(fn() => rtlIfArabic('en'))
                            ->required(),


                        TextInput::make('email')
                            ->label(__('filament/settings/webSetting.web.columns.email'))
                            ->email()
                            ->extraAttributes(fn() => rtlIfArabic('en'))
                            ->maxLength(255)
                            ->columnSpan(2)
                            ->required(),

                    ]),
                ])->columnSpan(1)->columns(2),


                Section::make(__('filament/settings/webSetting.web.section.social'))->schema([

                    TextInput::make('google_api')
                        ->extraAttributes(fn() => rtlIfArabic('en'))
                        ->label(__('filament/settings/webSetting.web.columns.google_api'))
                        ->nullable(),

                    TextInput::make('facebook')
                        ->extraAttributes(fn() => rtlIfArabic('en'))
                        ->label(__('filament/settings/webSetting.web.columns.facebook'))
                        ->url()
                        ->nullable(),

                    TextInput::make('twitter')
                        ->extraAttributes(fn() => rtlIfArabic('en'))
                        ->label(__('filament/settings/webSetting.web.columns.twitter'))
                        ->url()
                        ->nullable(),

                    TextInput::make('youtube')
                        ->extraAttributes(fn() => rtlIfArabic('en'))
                        ->label(__('filament/settings/webSetting.web.columns.youtube'))
                        ->url()
                        ->nullable(),

                    TextInput::make('instagram')
                        ->extraAttributes(fn() => rtlIfArabic('en'))
                        ->label(__('filament/settings/webSetting.web.columns.instagram'))
                        ->url()
                        ->nullable(),

                    TextInput::make('linkedin')
                        ->extraAttributes(fn() => rtlIfArabic('en'))
                        ->label(__('filament/settings/webSetting.web.columns.linkedin'))
                        ->url()
                        ->nullable(),


                ])->columnSpan(2)->columns('2'),

            ])
            ->columns(3)
            ->statePath('data')
            ->model(WebSettings::class);

    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function submit(): void {
        $data = $this->form->getState();
        // لو الموقع مفتوح → نخلي التاريخ null
        if ($data['web_status'] === true) {
            $data['web_status_date'] = null;
        }
        $record = WebSettings::first();
        if (!$record) {
            WebSettings::create($data);
        } else {
            $record->update($data);
        }

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
