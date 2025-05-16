<?php

namespace App\Filament\Admin\Resources;

use App\Models\Builder\Page;
use App\Models\Builder\Template;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\BuilderPageResource\Pages;
use App\Filament\Admin\Resources\BuilderPageResource\RelationManagers;
use App\Services\Builder\TemplateService;
use Filament\Notifications\Notification;

class BuilderPageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Site Builder';

    protected static ?int $navigationSort = 30;

    protected static ?string $navigationLabel = 'pages';

    protected static ?string $modelLabel = 'page';

    protected static ?string $pluralModelLabel = 'pages';
    
    public static function getNavigationLabel(): string
    {
        return __('site-builder/general.pages');
    }

    public static function getModelLabel(): string
    {
        return __('site-builder/page.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('site-builder/general.pages');
    }

    public static function form(Form $form): Form
    {
        $templates = Template::where('is_active', true)->pluck('name', 'id')->toArray();
        $defaultTemplate = Template::getDefault();
        
        if ($defaultTemplate) {
            $templates = ['' => __('site-builder/page.template_options.use_default_template', [
                'template' => $defaultTemplate->name
            ])] + $templates;
        }
        
        return $form
            ->schema([
                Forms\Components\Tabs::make('Tabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make(__('site-builder/page.tabs.basic_info'))
                            ->schema([
                                Forms\Components\Group::make([
                                    Forms\Components\Select::make('template_id')
                                        ->label(__('site-builder/page.labels.template'))
                                        ->options($templates)
                                        ->searchable()
                                        ->reactive()
                                        ->afterStateUpdated(function (Forms\Set $set, $state) {
                                            $set('use_default_template', $state === '');
                                        })
                                        ->afterStateHydrated(function (Forms\Get $get, Forms\Set $set, $state) {
                                            if ($get('use_default_template')) {
                                                $set('template_id', '');
                                            }
                                        }),
                                    
                                    Forms\Components\Hidden::make('use_default_template')
                                        ->default(false),
                                ]),
                                
                                Forms\Components\TextInput::make('title')
                                    ->label(__('site-builder/general.title'))
                                    ->required()
                                    ->maxLength(255),
                                
                                Forms\Components\TextInput::make('slug')
                                    ->label(__('site-builder/general.slug'))
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(Page::class, 'slug', fn ($record) => $record)
                                    ->alphaDash(),
                                
                                Forms\Components\Textarea::make('description')
                                    ->label(__('site-builder/general.description'))
                                    ->maxLength(65535),
                                
                                Forms\Components\Toggle::make('is_homepage')
                                    ->label(__('site-builder/page.labels.is_homepage'))
                                    ->helperText(__('site-builder/page.help_text.is_homepage'))
                                    ->reactive()
                                    ->afterStateUpdated(function (Forms\Set $set, $state) {
                                        if ($state) {
                                            $set('is_active', true);
                                        }
                                    }),
                                
                                Forms\Components\Toggle::make('is_active')
                                    ->label(__('site-builder/general.is_active'))
                                    ->default(true)
                                    ->disabled(fn (Forms\Get $get) => $get('is_homepage')),
                                
                                Forms\Components\TextInput::make('sort_order')
                                    ->label(__('site-builder/general.sort_order'))
                                    ->numeric()
                                    ->default(0),
                            ])
                            ->columns(2),
                        
                        Forms\Components\Tabs\Tab::make(__('site-builder/page.tabs.seo'))
                            ->schema([
                                Forms\Components\TextInput::make('meta_tags.title')
                                    ->label(__('site-builder/page.seo.meta_title'))
                                    ->maxLength(60)
                                    ->helperText(__('site-builder/page.help_text.meta_title')),
                                
                                Forms\Components\Textarea::make('meta_tags.description')
                                    ->label(__('site-builder/page.seo.meta_description'))
                                    ->maxLength(160)
                                    ->rows(3)
                                    ->helperText(__('site-builder/page.help_text.meta_description')),
                                
                                Forms\Components\TextInput::make('meta_tags.keywords')
                                    ->label(__('site-builder/page.seo.meta_keywords'))
                                    ->helperText(__('site-builder/page.help_text.meta_keywords')),
                                
                                Forms\Components\Select::make('meta_tags.robots')
                                    ->label(__('site-builder/page.seo.robots'))
                                    ->options([
                                        'index, follow' => __('site-builder/page.seo.robots_options.index_follow'),
                                        'noindex, follow' => __('site-builder/page.seo.robots_options.noindex_follow'),
                                        'index, nofollow' => __('site-builder/page.seo.robots_options.index_nofollow'),
                                        'noindex, nofollow' => __('site-builder/page.seo.robots_options.noindex_nofollow'),
                                    ])
                                    ->default('index, follow'),
                                
                                Forms\Components\TextInput::make('meta_tags.og:title')
                                    ->label(__('site-builder/page.seo.og_title'))
                                    ->maxLength(60),
                                
                                Forms\Components\Textarea::make('meta_tags.og:description')
                                    ->label(__('site-builder/page.seo.og_description'))
                                    ->maxLength(160)
                                    ->rows(3),
                                
                                Forms\Components\FileUpload::make('meta_tags.og:image')
                                    ->label(__('site-builder/page.seo.og_image'))
                                    ->image()
                                    ->directory('pages/og-images'),
                            ])
                            ->columns(2),
                        
                        Forms\Components\Tabs\Tab::make(__('site-builder/general.translations'))
                            ->schema([
                                Forms\Components\Repeater::make('translations')
                                    ->label(__('site-builder/general.translations'))
                                    ->schema([
                                        Forms\Components\Select::make('locale')
                                            ->label(__('site-builder/translation.locale'))
                                            ->options([
                                                'ar' => __('site-builder/translation.locale_ar'),
                                                'fr' => __('site-builder/translation.locale_fr'),
                                                'es' => __('site-builder/translation.locale_es'),
                                                'de' => 'German',
                                                // Add more languages as needed
                                            ])
                                            ->required(),
                                        
                                        Forms\Components\TextInput::make('title')
                                            ->label(__('site-builder/page.translations.title'))
                                            ->required()
                                            ->maxLength(255),
                                        
                                        Forms\Components\Textarea::make('description')
                                            ->label(__('site-builder/page.translations.description'))
                                            ->maxLength(65535),
                                        
                                        Forms\Components\TextInput::make('meta_title')
                                            ->label(__('site-builder/page.translations.meta_title'))
                                            ->maxLength(60),
                                        
                                        Forms\Components\Textarea::make('meta_description')
                                            ->label(__('site-builder/page.translations.meta_description'))
                                            ->maxLength(160)
                                            ->rows(3),
                                    ])
                                    ->columns(2)
                                    ->itemLabel(fn (array $state): ?string => $state['locale'] ?? null),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('site-builder/general.title'))
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('slug')
                    ->label(__('site-builder/general.slug'))
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('template.name')
                    ->label(__('site-builder/page.labels.template'))
                    ->description(fn (Page $record): ?string => 
                        $record->use_default_template 
                            ? __('site-builder/page.using_default_template') 
                            : null
                    )
                    ->searchable(),
                
                Tables\Columns\IconColumn::make('is_homepage')
                    ->label(__('site-builder/page.labels.is_homepage'))
                    ->boolean(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('site-builder/general.is_active'))
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('sort_order')
                    ->label(__('site-builder/general.sort_order'))
                    ->numeric()
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
                Tables\Filters\SelectFilter::make('template_id')
                    ->label(__('site-builder/page.labels.template'))
                    ->options(Template::pluck('name', 'id')),
                Tables\Filters\TernaryFilter::make('use_default_template')
                    ->label(__('site-builder/page.labels.use_default_template')),
                Tables\Filters\TernaryFilter::make('is_homepage')
                    ->label(__('site-builder/page.labels.is_homepage')),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('site-builder/general.is_active')),
            ])
            ->actions([
                Tables\Actions\Action::make('use_default_template')
                    ->label(__('site-builder/page.actions.use_default_template'))
                    ->icon('heroicon-o-link')
                    ->color('success')
                    ->visible(fn (Page $record) => !$record->use_default_template)
                    ->requiresConfirmation()
                    ->action(function (Page $record) {
                        $record->useDefaultTemplate();
                        $record->save();
                        
                        Notification::make()
                            ->title(__('site-builder/page.notifications.now_using_default_template'))
                            ->success()
                            ->send();
                    }),
                
                Tables\Actions\EditAction::make(),
                
                Tables\Actions\Action::make('view')
                    ->label(__('site-builder/general.view'))
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => route('builder.page', ['slug' => $record->slug]))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate')
                        ->label(__('site-builder/general.activate'))
                        ->icon('heroicon-o-check-circle')
                        ->action(fn (Builder $query) => $query->update(['is_active' => true])),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label(__('site-builder/general.deactivate'))
                        ->icon('heroicon-o-x-circle')
                        ->action(function (Builder $query) {
                            // Don't deactivate the homepage
                            $query->where('is_homepage', false)->update(['is_active' => false]);
                        }),
                    Tables\Actions\BulkAction::make('use_default_template')
                        ->label(__('site-builder/page.actions.use_default_template_bulk'))
                        ->icon('heroicon-o-link')
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion()
                        ->action(function (Builder $query) {
                            $count = 0;
                            $pages = $query->get();
                            
                            foreach ($pages as $page) {
                                $page->useDefaultTemplate();
                                $page->save();
                                $count++;
                            }
                            
                            Notification::make()
                                ->title(__('site-builder/page.notifications.pages_using_default_template', ['count' => $count]))
                                ->success()
                                ->send();
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\BlocksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBuilderPages::route('/'),
            'create' => Pages\CreateBuilderPage::route('/create'),
            'edit' => Pages\EditBuilderPage::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}