<?php

namespace App\Filament\Admin\Resources\WebSetting;

use App\Filament\Admin\Resources\WebSetting\WebPrivacyResource\Pages;
use App\Models\WebSetting\WebPrivacy;
use App\FilamentCustom\Table\CreatedDates;
use App\FilamentCustom\Table\TranslationTextColumn;
use App\Helpers\FilamentAstrotomic\Forms\Components\TranslatableTabs;
use App\Helpers\FilamentAstrotomic\TranslatableTab;
use App\Models\WebSetting\WebSiteSettings;
use App\Traits\Admin\Helper\SmartResourceTrait;
use Astrotomic\Translatable\Translatable;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;


class WebPrivacyResource extends Resource {
    use Translatable;
    use SmartResourceTrait;

    protected static ?string $model = WebPrivacy::class;
    protected static ?string $navigationIcon = 'heroicon-s-bolt';

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function canViewAny(): bool {
        return Gate::forUser(auth()->user())->allows('viewWebPrivacy', WebSiteSettings::class);
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
        return __('filament/settings/policies.NavigationLabel');
    }

    public static function getModelLabel(): string {
        return __('filament/settings/policies.ModelLabel');
    }

    public static function getPluralModelLabel(): string {
        return __('filament/settings/policies.PluralModelLabel');
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function form(Form $form): Form {
        return $form->schema([
            Group::make()->schema([
                Section::make()->schema([
                    TextInput::make('name')
                        ->label(__('default/lang.columns.name'))
                        ->extraAttributes(fn() => rtlIfArabic('ar'))
                        ->required(),

                    Toggle::make('is_active')
                        ->label(__('default/lang.columns.is_active'))
                        ->default(true)
                        ->required(),
                ]),
            ])->columnSpan(1),

            Group::make()->schema([
                TranslatableTabs::make('translations')
                    ->availableLocales(config('app.web_add_lang'))
                    ->localeTabSchema(fn(TranslatableTab $tab) => [

                        TextInput::make($tab->makeName('h1'))
                            ->label(__('filament/settings/policies.columns.h1'))
                            ->extraAttributes(fn() => rtlIfArabic($tab->getLocale()))
                            ->required(),

                        TextInput::make($tab->makeName('h2'))
                            ->label(__('filament/settings/policies.columns.h2'))
                            ->extraAttributes(fn() => rtlIfArabic($tab->getLocale()))
                            ->nullable(),

                        Textarea::make($tab->makeName('des'))
                            ->label(__('filament/settings/policies.columns.des'))
                            ->rows(9)
                            ->extraAttributes(fn() => rtlIfArabic($tab->getLocale()))
                            ->required(),

                        Textarea::make($tab->makeName('lists'))
                            ->label(__('filament/settings/policies.columns.lists'))
                            ->rows(6)
                            ->extraAttributes(fn() => rtlIfArabic($tab->getLocale()))
                            ->nullable(),
                    ]),
            ])->columnSpan(2),
        ])->columns(3);
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function table(Table $table): Table {
        return $table
            ->columns([
                TranslationTextColumn::make('name'),
                TranslationTextColumn::make('h1')->label(__('filament/settings/policies.columns.h1')),
                TranslationTextColumn::make('h2')->label(__('filament/settings/policies.columns.h2')),
                IconColumn::make('is_active')->label(__('default/lang.columns.is_active'))->boolean(),
                ...CreatedDates::make()->toggleable(true)->getColumns(),
            ])->filters([
                TrashedFilter::make(),
            ])
            ->persistFiltersInSession()
            ->persistSearchInSession()
            ->persistSortInSession()
            ->actions([
                Tables\Actions\EditAction::make()->hidden(fn($record) => $record->trashed()),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->recordUrl(fn($record) => static::getTableRecordUrl($record))
            ->reorderable('position')
            ->defaultSort('id');
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getPages(): array {
        return [
            'index' => Pages\ListWebPrivacies::route('/'),
            'create' => Pages\CreateWebPrivacy::route('/create'),
            'edit' => Pages\EditWebPrivacy::route('/{record}/edit'),
        ];
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getTableRecordUrl($record): ?string {
        return static::getUrl('edit', ['record' => $record->getKey()]);
    }

}
