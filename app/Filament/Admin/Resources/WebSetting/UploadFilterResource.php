<?php

namespace App\Filament\Admin\Resources\WebSetting;

use App\Enums\WebSetting\UploadFilter\EnumsAspectRatio;
use App\Enums\WebSetting\UploadFilter\EnumsFilterType;
use App\Filament\Admin\Resources\WebSetting\UploadFilterResource\Pages;
use App\Models\WebSetting\UploadFilter;
use App\FilamentCustom\Table\CreatedDates;
use App\Models\WebSetting\WebSiteSettings;
use App\Traits\Admin\Helper\SmartResourceTrait;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class UploadFilterResource extends Resource {
    use SmartResourceTrait;

    protected static ?string $model = UploadFilter::class;
    protected static ?string $navigationIcon = 'heroicon-s-scissors';

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function canViewAny(): bool {
        return Gate::forUser(auth()->user())->allows('viewUploadFilter', WebSiteSettings::class);
    }

    public static function getRecordTitle(?Model $record): Htmlable|string|null {
        return $record->name ?? null;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getNavigationGroup(): ?string {
        return __('filament/settings/webSetting.navigation_group');
    }

    public static function getNavigationLabel(): string {
        return __('filament/settings/uploadFilter.NavigationLabel');
    }

    public static function getModelLabel(): string {
        return __('filament/settings/uploadFilter.ModelLabel');
    }

    public static function getPluralModelLabel(): string {
        return __('filament/settings/uploadFilter.PluralModelLabel');
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function form(Form $form): Form {
        return $form->schema([
            Group::make()->schema([
                Section::make(__('filament/settings/uploadFilter.section.basic_settings'))->schema([
                    Group::make()->schema([
                        TextInput::make("name")
                            ->label(__('filament/settings/uploadFilter.columns.name'))
                            ->required(),

                        Select::make('type')
                            ->label(__('filament/settings/uploadFilter.columns.type'))
                            ->options(EnumsFilterType::options())
                            ->columnSpan(2)
                            ->preload()
                            ->searchable()
                            ->required(),
                    ])->columns(3),

                    Group::make()->schema([

                        Select::make('crop_aspect_ratio')
                            ->label(__('filament/settings/uploadFilter.columns.crop_aspect_ratio'))
                            ->options(EnumsAspectRatio::options())
                            ->preload()
                            ->searchable()
                            ->nullable()
                            ->default(fn($get, $livewire) => $livewire->data['crop_aspect_ratio'] ?? "1:1"),


                        TextInput::make("width")
                            ->label(__('filament/settings/uploadFilter.columns.width'))
                            ->numeric()
                            ->required(),

                        TextInput::make("height")
                            ->label(__('filament/settings/uploadFilter.columns.height'))
                            ->numeric()
                            ->required(),

                        ColorPicker::make('canvas_back')
                            ->label(__('filament/settings/uploadFilter.columns.canvas_back'))
                            ->required(),

                    ])->columns(4),
                ]),

                Repeater::make('sizes')
                    ->relationship('sizes')
                    ->label(__('filament/settings/uploadFilter.filter_type.btn'))
                    ->hiddenLabel(true)
                    ->defaultItems(0)
                    ->visible(fn($livewire) => $livewire->getRecord() !== null)
                    ->collapsed()
                    ->collapsible()
                    ->itemLabel(function (array $state): ?string {

                        $width = $state['width'] ?? null;
                        $height = $state['height'] ?? null;
                        $type = $state['type'] ?? null;

                        $typeLabel = $type !== null && EnumsFilterType::tryFrom($type)
                            ? EnumsFilterType::from($type)->label()
                            : '—';
                        return "{$width} × {$height} — {$typeLabel}";
                    })
                    ->schema([
                        Group::make()->schema([
                            Select::make('type')
                                ->label(__('filament/settings/uploadFilter.columns.type'))
                                ->options(EnumsFilterType::options())
                                ->preload()
                                ->searchable()
                                ->required()
                                ->columnSpan(2)
                                ->default(fn($get, $livewire) => $livewire->data['type'] ?? 1),

                            Toggle::make('text_state')
                                ->inline(false)
                                ->label(__('filament/settings/uploadFilter.columns.text_state'))
                                ->required()
                                ->default(fn($get, $livewire) => $livewire->data['text_state'] ?? false),

                            Toggle::make('watermark_state')
                                ->inline(false)
                                ->label(__('filament/settings/uploadFilter.columns.watermark_state'))
                                ->required()
                                ->default(fn($get, $livewire) => $livewire->data['watermark_state'] ?? false),

                        ])->columns(4),


                        Group::make()->schema([
                            TextInput::make("width")
                                ->label(__('filament/settings/uploadFilter.columns.width'))
                                ->numeric()
                                ->required(),

                            TextInput::make("height")
                                ->label(__('filament/settings/uploadFilter.columns.height'))
                                ->numeric()
                                ->required(),

                            ColorPicker::make('canvas_back')
                                ->label(__('filament/settings/uploadFilter.columns.canvas_back'))
                                ->required(),

                        ])->columns(3),

                    ])->columnSpanFull(),


            ])->columnSpan(2),

            Group::make()->schema([
                Tabs::make('notes')
                    ->tabs(
                        collect(config('app.admin_lang'))->map(function ($label, $lang) {
                            return Tab::make($label)
                                ->schema([
                                    Textarea::make("notes.$lang")
                                        ->label(__('default/lang.columns.notes'))
                                        ->rows(4)
                                        ->extraAttributes(fn() => rtlIfArabic($lang))
                                        ->nullable(),
                                ]);
                        })->values()->toArray()
                    ),

                Section::make(__('filament/settings/uploadFilter.section.additional_settings'))->schema([
                    Group::make()->schema([
                        Toggle::make('convert_state')
                            ->label(__('filament/settings/uploadFilter.columns.convert_state'))
                            ->inline(false)
                            ->default(true)
                            ->required(),
                        TextInput::make("quality_val")
                            ->label(__('filament/settings/uploadFilter.columns.quality_val'))
                            ->numeric()
                            ->default(85)
                            ->required(),

                        Toggle::make('is_notes')
                            ->label(__('filament/settings/uploadFilter.columns.is_notes'))
                            ->inline(false)
                            ->default(true)
                            ->required(),


                    ])->columns(3),

                    Group::make()->schema([

                        Toggle::make('greyscale')
                            ->label(__('filament/settings/uploadFilter.columns.greyscale'))
                            ->inline(false)
                            ->default(false)
                            ->required(),

                        Toggle::make('flip_state')
                            ->label(__('filament/settings/uploadFilter.columns.flip_state'))
                            ->inline(false)
                            ->default(false)
                            ->required(),
                        Toggle::make('flip_v')
                            ->label(__('filament/settings/uploadFilter.columns.flip_v'))
                            ->inline(false)
                            ->default(false)
                            ->required(),
                    ])->columns(3),

                    Group::make()->schema([
                        Toggle::make('blur')
                            ->label(__('filament/settings/uploadFilter.columns.blur'))
                            ->inline(false)
                            ->default(false)
                            ->required(),
                        TextInput::make("blur_size")
                            ->label(__('filament/settings/uploadFilter.columns.blur_size'))
                            ->numeric()
                            ->default(0)
                            ->required()
                    ])->columns(2),


                    Group::make()->schema([
                        Toggle::make('pixelate')
                            ->label(__('filament/settings/uploadFilter.columns.pixelate'))
                            ->inline(false)
                            ->default(false)
                            ->required(),
                        TextInput::make("pixelate_size")
                            ->label(__('filament/settings/uploadFilter.columns.pixelate_size'))
                            ->numeric()
                            ->default(5)
                            ->required()
                    ])->columns(2),

                ]),
            ])->columnSpan(1),


            Group::make()->schema([
                Section::make(__('filament/settings/uploadFilter.section.image_text'))->schema([

                    Group::make()->schema([
                        Toggle::make('text_state')
                            ->label(__('filament/settings/uploadFilter.columns.text_state'))
                            ->inline(false)
                            ->default(true)
                            ->required(),

                        TextInput::make("text_print")
                            ->label(__('filament/settings/uploadFilter.columns.text_print'))
                            ->requiredIf('text_state', true)
                            ->nullable(),

                    ])->columns(1),

                    Group::make()->schema([

                    ])->columns(1),


                    Group::make()->schema([
                        TextInput::make("font_size")
                            ->label(__('filament/settings/uploadFilter.columns.font_size'))
                            ->numeric()
                            ->default(5)
                            ->requiredIf('text_state', true)
                            ->nullable(),

                        TextInput::make("font_opacity")
                            ->label(__('filament/settings/uploadFilter.columns.font_opacity'))
                            ->numeric()
                            ->default(5)
                            ->requiredIf('text_state', true)
                            ->nullable(),

                        ColorPicker::make('font_color')
                            ->label(__('filament/settings/uploadFilter.columns.font_color'))
                            ->requiredIf('text_state', true)
                            ->nullable(),

                    ])->columns(2),

                ]),
            ])
                ->visible(config('appConfig.uploadFilter.watermark_text'))
                ->columnSpan(1),

        ])->columns(3);
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function table(Table $table): Table {
        return $table
            ->columns([

                TextColumn::make('name')
                    ->label(__('filament/settings/uploadFilter.columns.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('crop_aspect_ratio')
                    ->label(__('filament/settings/uploadFilter.columns.crop_aspect_ratio')),

                TextColumn::make('type')
                    ->label(__('filament/settings/uploadFilter.columns.type'))
                    ->formatStateUsing(fn($state) => EnumsFilterType::from($state)->label())
                    ->description(fn(UploadFilter $record): string => $record->width . "x" . $record->height)
                    ->sortable(),


                TextColumn::make('sizes_list')
                    ->label(__('filament/settings/uploadFilter.columns.sizes'))
                    ->getStateUsing(fn(UploadFilter $record) => $record->sizes
                        ->map(fn($size) => EnumsFilterType::from($size->type)->label())
                        ->implode(' | ')
                    )
                    ->description(fn(UploadFilter $record) => $record->sizes
                        ->map(fn($size) => $size->width . 'x' . $size->height)
                        ->implode(' | ')
                    )
                    ->wrap()
                    ->limit(100),

                IconColumn::make('convert_state')
                    ->label(__('filament/settings/uploadFilter.columns.convert_state'))
                    ->boolean(),

                ...CreatedDates::make()->toggleable(true)->getColumns(),
            ])->filters([
                SelectFilter::make('type')
                    ->label(__('filament/settings/uploadFilter.columns.type'))
                    ->options(EnumsFilterType::options())
                    ->columnSpan(2)
                    ->preload()
                    ->searchable(),

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
            ->defaultSort('id');
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getPages(): array {
        return [
            'index' => Pages\ListUploadFilters::route('/'),
            'create' => Pages\CreateUploadFilter::route('/create'),
            'edit' => Pages\EditUploadFilter::route('/{record}/edit'),
        ];
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getTableRecordUrl($record): ?string {
        return static::getUrl('edit', ['record' => $record->getKey()]);
    }

}
