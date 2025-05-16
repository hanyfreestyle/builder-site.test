<?php

namespace App\Filament\Admin\Resources\BuilderPageResource\RelationManagers;

use App\Models\Builder\BlockType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BlocksRelationManager extends RelationManager
{
    protected static string $relationship = 'blocks';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('block_type_id')
                    ->label('نوع البلوك')
                    ->options(function () {
                        // Get the page's template
                        $page = $this->getOwnerRecord();
                        $templateId = $page->template_id;

                        // Get all block types enabled for this template
                        return BlockType::whereHas('templates', function ($query) use ($templateId) {
                            $query->where('template_id', $templateId)
                                  ->where('is_enabled', true);
                        })->pluck('name', 'id');
                    })
                    ->reactive()
                    ->required()
                    ->searchable()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        // Clear previous data when block type changes
                        $set('data', null);
                        $set('view_version', 'default');
                    }),

                Forms\Components\Select::make('view_version')
                    ->label('إصدار العرض')
                    ->options(function (Forms\Get $get) {
                        // Get the page's template
                        $page = $this->getOwnerRecord();
                        $template = $page->template;

                        // Get the selected block type
                        $blockTypeId = $get('block_type_id');
                        if (!$blockTypeId || !$template) {
                            return ['default' => 'Default'];
                        }

                        // Get the relation between the template and block type
                        $relation = $template->blockTypes()->where('block_type_id', $blockTypeId)->first();
                        if (!$relation) {
                            return ['default' => 'Default'];
                        }

                        // Get available view versions
                        $versions = json_decode($relation->pivot->view_versions, true) ?: ['default'];
                        return array_combine($versions, $versions);
                    })
                    ->default('default')
                    ->required(),

                // Dynamic Block Fields based on Schema
                Forms\Components\Section::make('بيانات البلوك')
                    ->schema(function (Forms\Get $get) {
                        $blockTypeId = $get('block_type_id');

                        if (!$blockTypeId) {
                            return [];
                        }

                        $blockType = BlockType::find($blockTypeId);

                        if (!$blockType) {
                            return [];
                        }

                        $schema = $blockType->schema ?: [];

                        // Create form fields based on schema
                        $formFields = [];

                        foreach ($schema as $field) {
                            $formField = null;
                            $name = $field['name'] ?? '';
                            $label = $field['label'] ?? $name;
                            $type = $field['type'] ?? 'text';
                            $required = $field['required'] ?? false;
                            $placeholder = $field['placeholder'] ?? null;
                            $help = $field['help'] ?? null;
                            $defaultValue = $field['default'] ?? null;
                            $width = $field['width'] ?? 'full';

                            // Convert width to Filament width
                            $fieldWidth = match($width) {
                                '1/2' => 'md:col-span-1',
                                '1/3' => 'md:col-span-1',
                                '2/3' => 'md:col-span-2',
                                default => 'col-span-2',
                            };

                            // Create field based on type
                            switch ($type) {
                                case 'text':
                                    $formField = Forms\Components\TextInput::make("data.{$name}")
                                        ->label($label)
                                        ->required($required)
                                        ->placeholder($placeholder)
                                        ->helperText($help)
                                        ->default($defaultValue);
                                    break;

                                case 'textarea':
                                    $formField = Forms\Components\Textarea::make("data.{$name}")
                                        ->label($label)
                                        ->required($required)
                                        ->placeholder($placeholder)
                                        ->helperText($help)
                                        ->default($defaultValue);
                                    break;

                                case 'rich_text':
                                    $formField = Forms\Components\RichEditor::make("data.{$name}")
                                        ->label($label)
                                        ->required($required)
                                        ->placeholder($placeholder)
                                        ->helperText($help)
                                        ->default($defaultValue);
                                    break;

                                case 'select':
                                    $options = $field['options'] ?? [];
                                    $formField = Forms\Components\Select::make("data.{$name}")
                                        ->label($label)
                                        ->options($options)
                                        ->required($required)
                                        ->placeholder($placeholder)
                                        ->helperText($help)
                                        ->default($defaultValue);
                                    break;

                                case 'checkbox':
                                    $formField = Forms\Components\Toggle::make("data.{$name}")
                                        ->label($label)
                                        ->required($required)
                                        ->helperText($help)
                                        ->default($defaultValue ?: false);
                                    break;

                                case 'radio':
                                    $options = $field['options'] ?? [];
                                    $formField = Forms\Components\Radio::make("data.{$name}")
                                        ->label($label)
                                        ->options($options)
                                        ->required($required)
                                        ->helperText($help)
                                        ->default($defaultValue);
                                    break;

                                case 'image':
                                    $formField = Forms\Components\FileUpload::make("data.{$name}")
                                        ->label($label)
                                        ->image()
                                        ->directory('images')
                                        ->required($required)
                                        ->helperText($help);
                                    break;

                                case 'file':
                                    $formField = Forms\Components\FileUpload::make("data.{$name}")
                                        ->label($label)
                                        ->directory('files')
                                        ->required($required)
                                        ->helperText($help);
                                    break;

                                case 'date':
                                    $formField = Forms\Components\DatePicker::make("data.{$name}")
                                        ->label($label)
                                        ->required($required)
                                        ->placeholder($placeholder)
                                        ->helperText($help)
                                        ->default($defaultValue);
                                    break;

                                case 'time':
                                    $formField = Forms\Components\TimePicker::make("data.{$name}")
                                        ->label($label)
                                        ->required($required)
                                        ->placeholder($placeholder)
                                        ->helperText($help)
                                        ->default($defaultValue);
                                    break;

                                case 'color':
                                    $formField = Forms\Components\ColorPicker::make("data.{$name}")
                                        ->label($label)
                                        ->required($required)
                                        ->helperText($help)
                                        ->default($defaultValue);
                                    break;

                                case 'icon':
                                    $formField = Forms\Components\TextInput::make("data.{$name}")
                                        ->label($label)
                                        ->required($required)
                                        ->placeholder($placeholder)
                                        ->helperText($help ?: 'أدخل اسم الأيقونة، مثال: fas fa-home')
                                        ->default($defaultValue);
                                    break;

                                case 'link':
                                    $formField = Forms\Components\Group::make([
                                        Forms\Components\TextInput::make("data.{$name}.text")
                                            ->label('نص الرابط')
                                            ->required($required)
                                            ->placeholder('أدخل نص الرابط')
                                            ->default($defaultValue['text'] ?? null),

                                        Forms\Components\TextInput::make("data.{$name}.url")
                                            ->label('عنوان الرابط')
                                            ->required($required)
                                            ->placeholder('أدخل عنوان الرابط URL')
                                            ->default($defaultValue['url'] ?? null),
                                    ])
                                    ->label($label);
                                    //->helperText($help);
                                    break;

                                case 'number':
                                    $formField = Forms\Components\TextInput::make("data.{$name}")
                                        ->label($label)
                                        ->numeric()
                                        ->required($required)
                                        ->placeholder($placeholder)
                                        ->helperText($help)
                                        ->default($defaultValue);
                                    break;

                                case 'repeater':
                                    $subSchema = [];

                                    // If we have nested fields, create them
                                    if (isset($field['fields']) && is_array($field['fields'])) {
                                        foreach ($field['fields'] as $subField) {
                                            $subName = $subField['name'] ?? '';
                                            $subLabel = $subField['label'] ?? $subName;
                                            $subType = $subField['type'] ?? 'text';

                                            // Basic subfields for now, can be expanded
                                            if ($subType === 'text') {
                                                $subSchema[] = Forms\Components\TextInput::make($subName)
                                                    ->label($subLabel)
                                                    ->required($subField['required'] ?? false);
                                            } elseif ($subType === 'textarea') {
                                                $subSchema[] = Forms\Components\Textarea::make($subName)
                                                    ->label($subLabel)
                                                    ->required($subField['required'] ?? false);
                                            } elseif ($subType === 'image') {
                                                $subSchema[] = Forms\Components\FileUpload::make($subName)
                                                    ->label($subLabel)
                                                    ->image()
                                                    ->required($subField['required'] ?? false);
                                            } elseif ($subType === 'icon') {
                                                $subSchema[] = Forms\Components\TextInput::make($subName)
                                                    ->label($subLabel)
                                                    ->required($subField['required'] ?? false);
                                            }
                                        }
                                    } else {
                                        // Default fields for a repeater
                                        $subSchema = [
                                            Forms\Components\TextInput::make('title')
                                                ->label('العنوان')
                                                ->required(),
                                            Forms\Components\Textarea::make('description')
                                                ->label('الوصف'),
                                        ];
                                    }

                                    $formField = Forms\Components\Repeater::make("data.{$name}")
                                        ->label($label)
                                        ->schema($subSchema)
                                        ->required($required)
                                        ->helperText($help)
                                        ->collapsible()
                                        ->defaultItems(1);
                                    break;

                                default:
                                    // Default to text input
                                    $formField = Forms\Components\TextInput::make("data.{$name}")
                                        ->label($label)
                                        ->required($required)
                                        ->placeholder($placeholder)
                                        ->helperText($help)
                                        ->default($defaultValue);
                                    break;
                            }

                            // Set width and add to form fields
                            if ($formField) {
                                $formField->columnSpan($fieldWidth);
                                $formFields[] = $formField;
                            }
                        }

                        return $formFields;
                    })
                    ->columns(2),

                // Translations section
                Forms\Components\Section::make('الترجمات')
                    ->schema([
                        Forms\Components\Repeater::make('translations')
                            ->label('الترجمات')
                            ->schema([
                                Forms\Components\Select::make('locale')
                                    ->label('اللغة')
                                    ->options([
                                        'ar' => 'العربية',
                                        'en' => 'الإنجليزية',
                                        'fr' => 'الفرنسية',
                                        'es' => 'الإسبانية',
                                        'de' => 'الألمانية',
                                    ])
                                    ->required(),

                                Forms\Components\Grid::make('translation_fields')
                                    ->label('حقول الترجمة')
                                    ->schema(function (Forms\Get $get) {
                                        $blockTypeId = $get('../../block_type_id');

                                        if (!$blockTypeId) {
                                            return [];
                                        }

                                        $blockType = BlockType::find($blockTypeId);

                                        if (!$blockType) {
                                            return [];
                                        }

                                        $schema = $blockType->schema ?: [];
                                        $fields = [];

                                        foreach ($schema as $field) {
                                        $name = $field['name'] ?? '';
                                        $label = $field['label'] ?? $name;
                                        $translatable = $field['translatable'] ?? true;
                                        $type = $field['type'] ?? 'text';
                                        
                                        // Skip non-translatable fields
                                        if (!$translatable) {
                                        continue;
                                        }
                                        
                                        // Handle different field types for translation
                                        if ($type === 'textarea' || $type === 'rich_text') {
                                        $fields[] = Forms\Components\Textarea::make($name)
                                        ->label($label);
                                        } elseif ($type === 'link') {
                                        // For link type, we need to handle the text part
                                        $fields[] = Forms\Components\TextInput::make("{$name}.text")
                                        ->label("{$label} (نص الرابط)");
                                        } elseif ($type === 'repeater') {
                                        // For repeater type, we create a KeyValue field to translate the titles/descriptions
                                        $fields[] = Forms\Components\KeyValue::make("{$name}")
                                                ->label($label)
                                                    ->keyLabel('عنصر')
                                                    ->valueLabel('الترجمة')
                                                    ->helperText('استخدم المفتاح للإشارة إلى العنصر والحقل مثل "0.title" أو "1.description"');
                                            } else {
                                                $fields[] = Forms\Components\TextInput::make($name)
                                                    ->label($label);
                                            }
                                        }

                                        return $fields;
                                    }),
                            ])
                            ->itemLabel(fn (array $state): ?string => $state['locale'] ?? null)
                            ->collapsible(),
                    ])
                    ->collapsed(),

                Forms\Components\Group::make([
                    Forms\Components\Toggle::make('is_active')
                        ->label('نشط')
                        ->default(true),

                    Forms\Components\Toggle::make('is_visible')
                        ->label('ظاهر')
                        ->default(true),
                ])
                ->columns(2),

                Forms\Components\TextInput::make('sort_order')
                    ->label('ترتيب العرض')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('blockType.name')
                    ->label('نوع البلوك')
                    ->description(fn ($record) => $record->blockType?->category ?? '')
                    ->searchable(),

                Tables\Columns\TextColumn::make('data.title')
                    ->label('العنوان')
                    ->searchable()
                    ->limit(30),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean(),
                    
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('الترتيب')
                    ->sortable(),

                Tables\Columns\TextColumn::make('view_version')
                    ->label('إصدار العرض')
                    ->badge(),

                Tables\Columns\IconColumn::make('is_visible')
                    ->label('ظاهر')
                    ->boolean(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('تاريخ التحديث')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('block_type_id')
                    ->label('نوع البلوك')
                    ->options(function () {
                        return BlockType::pluck('name', 'id');
                    })
                    ->searchable(),
                    
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('نشط'),
                    
                Tables\Filters\TernaryFilter::make('is_visible')
                    ->label('ظاهر'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('moveUp')
                    ->label('تحريك لأعلى')
                    ->icon('heroicon-o-arrow-up')
                    ->action(function ($record) {
                        $currentOrder = $record->sort_order;
                        $previousBlock = $this->getOwnerRecord()->blocks()
                            ->where('sort_order', '<', $currentOrder)
                            ->orderBy('sort_order', 'desc')
                            ->first();

                        if ($previousBlock) {
                            $previousOrder = $previousBlock->sort_order;
                            $record->update(['sort_order' => $previousOrder]);
                            $previousBlock->update(['sort_order' => $currentOrder]);
                        }
                    }),
                Tables\Actions\Action::make('moveDown')
                    ->label('تحريك لأسفل')
                    ->icon('heroicon-o-arrow-down')
                    ->action(function ($record) {
                        $currentOrder = $record->sort_order;
                        $nextBlock = $this->getOwnerRecord()->blocks()
                            ->where('sort_order', '>', $currentOrder)
                            ->orderBy('sort_order', 'asc')
                            ->first();

                        if ($nextBlock) {
                            $nextOrder = $nextBlock->sort_order;
                            $record->update(['sort_order' => $nextOrder]);
                            $nextBlock->update(['sort_order' => $currentOrder]);
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }
}
