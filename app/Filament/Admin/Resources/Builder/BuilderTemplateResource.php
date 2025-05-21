<?php

namespace App\Filament\Admin\Resources\Builder;

use App\Models\Builder\Template;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\BuilderTemplateResource\Pages;
use App\Filament\Admin\Resources\BuilderTemplateResource\RelationManagers;
use App\Services\Builder\TemplateService;
use Filament\Notifications\Notification;

class BuilderTemplateResource extends Resource {

    protected static ?string $model = Template::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Site Builder';
    protected static ?int $navigationSort = 10;
    protected static ?string $navigationLabel = 'templates';
    protected static ?string $modelLabel = 'template';
    protected static ?string $pluralModelLabel = 'templates';

    public static function getNavigationLabel(): string {
        return __('site-builder/general.templates');
    }

    public static function getModelLabel(): string {
        return __('site-builder/template.singular');
    }

    public static function getPluralModelLabel(): string {
        return __('site-builder/general.templates');
    }

    public static function form(Form $form): Form {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Tabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make(__('site-builder/template.tabs.basic_info'))
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label(__('site-builder/general.name'))
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('slug')
                                    ->label(__('site-builder/general.slug'))
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(Template::class, 'slug', fn($record) => $record)
                                    ->alphaDash(),

                                Forms\Components\Textarea::make('description')
                                    ->label(__('site-builder/general.description'))
                                    ->maxLength(65535)
                                    ->columnSpanFull(),

                                Forms\Components\FileUpload::make('thumbnail')
                                    ->label(__('site-builder/general.thumbnail'))
                                    ->image()
                                    ->directory('templates/thumbnails'),

                                Forms\Components\Toggle::make('is_active')
                                    ->label(__('site-builder/general.is_active'))
                                    ->default(true),

                                Forms\Components\Toggle::make('is_default')
                                    ->label(__('site-builder/general.is_default'))
                                    ->default(false)
                                    ->helperText(__('site-builder/template.helpers.is_default')),
                            ])
                            ->columns(2),

                        Forms\Components\Tabs\Tab::make(__('site-builder/template.tabs.settings'))
                            ->schema([
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

                                Forms\Components\Section::make(__('site-builder/template.settings.fonts'))
                                    ->schema([
                                        Forms\Components\TextInput::make('settings.fonts.primary')
                                            ->label(__('site-builder/template.fonts.primary'))
                                            ->default('Roboto, sans-serif'),

                                        Forms\Components\TextInput::make('settings.fonts.heading')
                                            ->label(__('site-builder/template.fonts.heading'))
                                            ->default('Roboto, sans-serif'),

                                        Forms\Components\TextInput::make('settings.fonts.base_size')
                                            ->label(__('site-builder/template.fonts.base_size'))
                                            ->default('16px'),
                                    ])
                                    ->columns(2),

                                Forms\Components\Section::make(__('site-builder/template.settings.spacing'))
                                    ->schema([
                                        Forms\Components\TextInput::make('settings.spacing.base')
                                            ->label(__('site-builder/template.spacing.base'))
                                            ->default('1rem'),

                                        Forms\Components\TextInput::make('settings.spacing.section')
                                            ->label(__('site-builder/template.spacing.section'))
                                            ->default('3rem'),
                                    ])
                                    ->columns(2),
                            ]),

                        Forms\Components\Tabs\Tab::make(__('site-builder/template.tabs.languages'))
                            ->schema([
                                Forms\Components\CheckboxList::make('supported_languages')
                                    ->label(__('site-builder/template.labels.supported_languages'))
                                    ->options([
                                        'en' => __('site-builder/translation.locale_en'),
                                        'ar' => __('site-builder/translation.locale_ar'),
                                        'fr' => __('site-builder/translation.locale_fr'),
                                        'es' => __('site-builder/translation.locale_es'),
                                        'de' => 'German',
                                    ])
                                    ->default(['en'])
                                    ->required(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('site-builder/general.name'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label(__('site-builder/general.slug'))
                    ->searchable(),

                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label(__('site-builder/general.thumbnail'))
                    ->square(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('site-builder/general.is_active'))
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_default')
                    ->label(__('site-builder/general.is_default'))
                    ->boolean()
                    ->sortable(),

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
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('set_default')
                    ->label(__('site-builder/template.actions.set_default'))
                    ->icon('heroicon-o-star')
                    ->color('warning')
                    ->visible(fn(Template $record) => !$record->is_default && $record->is_active)
                    ->requiresConfirmation()
                    ->action(function (Template $record) {
                        $record->setAsDefault();

                        Notification::make()
                            ->title(__('site-builder/template.notifications.set_default_success'))
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('migrate_pages')
                    ->label(__('site-builder/template.actions.migrate_pages'))
                    ->icon('heroicon-o-document-duplicate')
                    ->color('info')
                    ->visible(fn(Template $record) => !$record->is_default)
                    ->requiresConfirmation()
                    ->modalHeading(__('site-builder/template.modal.migrate_pages_title'))
                    ->modalDescription(__('site-builder/template.modal.migrate_pages_description'))
                    ->modalSubmitActionLabel(__('site-builder/template.modal.migrate_pages_submit'))
                    ->action(function (Template $record) {
                        try {
                            $count = TemplateService::migrateTemplatePagesToDefault($record, true);

                            Notification::make()
                                ->title(__('site-builder/template.notifications.pages_migrated', ['count' => $count]))
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title(__('site-builder/template.notifications.migration_failed'))
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array {
        return [
            RelationManagers\BlockTypesRelationManager::class,
        ];
    }

    public static function getPages(): array {
        return [
            'index' => Pages\ListBuilderTemplates::route('/'),
            'create' => Pages\CreateBuilderTemplate::route('/create'),
            'edit' => Pages\EditBuilderTemplate::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
