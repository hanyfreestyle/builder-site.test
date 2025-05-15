<?php

namespace App\Filament\Admin\Resources\SiteBuilder;

use App\FilamentCustom\Form\Inputs\SoftTranslatableInput;
use App\FilamentCustom\Form\Inputs\SoftTranslatableTextArea;
use App\FilamentCustom\UploadFile\WebpUploadFixedSize;
use App\Filament\Admin\Resources\SiteBuilder\BuilderTemplateLayoutResource\TableBuilderTemplateLayout;
use App\Filament\Admin\Resources\SiteBuilder\BuilderTemplateLayoutResource\Pages;
use App\Models\SiteBuilder\BuilderTemplate;
use Filament\Forms\Components\Select;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use App\FilamentCustom\Form\Inputs\SlugInput;
use App\Traits\Admin\Helper\SmartResourceTrait;
use App\Models\SiteBuilder\BuilderTemplateLayout;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Components\Group;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;

class BuilderTemplateLayoutResource extends Resource implements HasShieldPermissions {
    use SmartResourceTrait;
    use TableBuilderTemplateLayout;

    protected static ?string $model = BuilderTemplateLayout::class;
    protected static ?string $navigationIcon = 'heroicon-s-rectangle-group';
    protected static ?string $uploadDirectory = 'site-builder';


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getPages(): array {
        return [
            'index' => Pages\ListBuilderTemplateLayouts::route('/'),
            'create' => Pages\CreateBuilderTemplateLayout::route('/create'),
            'edit' => Pages\EditBuilderTemplateLayout::route('/{record}/edit'),
        ];
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function form(Form $form): Form {
        return $form->schema([
            Group::make()->schema([
                Group::make()->schema([
                    SlugInput::make('slug'),
                ]),
                Group::make()->schema([
                    Select::make('template_id')
                        ->label('القالب')
                        ->options(function () {
                            return  BuilderTemplate::all()
                                ->pluck('name.ar', 'id'); // استخراج التسمية من JSON مباشرة
                        })
                        ->preload()
                        ->searchable()
                        ->required(),

                    Select::make('type')
                        ->label('النوع')
                        ->preload()
                        ->searchable()
                        ->options([
                            'header' => 'Header',
                            'footer' => 'Footer',
                        ])
                        ->required(),

                ])->columns(2),
                Group::make()->schema([
                    ...SoftTranslatableInput::make()->getColumns(),

                ])->columns(2),

            ])->columnSpan(2),

            Group::make()->schema([
                Section::make()->schema([
                    ...WebpUploadFixedSize::make()
                        ->setThumbnail(true)
                        ->setResize(500, 500, 90)
                        ->setThumbnailSize(100, 100)
                        ->setUploadDirectory(static::$uploadDirectory)
                        ->setRequiredUpload(false)
                        ->setCanChangeFilter(true)
                        ->getColumns(),

                    Toggle::make('is_active')
                        ->label(__('default/lang.columns.is_active'))
                        ->default(true)
                        ->required(),

                    Toggle::make('is_default')
                        ->label(__('default/lang.columns.is_default'))
                        ->default(false)
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
        return __('site-builder/builder-template.navigation_group');
    }

    public static function getNavigationLabel(): string {
        return __('site-builder/builder-template-layout.navigation_label');
    }

    public static function getModelLabel(): string {
        return __('site-builder/builder-template-layout.model_label');
    }

    public static function getPluralModelLabel(): string {
        return __('site-builder/builder-template-layout.plural_model_label');
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
