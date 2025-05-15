<?php

namespace App\Filament\Admin\Resources\SiteBuilder;

use App\Filament\Admin\Resources\SiteBuilder\BuilderTemplateResource\TableBuilderTemplate;
use App\FilamentCustom\Form\Inputs\SoftTranslatableInput;
use App\FilamentCustom\Form\Inputs\SoftTranslatableTextArea;
use App\FilamentCustom\UploadFile\WebpUploadFixedSize;
use App\Filament\Admin\Resources\SiteBuilder\BuilderTemplateResource\Pages;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use App\FilamentCustom\Form\Inputs\SlugInput;
use App\Traits\Admin\Helper\SmartResourceTrait;
use App\Models\SiteBuilder\BuilderTemplate;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Components\Group;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;

class BuilderTemplateResource extends Resource implements HasShieldPermissions {
    use SmartResourceTrait;
    use TableBuilderTemplate;

    protected static ?string $model = BuilderTemplate::class;
    protected static ?string $navigationIcon = 'heroicon-s-paint-brush';
    protected static ?string $uploadDirectory = 'site-builder';

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
        return $form->schema([
            Group::make()->schema([
                Section::make()->schema([
                    Group::make()->schema([
                        SlugInput::make('slug'),
                    ]),
                    Group::make()->schema([
                        ...SoftTranslatableInput::make()->getColumns(),
                        ...SoftTranslatableTextArea::make()->setDataRequired(false)->getColumns(),
                    ])->columns(2),
                ]),
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
        return __('site-builder/builder-template.navigation_label');
    }

    public static function getModelLabel(): string {
        return __('site-builder/builder-template.model_label');
    }

    public static function getPluralModelLabel(): string {
        return __('site-builder/builder-template.plural_model_label');
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
