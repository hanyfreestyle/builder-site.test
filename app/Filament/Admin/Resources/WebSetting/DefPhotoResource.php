<?php

namespace App\Filament\Admin\Resources\WebSetting;

use App\Filament\Admin\Resources\WebSetting\DefPhotoResource\Pages;
use App\FilamentCustom\UploadFile\WebpUploadWithFilter;
use App\Models\WebSetting\DefPhoto;
use App\FilamentCustom\Table\CreatedDates;
use App\FilamentCustom\Table\ImageColumnDef;
use App\Models\WebSetting\WebSiteSettings;
use App\Traits\Admin\Helper\SmartResourceTrait;
use Astrotomic\Translatable\Translatable;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;


class DefPhotoResource extends Resource {
    use Translatable;
    use SmartResourceTrait;

    protected static ?string $model = DefPhoto::class;
    protected static ?string $navigationIcon = 'heroicon-s-photo';

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function canViewAny(): bool {
        return Gate::forUser(auth()->user())->allows('viewDefaultPhoto', WebSiteSettings::class);
    }

    public static function getRecordTitle(?Model $record): Htmlable|string|null {
        return $record->cat_id ?? null;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getNavigationGroup(): ?string {
        return __('filament/settings/webSetting.navigation_group');
    }

    public static function getNavigationLabel(): string {
        return __('filament/settings/defPhotos.NavigationLabel');
    }

    public static function getModelLabel(): string {
        return __('filament/settings/defPhotos.ModelLabel');
    }

    public static function getPluralModelLabel(): string {
        return __('filament/settings/defPhotos.PluralModelLabel');
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function form(Form $form): Form {
        $filterId = getModuleConfigKey("DefPhoto_filter_photo", 0);
        return $form->schema([
            Group::make()->schema([
                Section::make('')->schema([
                    TextInput::make('cat_id')
                        ->label(__('Cat ID'))
                        ->unique(DefPhoto::class, 'cat_id', ignoreRecord: true)
                        ->dehydrated()
                        ->live(onBlur: true)
                        ->maxLength(255)
                        ->afterStateUpdated(fn($state, $set) => $set('cat_id', Url_Slug($state, ['delimiter' => '_'])))
                        ->extraAttributes(['dir' => 'ltr', 'style' => 'text-align: left',])
                        ->required(),
                ])->columnSpanFull()
            ])->columnSpan(2),

            Group::make()->schema([
                Section::make()->schema([
                    ...WebpUploadWithFilter::make()
                        ->setFilterId($filterId)
                        ->setUploadDirectory('def-photo')
                        ->setRequiredUpload(true)
                        ->setChangeFilter(true)
                        ->getColumns(),
                ]),
            ])->columnSpan(1),

        ])->columns(3);
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function table(Table $table): Table {
        return $table
            ->columns([
                ImageColumnDef::make('photo'),
                Tables\Columns\TextColumn::make('cat_id')
                    ->copyable()
                    ->label('Cat Id'),
                ...CreatedDates::make()->toggleable(true)->getColumns(),
            ])->filters([

            ])
            ->persistFiltersInSession()
            ->persistSearchInSession()
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->recordUrl(fn($record) => static::getTableRecordUrl($record))
            ->reorderable('position')
            ->defaultSort('position');
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getPages(): array {
        return [
            'index' => Pages\ListDefPhotos::route('/'),
            'create' => Pages\CreateDefPhoto::route('/create'),
            'edit' => Pages\EditDefPhoto::route('/{record}/edit'),
        ];
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getTableRecordUrl($record): ?string {
        return static::getUrl('edit', ['record' => $record->getKey()]);
    }

}
