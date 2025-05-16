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

class BuilderPageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Site Builder';

    protected static ?int $navigationSort = 30;

    protected static ?string $navigationLabel = 'Pages';

    protected static ?string $modelLabel = 'Page';

    protected static ?string $pluralModelLabel = 'Pages';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Tabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Basic Information')
                            ->schema([
                                Forms\Components\Select::make('template_id')
                                    ->label('Template')
                                    ->options(Template::where('is_active', true)->pluck('name', 'id'))
                                    ->required()
                                    ->searchable(),
                                
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->maxLength(255),
                                
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(Page::class, 'slug', fn ($record) => $record)
                                    ->alphaDash(),
                                
                                Forms\Components\Textarea::make('description')
                                    ->maxLength(65535),
                                
                                Forms\Components\Toggle::make('is_homepage')
                                    ->label('Is Homepage')
                                    ->helperText('Only one page can be set as homepage')
                                    ->reactive()
                                    ->afterStateUpdated(function (Forms\Set $set, $state) {
                                        if ($state) {
                                            $set('is_active', true);
                                        }
                                    }),
                                
                                Forms\Components\Toggle::make('is_active')
                                    ->default(true)
                                    ->disabled(fn (Forms\Get $get) => $get('is_homepage')),
                                
                                Forms\Components\TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0),
                            ])
                            ->columns(2),
                        
                        Forms\Components\Tabs\Tab::make('SEO')
                            ->schema([
                                Forms\Components\TextInput::make('meta_tags.title')
                                    ->label('Meta Title')
                                    ->maxLength(60)
                                    ->helperText('Optimal length: 50-60 characters'),
                                
                                Forms\Components\Textarea::make('meta_tags.description')
                                    ->label('Meta Description')
                                    ->maxLength(160)
                                    ->rows(3)
                                    ->helperText('Optimal length: 150-160 characters'),
                                
                                Forms\Components\TextInput::make('meta_tags.keywords')
                                    ->label('Meta Keywords')
                                    ->helperText('Comma-separated keywords'),
                                
                                Forms\Components\Select::make('meta_tags.robots')
                                    ->label('Robots')
                                    ->options([
                                        'index, follow' => 'Index, Follow',
                                        'noindex, follow' => 'No Index, Follow',
                                        'index, nofollow' => 'Index, No Follow',
                                        'noindex, nofollow' => 'No Index, No Follow',
                                    ])
                                    ->default('index, follow'),
                                
                                Forms\Components\TextInput::make('meta_tags.og:title')
                                    ->label('OG Title')
                                    ->maxLength(60),
                                
                                Forms\Components\Textarea::make('meta_tags.og:description')
                                    ->label('OG Description')
                                    ->maxLength(160)
                                    ->rows(3),
                                
                                Forms\Components\FileUpload::make('meta_tags.og:image')
                                    ->label('OG Image')
                                    ->image()
                                    ->directory('pages/og-images'),
                            ])
                            ->columns(2),
                        
                        Forms\Components\Tabs\Tab::make('Translations')
                            ->schema([
                                Forms\Components\Repeater::make('translations')
                                    ->label('Translations')
                                    ->schema([
                                        Forms\Components\Select::make('locale')
                                            ->label('Language')
                                            ->options([
                                                'ar' => 'Arabic',
                                                'fr' => 'French',
                                                'es' => 'Spanish',
                                                'de' => 'German',
                                                // Add more languages as needed
                                            ])
                                            ->required(),
                                        
                                        Forms\Components\TextInput::make('title')
                                            ->label('Translated Title')
                                            ->required()
                                            ->maxLength(255),
                                        
                                        Forms\Components\Textarea::make('description')
                                            ->label('Translated Description')
                                            ->maxLength(65535),
                                        
                                        Forms\Components\TextInput::make('meta_title')
                                            ->label('Translated Meta Title')
                                            ->maxLength(60),
                                        
                                        Forms\Components\Textarea::make('meta_description')
                                            ->label('Translated Meta Description')
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
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('template.name')
                    ->label('Template')
                    ->searchable(),
                
                Tables\Columns\IconColumn::make('is_homepage')
                    ->label('Homepage')
                    ->boolean(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('template_id')
                    ->label('Template')
                    ->options(Template::pluck('name', 'id')),
                Tables\Filters\TernaryFilter::make('is_homepage')
                    ->label('Homepage'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('view')
                    ->label('View')
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
                        ->label('Activate')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn (Builder $query) => $query->update(['is_active' => true])),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate')
                        ->icon('heroicon-o-x-circle')
                        ->action(function (Builder $query) {
                            // Don't deactivate the homepage
                            $query->where('is_homepage', false)->update(['is_active' => false]);
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