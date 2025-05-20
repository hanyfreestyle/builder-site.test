<?php

namespace App\Filament\Admin\Resources;

use App\Models\Builder\Block;
use App\Models\Builder\BlockType;
use App\Models\Builder\Page;
use App\Services\Builder\FormFieldsService;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\BuilderBlockResource\Pages;

class BuilderBlockResource extends Resource {
    protected static ?string $model = Block::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Site Builder';
    protected static ?int $navigationSort = 25;
    protected static ?string $navigationLabel = 'blocks';
    protected static ?string $modelLabel = 'block';
    protected static ?string $pluralModelLabel = 'blocks';

    public static function getNavigationLabel(): string {
        return __('site-builder/general.blocks');
    }

    public static function getModelLabel(): string {
        return __('site-builder/block.singular');
    }

    public static function getPluralModelLabel(): string {
        return __('site-builder/general.blocks');
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function form(Form $form): Form {
        return $form
            ->schema([
                // Basic Information Section
                Section::make(__('site-builder/block.tabs.basic_info'))
                    ->schema([
                        Grid::make(12)
                            ->schema([
                                Select::make('pages')
                                    ->label(__('site-builder/block.pages'))
                                    ->placeholder(__('site-builder/block.select_pages'))
                                    ->relationship('pages', 'title')
                                    ->multiple()
                                    ->preload()
                                    ->columnSpan(6)
                                    ->searchable(),

                                Grid::make(6)
                                    ->schema([
                                        Toggle::make('is_active')
                                            ->label(__('site-builder/general.is_active'))
                                            ->inline(false)
                                            ->default(true),
                                        Toggle::make('is_visible')
                                            ->label(__('site-builder/block.is_visible'))
                                            ->inline(false)
                                            ->default(true),
                                    ])
                                    ->columnSpan(6),

                                Select::make('block_type_id')
                                    ->label(__('site-builder/block.block_type'))
                                    ->placeholder(__('site-builder/block.select_block_type'))
                                    ->options(BlockType::where('is_active', true)->pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->columnSpan(6)
                                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                                        // Get the block type and load schema
                                        if ($state) {
                                            $blockType = BlockType::find($state);
                                            if ($blockType) {
                                                // Initialize data with default values from schema
                                                $data = [];
                                                $schema = $blockType->schema ?: [];

                                                foreach ($schema as $field) {
                                                    $name = $field['name'] ?? '';
                                                    $defaultValue = $field['default'] ?? null;

                                                    if (!empty($name) && $defaultValue !== null) {
                                                        $data[$name] = $defaultValue;
                                                    }
                                                }

                                                // Set data with values from schema
                                                if (!empty($data)) {
                                                    $set('data', $data);

                                                } else {
                                                    // Clear previous data
                                                    $set('data', null);
                                                }
                                                $set('view_version', 'default');
                                            }
                                        } else {
                                            // Clear if no block type selected
                                            $set('data', null);
                                            $set('view_version', 'default');
                                        }
                                    }),

                                Select::make('view_version')
                                    ->label(__('site-builder/block.view_version'))
                                    ->options(function (Forms\Get $get) {
                                        $blockTypeId = $get('block_type_id');
                                        if (!$blockTypeId) {
                                            return ['default' => 'Default'];
                                        }

                                        $blockType = BlockType::find($blockTypeId);
                                        if (!$blockType) {
                                            return ['default' => 'Default'];
                                        }

                                        // Get templates that use this block type
                                        $templates = $blockType->templates;

                                        // Collect all view versions from all templates
                                        $allVersions = collect(['default']);
                                        foreach ($templates as $template) {
                                            $versions = $blockType->getAvailableViewVersionsForTemplate($template->id);
                                            $allVersions = $allVersions->merge($versions);
                                        }

                                        $uniqueVersions = $allVersions->unique()->values()->toArray();
                                        return array_combine($uniqueVersions, $uniqueVersions);
                                    })
                                    ->default('default')
                                    ->searchable()
                                    ->preload()
                                    ->columnSpan(6)
                                    ->required(),
                            ])
                    ])
                    ->collapsible(),

                // Content Section (Conditional based on blockType)
                Section::make(__('site-builder/block.tabs.content'))
                    ->schema(function (Forms\Get $get) {
                        $blockTypeId = $get('block_type_id');

                        if (!$blockTypeId) {
                            return [
                                Placeholder::make('no_block_type')
                                    ->label(__('site-builder/block.no_block_type_selected'))
                                    ->content(__('site-builder/block.please_select_block_type'))
                            ];
                        }
                        $blockType = BlockType::find($blockTypeId);
                        if (!$blockType) {
                            return [
                                Placeholder::make('invalid_block_type')
                                    ->label(__('site-builder/block.invalid_block_type'))
                                    ->content(__('site-builder/block.block_type_not_found'))
                            ];
                        }
                        $schema = $blockType->schema ?: [];
                        // Check if schema is empty
                        if (empty($schema)) {
                            return [
                                Placeholder::make('empty_schema')
                                    ->label(__('site-builder/block.empty_schema'))
                                    ->content(__('site-builder/block.no_fields_defined'))
                            ];
                        }
                        // Create form fields based on schema
                        return [
                            Grid::make(12)->schema(
                                FormFieldsService::createFormFieldsFromSchema($schema)
                            )
                        ];
                    })
                    ->visible(fn(Forms\Get $get) => $get('block_type_id'))
                    ->collapsible(),

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
                // Translations Section
                Group::make()->schema(function (Forms\Get $get) {
                    $blockTypeId = $get('block_type_id');

                    if (!$blockTypeId) {
                        return [
                            Placeholder::make('no_block_type_trans')->content(__('site-builder/block.please_select_block_type'))
                        ];
                    }
                    $blockType = BlockType::find($blockTypeId);
                    if (!$blockType) {
                        return [];
                    }
                    $schema = $blockType->schema ?: [];
                    if (empty($schema)) {
                        return [];
                    }
                    // Get all supported languages in the system
                    $supportedLanguages = config('app.supported_locales', ['ar', 'en']);
                    // Remove default language (first language is considered default)
                    $defaultLanguage = $supportedLanguages[0] ?? 'ar';
                    $translationLanguages = array_filter($supportedLanguages, function ($lang) use ($defaultLanguage) {
                        return $lang !== $defaultLanguage;
                    });
                    if (empty($translationLanguages)) {
                        return [
                            Placeholder::make('no_translations')->content(__('site-builder/block.no_translations_needed'))
                        ];
                    }
                    // Create sections for each language
                    $languageSections = [];
                    foreach ($translationLanguages as $locale) {
                        $fields = FormFieldsService::createTranslationFieldsFromSchema($schema, $locale);

                        if (!empty($fields)) {
                            $languageName = match ($locale) {
                                'ar' => __('site-builder/translation.locale_ar'),
                                'en' => __('site-builder/translation.locale_en'),
                                'fr' => __('site-builder/translation.locale_fr'),
                                'es' => __('site-builder/translation.locale_es'),
                                'de' => __('site-builder/translation.locale_de'),
                                default => $locale,
                            };
                            $languageSections[] = Section::make(__('site-builder/block.language_content', ['language' => $languageName]))
                                ->schema([
                                    Grid::make(12)
                                        ->schema($fields)
                                ]);
                        }
                    }


                    return $languageSections;
                })
                    ->visible(fn(Forms\Get $get) => $get('block_type_id'))
                    ->columnSpanFull(),
            ]);
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function table(Table $table): Table {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('blockType.name')
                    ->label(__('site-builder/block.block_type'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('pages.title')
                    ->label(__('site-builder/block.pages'))
                    ->badge()
                    ->searchable(),

                Tables\Columns\TextColumn::make('data.title')
                    ->label(__('site-builder/general.title'))
                    ->searchable()
                    ->limit(30),

                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('site-builder/general.is_active'))
                    ->boolean(),

                Tables\Columns\TextColumn::make('view_version')
                    ->label(__('site-builder/block.view_version'))
                    ->badge(),

                Tables\Columns\IconColumn::make('is_visible')
                    ->label(__('site-builder/block.is_visible'))
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('site-builder/general.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('site-builder/general.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('block_type_id')
                    ->label(__('site-builder/block.block_type'))
                    ->options(BlockType::pluck('name', 'id'))
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('pages')
                    ->label(__('site-builder/block.pages'))
                    ->options(Page::pluck('title', 'id'))
                    ->relationship('pages', 'id')
                    ->searchable()
                    ->preload()
                    ->multiple(),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('site-builder/general.is_active')),

                Tables\Filters\TernaryFilter::make('is_visible')
                    ->label(__('site-builder/block.is_visible')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('duplicate')
                    ->label(__('site-builder/block.duplicate'))
                    ->icon('heroicon-o-document-duplicate')
                    ->action(function ($record) {
                        // Create a copy of the block with the same data
                        $newBlock = $record->replicate();
                        $newBlock->push();

                        // Copy all the relationships to pages
                        foreach ($record->pages as $page) {
                            $newBlock->pages()->attach($page->id, ['sort_order' => $page->pivot->sort_order]);
                        }

                        return redirect()->route('filament.admin.resources.builder-blocks.edit', ['record' => $newBlock->id]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activateBulk')
                        ->label(__('site-builder/general.activate'))
                        ->icon('heroicon-o-check')
                        ->action(fn(Builder $query) => $query->update(['is_active' => true])),
                    Tables\Actions\BulkAction::make('deactivateBulk')
                        ->label(__('site-builder/general.deactivate'))
                        ->icon('heroicon-o-x-mark')
                        ->action(fn(Builder $query) => $query->update(['is_active' => false])),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array {
        return [
            //
        ];
    }

    public static function getPages(): array {
        return [
            'index' => Pages\ListBuilderBlocks::route('/'),
            'create' => Pages\CreateBuilderBlock::route('/create'),
            'edit' => Pages\EditBuilderBlock::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
