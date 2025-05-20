<?php

namespace App\Services\Builder\Form;

use App\Models\Builder\BlockType;
use App\Services\Builder\FormFieldsService;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Forms\Set;

class BuilderBlockResourceForm {

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function BasicInformationSection(): Section {
        return Section::make(__('site-builder/block.tabs.basic_info'))
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
                            ->options(BlockType::where('is_active', true)->get()->pluck('translated_name', 'id'))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->columnSpan(6)
                            ->afterStateUpdated(function ($state, Set $set) {
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
                            ->options(function (Get $get) {
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
            ->collapsible();
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function BlocksContentSection(): Section {
        return Section::make(__('site-builder/block.tabs.content'))
            ->schema(function (Get $get) {
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
            ->visible(fn(Get $get) => $get('block_type_id'))
            ->collapsible();
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function BlocksTranslationsSection(): Group {
        return Group::make()->schema(function (Get $get) {
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

        })->visible(fn(Get $get) => $get('block_type_id'))
            ->columnSpanFull();

    }


}
