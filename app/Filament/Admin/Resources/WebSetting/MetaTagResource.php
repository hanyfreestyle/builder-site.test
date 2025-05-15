<?php

namespace App\Filament\Admin\Resources\WebSetting;

use App\Filament\Admin\Resources\WebSetting\MetaTagResource\Pages;
use App\FilamentCustom\Form\Translation\MainInput;
use App\FilamentCustom\UploadFile\WebpUploadWithFilter;
use App\Models\WebSetting\MetaTag;
use App\FilamentCustom\Table\CreatedDates;
use App\FilamentCustom\Table\ImageColumnDef;
use App\FilamentCustom\Table\TranslationTextColumn;
use App\Helpers\FilamentAstrotomic\Forms\Components\TranslatableTabs;
use App\Helpers\FilamentAstrotomic\TranslatableTab;
use App\Models\WebSetting\WebSiteSettings;
use App\Traits\Admin\Helper\SmartResourceTrait;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Tables;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class MetaTagResource extends Resource implements TranslatableContract {
    use Translatable;
    use SmartResourceTrait;

    protected static ?string $model = MetaTag::class;
    protected static ?string $navigationIcon = 'heroicon-s-tag';

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function canViewAny(): bool {
        return Gate::forUser(auth()->user())->allows('viewMetaTag', WebSiteSettings::class);
    }

    public static function getRecordTitle(?Model $record): Htmlable|string|null {
        return $record->translation->name ?? null;
    }


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getNavigationGroup(): ?string {
        return __('filament/settings/webSetting.navigation_group');
    }

    public static function getNavigationLabel(): string {
        return __('filament/settings/meta.NavigationLabel');
    }

    public static function getModelLabel(): string {
        return __('filament/settings/meta.ModelLabel');
    }

    public static function getPluralModelLabel(): string {
        return __('filament/settings/meta.PluralModelLabel');
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function form(Form $form): Form {
        $filterId = getModuleConfigKey("MetaTag_filter_photo", 0);

        return $form->schema([
            Group::make()->schema([
                TranslatableTabs::make('translations')
                    ->availableLocales(config('app.web_add_lang'))
                    ->localeTabSchema(fn(TranslatableTab $tab) => [
                        ...MainInput::make()
                            ->setDes(false)
                            ->setSeoRequired(true)
                            ->getColumns($tab),
                    ]),

            ])->columnSpan(2),
            Group::make()->schema([
                Section::make()->schema([
                    TextInput::make('cat_id')
                        ->label("Cat Id")
                        ->extraAttributes(fn() => rtlIfArabic('en'))
                        ->unique(MetaTag::class, 'cat_id', ignoreRecord: true)
                        ->required(),

                    ...WebpUploadWithFilter::make()
                        ->setFilterId($filterId)
                        ->setUploadDirectory('meta')
                        ->setRequiredUpload(false)
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
                ImageColumnDef::make('photo_thumbnail'),
                TranslationTextColumn::make('name'),
                TranslationTextColumn::make('g_title')->label(__('default/lang.columns.g_title')),
                TranslationTextColumn::make('g_des')->label(__('default/lang.columns.g_des'))
                    ->lineClamp(2)
                    ->extraAttributes([
                        'style' => 'max-width: 350px; white-space: normal;',
                        'class' => 'text-sm leading-relaxed',
                    ]),
                ...CreatedDates::make()->toggleable(true)->getColumns(),
            ])->filters([
                TrashedFilter::make(),
            ])
            ->persistFiltersInSession()
            ->persistSearchInSession()
            ->persistSortInSession()
            ->actions([
                Tables\Actions\EditAction::make()->hidden(fn($record) => $record->trashed())->iconButton(),
                Tables\Actions\DeleteAction::make()->iconButton(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->recordUrl(fn($record) => static::getTableRecordUrl($record))
            // ->reorderable('position')
            ->defaultSort('id');
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getPages(): array {
        return [
            'index' => Pages\ListMetaTags::route('/'),
            'create' => Pages\CreateMetaTag::route('/create'),
            'edit' => Pages\EditMetaTag::route('/{record}/edit'),
        ];
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getTableRecordUrl($record): ?string {
        return static::getUrl('edit', ['record' => $record->getKey()]);
    }

}
