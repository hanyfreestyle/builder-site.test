<?php

namespace App\Filament\Admin\Resources;

use App\Models\Builder\BlockType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\BuilderBlockTypeResource\Pages;

class BuilderBlockTypeResource extends Resource
{
    protected static ?string $model = BlockType::class;

    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static ?string $navigationGroup = 'Site Builder';

    protected static ?int $navigationSort = 20;

    protected static ?string $navigationLabel = 'أنواع البلوكات';

    protected static ?string $modelLabel = 'نوع البلوك';

    protected static ?string $pluralModelLabel = 'أنواع البلوكات';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Tabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('المعلومات الأساسية')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('الاسم')
                                    ->required()
                                    ->maxLength(255),
                                
                                Forms\Components\TextInput::make('slug')
                                    ->label('الرابط')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(BlockType::class, 'slug', fn ($record) => $record)
                                    ->alphaDash(),
                                
                                Forms\Components\Textarea::make('description')
                                    ->label('الوصف')
                                    ->maxLength(65535)
                                    ->columnSpanFull(),
                                
                                Forms\Components\TextInput::make('icon')
                                    ->label('الأيقونة')
                                    ->maxLength(255)
                                    ->helperText('رمز FontAwesome أو رمز آخر، مثال: "fas fa-home"'),
                                
                                Forms\Components\TextInput::make('category')
                                    ->label('التصنيف')
                                    ->maxLength(255)
                                    ->helperText('فئة لتجميع البلوكات، مثل: "أساسي"، "وسائط"، "متقدم"'),
                                
                                Forms\Components\Toggle::make('is_active')
                                    ->label('نشط')
                                    ->default(true),
                                
                                Forms\Components\TextInput::make('sort_order')
                                    ->label('ترتيب العرض')
                                    ->numeric()
                                    ->default(0),
                            ])
                            ->columns(2),
                        
                        Forms\Components\Tabs\Tab::make('بنية البيانات')
                            ->schema([
                                Forms\Components\Repeater::make('schema')
                                    ->label('بنية البلوك')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label('اسم الحقل')
                                            ->required()
                                            ->maxLength(255)
                                            ->helperText('اسم الحقل، يستخدم كمفتاح في تخزين البيانات'),
                                        
                                        Forms\Components\TextInput::make('label')
                                            ->label('عنوان الحقل')
                                            ->required()
                                            ->maxLength(255)
                                            ->helperText('عنوان الحقل المرئي للمستخدم'),
                                        
                                        Forms\Components\Select::make('type')
                                            ->label('نوع الحقل')
                                            ->options([
                                                'text' => 'نص',
                                                'textarea' => 'نص طويل',
                                                'rich_text' => 'محرر نصوص متقدم',
                                                'select' => 'قائمة منسدلة',
                                                'checkbox' => 'صندوق اختيار',
                                                'radio' => 'أزرار راديو',
                                                'image' => 'رفع صورة',
                                                'file' => 'رفع ملف',
                                                'date' => 'منتقي التاريخ',
                                                'time' => 'منتقي الوقت',
                                                'color' => 'منتقي اللون',
                                                'icon' => 'منتقي الأيقونة',
                                                'link' => 'رابط (عنوان + نص)',
                                                'number' => 'رقم',
                                                'repeater' => 'قائمة عناصر متكررة',
                                            ])
                                            ->required(),
                                        
                                        Forms\Components\Toggle::make('required')
                                            ->label('مطلوب')
                                            ->default(false),
                                        
                                        Forms\Components\TextInput::make('placeholder')
                                            ->label('نص توضيحي')
                                            ->maxLength(255),
                                        
                                        Forms\Components\TextInput::make('default')
                                            ->label('القيمة الافتراضية')
                                            ->maxLength(255)
                                            ->helperText('القيمة الافتراضية للحقل (إن وجدت)'),
                                        
                                        Forms\Components\KeyValue::make('options')
                                            ->label('الخيارات')
                                            ->helperText('أزواج المفتاح-القيمة للقوائم المنسدلة وأزرار الراديو وصناديق الاختيار')
                                            ->visible(fn (Forms\Get $get) => in_array($get('type'), ['select', 'radio', 'checkbox'])),
                                        
                                        Forms\Components\Toggle::make('translatable')
                                            ->label('قابل للترجمة')
                                            ->default(true)
                                            ->helperText('هل يمكن ترجمة هذا الحقل'),
                                        
                                        Forms\Components\TextInput::make('help')
                                            ->label('نص المساعدة')
                                            ->maxLength(255)
                                            ->helperText('نص المساعدة الذي سيتم عرضه مع الحقل'),
                                        
                                        Forms\Components\Select::make('width')
                                            ->label('عرض الحقل')
                                            ->options([
                                                '1/2' => 'نصف عرض',
                                                '1/3' => 'ثلث عرض',
                                                '2/3' => 'ثلثي عرض',
                                                'full' => 'عرض كامل',
                                            ])
                                            ->default('full')
                                            ->required(),
                                    ])
                                    ->columns(2)
                                    ->columnSpanFull()
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('البيانات الافتراضية')
                            ->schema([
                                Forms\Components\KeyValue::make('default_data')
                                    ->label('القيم الافتراضية للحقول')
                                    ->helperText('تعيين القيم الافتراضية لحقول البلوك'),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('slug')
                    ->label('الرابط')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('category')
                    ->label('التصنيف')
                    ->searchable(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('الترتيب')
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
            ])
            ->actions([
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBuilderBlockTypes::route('/'),
            'create' => Pages\CreateBuilderBlockType::route('/create'),
            'edit' => Pages\EditBuilderBlockType::route('/{record}/edit'),
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