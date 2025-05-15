<?php

namespace App\Filament\Admin\Resources\SiteBuilder;

use Astrotomic\Translatable\Translatable;
use App\Filament\Admin\Resources\SiteBuilder\TemplateResource\TableTemplate;
use App\Filament\Admin\Resources\SiteBuilder\TemplateResource\Pages;
use Filament\Forms\Components\TextInput;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use App\FilamentCustom\Form\Inputs\SlugInput;
use App\FilamentCustom\Form\Translation\MainInput;
use App\FilamentCustom\UploadFile\WebpUploadWithFilter;
use App\Traits\Admin\Helper\SmartResourceTrait;
use App\Models\SiteBuilder\Template;
use App\Helpers\FilamentAstrotomic\Forms\Components\TranslatableTabs;
use App\Helpers\FilamentAstrotomic\TranslatableTab;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Components\Group;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Facades\Gate;

class TemplateResource extends Resource implements HasShieldPermissions {
    use SmartResourceTrait;
    use TableTemplate;

    protected static ?string $model = Template::class;
    protected static ?string $navigationIcon = 'heroicon-s-paint-brush';
    protected static ?string $uploadDirectory = 'site-builder';

//    public static bool $showCategoryActions = true;
//    public static string $relatedResourceClass = BlogCategoryResource::class;
//    public static string $modelPolicy = Template::class;

//    public static function canViewAny(): bool {
//        return Gate::forUser(auth()->user())->allows('viewAnyCategory', Template::class) ;
//    }
//
//    public static function shouldRegisterNavigation(): bool {
//        return false;
//    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getPages(): array {
        return [
            'index' => Pages\ListTemplates::route('/'),
            'create' => Pages\CreateTemplate::route('/create'),
            'edit' => Pages\EditTemplate::route('/{record}/edit'),
        ];
    }
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function form(Form $form): Form {
//        $filterId = getModuleConfigKey("template_filter_photo", 0);
        $nameInputs = [];
        foreach (config('app.web_add_lang') as $lang) {
            $printLang = "(" . ucfirst($lang) . ")";
            $nameInputs[] = TextInput::make('name.' . $lang)
                ->label(__('default/lang.columns.name') . " " . $printLang)
                ->extraAttributes(fn() => rtlIfArabic($lang))
                ->required();
        }

        return $form->schema([
            Group::make()->schema([
                SlugInput::make('slug'),
                ...$nameInputs,
//                TranslatableTabs::make('translations')
//                    ->availableLocales(config('app.web_add_lang'))
//                    ->localeTabSchema(fn(TranslatableTab $tab) => [
//                        ...MainInput::make()
//                            ->setDes(false)
//                            ->setSeoRequired(false)
//                            ->getColumns($tab),
//                    ]),
            ])->columnSpan(2),

            Group::make()->schema([
                Section::make()->schema([
//                    ...WebpUploadWithFilter::make()
//                        ->setFilterId($filterId)
//                        ->setUploadDirectory(static::$uploadDirectory)
//                        ->setRequiredUpload(false)
//                        ->setCanChangeFilter(true)
//                        ->getColumns(),

                    Toggle::make('is_active')
                        ->label(__('default/lang.columns.is_active'))
                        ->default(true)
                        ->required(),
                ]),
            ])->columnSpan(1),
        ])->columns(3);
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getRelations(): array {
        return [];
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getNavigationGroup(): ?string {
        return __('site-builder/template.navigation_group');
    }
    public static function getNavigationLabel(): string {
        return __('site-builder/template.navigation_label');
    }
    public static function getModelLabel(): string {
        return __('site-builder/template.model_label');
    }
    public static function getPluralModelLabel(): string {
        return __('site-builder/template.plural_model_label');
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getPermissionPrefixes(): array {
        return static::filterPermissions(
            skipKeys: ['view'],
            keepKeys: ['cat', 'sort', 'publish'],
        );
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getRecordTitle(?Model $record): Htmlable|string|null {
        return $record->name[app()->getLocale()] ?? null;
    }
}
