<?php

namespace App\Filament\Admin\Resources\Builder;

use App\Enums\Fonts\GoogleFontsArabic;
use App\Enums\Fonts\GoogleFontsEnglish;
use App\Enums\Layouts\FontSizePx;
use App\Enums\Layouts\FontSizeRem;
use App\Filament\Admin\Resources\Builder\BuilderTemplateResource\RelationManagers\BlockTypesRelationManager;
use App\Filament\Admin\Resources\Builder\BuilderTemplateResource\TableBuilderTemplate;
use App\FilamentCustom\Form\Inputs\SlugInput;
use App\FilamentCustom\Form\Inputs\SoftTranslatableInput;
use App\FilamentCustom\Form\Inputs\SoftTranslatableTextArea;
use App\FilamentCustom\UploadFile\WebpUploadFixedSize;
use App\Models\Builder\Template;
use App\Traits\Admin\Helper\SmartResourceTrait;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\BuilderTemplateResource\Pages;

class BuilderTemplateResource extends Resource {
    use SmartResourceTrait;

//    use TableBuilderTemplate;

    protected static ?string $model = Template::class;
    protected static ?string $navigationIcon = 'heroicon-s-paint-brush';

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getPages(): array {
        return [
            'index' => Pages\ListBuilderTemplates::route('/'),
            'create' => Pages\CreateBuilderTemplate::route('/create'),
            'edit' => Pages\EditBuilderTemplate::route('/{record}/edit'),
        ];
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function form(Form $form): Form {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make(__('site-builder/template.tabs.basic_info'))->schema([
                        SlugInput::make('slug')->columnSpan(2),
                        ...SoftTranslatableInput::make()->getColumns(),
                        ...SoftTranslatableTextArea::make()->setInputName('description')
                            ->setDataRequired(false)->getColumns(),
                    ])->columns(2),

                    Forms\Components\Section::make(__('site-builder/template.settings.fonts'))
                        ->schema([
                            Forms\Components\Group::make()->schema([
                                Forms\Components\Select::make('settings.fonts.primary_ar')
                                    ->label(__('site-builder/template.fonts.primary') . " (Ar)")
                                    ->options(GoogleFontsArabic::options())
                                    ->default(GoogleFontsArabic::Tajawal)
                                    ->searchable()
                                    ->required(),

                                Forms\Components\Select::make('settings.fonts.heading_ar')
                                    ->label(__('site-builder/template.fonts.heading') . " (Ar)")
                                    ->options(GoogleFontsArabic::options())
                                    ->default(GoogleFontsArabic::Tajawal)
                                    ->searchable()
                                    ->required(),

                                Forms\Components\Select::make('settings.fonts.base_size_ar')
                                    ->label(__('site-builder/template.fonts.base_size') . " (Ar)")
                                    ->options(FontSizePx::options())
                                    ->searchable()
                                    ->required(),
                            ])->visible(fn(Get $get) => collect($get('supported_languages'))->intersect(['ar'])->isNotEmpty())
                                ->columns(3),

                            Forms\Components\Group::make()->schema([
                                Forms\Components\Select::make('settings.fonts.primary')
                                    ->label(__('site-builder/template.fonts.primary'))
                                    ->options(GoogleFontsEnglish::options())
                                    ->default(GoogleFontsEnglish::Roboto)
                                    ->searchable()
                                    ->required(),

                                Forms\Components\Select::make('settings.fonts.heading')
                                    ->label(__('site-builder/template.fonts.heading'))
                                    ->options(GoogleFontsEnglish::options())
                                    ->default(GoogleFontsEnglish::Roboto)
                                    ->searchable()
                                    ->required(),

                                Forms\Components\Select::make('settings.fonts.base_size')
                                    ->label(__('site-builder/template.fonts.base_size'))
                                    ->options(FontSizePx::options())
                                    ->searchable()
                                    ->required(),
                            ])
                                ->visible(fn(Get $get) => collect($get('supported_languages'))->intersect(['en', 'fr', 'es'])->isNotEmpty())
                                ->columns(3),


                        ])
                        ->columnSpanFull(),


                    Forms\Components\Section::make(__('site-builder/template.settings.spacing'))
                        ->schema([

                            Forms\Components\Group::make()->schema([

                                Forms\Components\Select::make('settings.spacing.base_ar')
                                    ->label(__('site-builder/template.spacing.base') . " (Ar)")
                                    ->options(FontSizeRem::options())
                                    ->searchable()
                                    ->required(),

                                Forms\Components\Select::make('settings.spacing.section_ar')
                                    ->label(__('site-builder/template.spacing.section') . " (Ar)")
                                    ->options(FontSizeRem::options())
                                    ->searchable()
                                    ->required(),

                            ])->visible(fn(Get $get) => collect($get('supported_languages'))->intersect(['ar'])->isNotEmpty())
                                ->columns(2),


                            Forms\Components\Group::make()->schema([

                                Forms\Components\Select::make('settings.spacing.base')
                                    ->label(__('site-builder/template.spacing.base'))
                                    ->options(FontSizeRem::options())
                                    ->searchable()
                                    ->required(),

                                Forms\Components\Select::make('settings.spacing.section')
                                    ->label(__('site-builder/template.spacing.section'))
                                    ->options(FontSizeRem::options())
                                    ->searchable()
                                    ->required(),

                            ])->visible(fn(Get $get) => collect($get('supported_languages'))->intersect(['en', 'fr', 'es'])->isNotEmpty())
                                ->columns(2),

                        ])
                        ->columnSpanFull(),
                ])->columnSpan(2),


                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make(__('site-builder/general.thumbnail'))->schema([
                        ...WebpUploadFixedSize::make()
                            ->setFileName('photo')
                            ->setUploadDirectory('amenity')
                            ->setRequiredUpload(false)
                            ->setResize(150, 150, 90)
                            ->getColumns(),

                        Forms\Components\Toggle::make('is_active')
                            ->label(__('site-builder/general.is_active'))
                            ->default(true),

                        Forms\Components\Toggle::make('is_default')
                            ->label(__('site-builder/general.is_default'))
                            ->default(false)
                            ->helperText(__('site-builder/template.helpers.is_default')),

                    ]),
                    Forms\Components\Section::make(__('site-builder/template.labels.supported_languages'))->schema([
                        Forms\Components\CheckboxList::make('supported_languages')
                            ->hiddenLabel()
                            ->options([
                                'ar' => __('site-builder/translation.locale_ar'),
                                'en' => __('site-builder/translation.locale_en'),
                                'fr' => __('site-builder/translation.locale_fr'),
                                'es' => __('site-builder/translation.locale_es'),
                            ])
                            ->default(['ar'])
                            ->live()
                            ->columns(2)
                            ->required(),
                    ]),
                    Forms\Components\Section::make(__('site-builder/template.settings.colors'))
                        ->schema([
                            Forms\Components\ColorPicker::make('settings.colors.primary')
                                ->label(__('site-builder/template.colors.primary'))
                                ->default('#007bff'),

                            Forms\Components\ColorPicker::make('settings.colors.secondary')
                                ->label(__('site-builder/template.colors.secondary'))
                                ->default('#6c757d'),

                            Forms\Components\ColorPicker::make('settings.colors.accent')
                                ->label(__('site-builder/template.colors.accent'))
                                ->default('#fd7e14'),

                            Forms\Components\ColorPicker::make('settings.colors.background')
                                ->label(__('site-builder/template.colors.background'))
                                ->default('#ffffff'),

                            Forms\Components\ColorPicker::make('settings.colors.text')
                                ->label(__('site-builder/template.colors.text'))
                                ->default('#212529'),
                        ])
                        ->columns(2),
                ])->columnSpan(1),


            ])->columns(3);
    }



#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getRelations(): array {
        return [
            BlockTypesRelationManager::class,
        ];
    }


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getEloquentQuery(): Builder {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getNavigationGroup(): ?string {
        return __('site-builder/general.navigation_group');
    }

    public static function getNavigationLabel(): string {
        return __('site-builder/general.templates');
    }

    public static function getModelLabel(): string {
        return __('site-builder/template.singular');
    }

    public static function getPluralModelLabel(): string {
        return __('site-builder/general.templates');
    }

}
