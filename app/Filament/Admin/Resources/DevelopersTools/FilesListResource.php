<?php

namespace App\Filament\Admin\Resources\DevelopersTools;

use App\Filament\Admin\Resources\DevelopersTools\FilesListResource\Pages;
use App\Models\DevelopersTools\FilesList;
use App\Traits\Admin\Helper\SmartResourceTrait;
use Filament\Forms\Components\Group;
use Filament\Resources\Resource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Illuminate\Support\Str;


class FilesListResource extends Resource {
    use SmartResourceTrait;

    protected static ?string $model = FilesList::class;
    protected static ?string $navigationIcon = 'heroicon-o-code-bracket';

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function canAccess(): bool {
        return isLocalSuperAdmin();
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getNavigationGroup(): ?string {
        return __('developers-tools/fileList.navigation_group');
    }

    public static function getNavigationLabel(): string {
        return __('developers-tools/fileList.file.NavigationLabel');
    }

    public static function getModelLabel(): string {
        return __('developers-tools/fileList.file.ModelLabel');
    }

    public static function getPluralModelLabel(): string {
        return __('developers-tools/fileList.file.PluralModelLabel');
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function form(Form $form): Form {
        return $form
            ->schema([
                Group::make()->schema([

                    Group::make()->schema([
                        Forms\Components\Select::make('group_id')
                            ->hiddenLabel()
                            ->relationship('group', 'name')
                            ->required(),

                    ])->columnSpan(1)->columns(1),

                    Group::make()->schema([
                        Toggle::make('is_active')
                            ->label(__('default/lang.tableHeader.active'))
                            ->default(1)
                            ->required(),
                        Toggle::make('copy')
                            ->default(1)
                            ->required(),
                        Toggle::make('delete')
                            ->default(0)
                            ->required(),
                        Toggle::make('import')
                            ->required()
                            ->default(0),

                    ])->columnSpan(2)->columns(4),


                ])->columnSpanFull()->columns(3),

                Group::make()->schema([

                    TextInput::make('title')
                        ->label(__('default/lang.tableHeader.name'))
                        ->extraAttributes(['dir' => 'ltr', 'style' => 'text-align: left',])
                        ->maxLength(200)
                        ->required(),

                    TextInput::make('cat_id')
                        ->label(__('Cat ID'))
                        ->unique(FilesList::class, 'cat_id', ignoreRecord: true)
                        ->dehydrated()
                        ->live(onBlur: true)
                        ->maxLength(255)
                        ->reactive()
                        ->afterStateUpdated(fn($state, $set) => $set('cat_id', Str::slug($state)))
                        ->extraAttributes(['dir' => 'ltr', 'style' => 'text-align: left',])
                        ->required(),

                    TextInput::make('is_exist')
                        ->label(__('Is Exist'))
                        ->unique(FilesList::class, 'is_exist', ignoreRecord: true)
                        ->extraAttributes(['dir' => 'ltr', 'style' => 'text-align: left',])
                        ->maxLength(200)
                        ->columnSpan(2)
                        ->required(),

                ])->columnSpanFull()->columns(4),

                Group::make()->schema([
                    Textarea::make('files_text')
                        ->label('قائمة الملفات')
                        ->rows(20)
                        ->extraAttributes(['dir' => 'ltr', 'style' => 'font-size: 60px!important;',])
                        ->extraInputAttributes([
                            'style' => 'font-size: 18px !important; line-height: 30px!important;',
                        ])
                        ->placeholder("أدخل كل مسار في سطر جديد")
                        ->nullable(),

                    Textarea::make('folders_text')
                        ->label('المجلدات')
                        ->rows(20)
                        ->extraAttributes(['dir' => 'ltr', 'style' => 'font-size: 60px!important;',])
                        ->extraInputAttributes([
                            'style' => 'font-size: 18px !important; line-height: 30px!important;',
                        ])
                        ->placeholder("أدخل كل مسار في سطر جديد")
                        ->nullable(),

                ])->columnSpanFull()->columns(2),

            ]);
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function table(Table $table): Table {

        return $table
            ->columns([

                TextInputColumn::make('title')
                    ->extraAttributes(['dir' => 'ltr', 'style' => 'text-align: left',]),

                TextColumn::make('cat_id')
                    ->searchable(),


                TextColumn::make('group.name')
                    ->sortable(),

                ToggleColumn::make('is_active')
                    ->label('Active'),

                ToggleColumn::make('copy'),
                ToggleColumn::make('delete'),
                ToggleColumn::make('import'),


            ])
            ->filters([
                SelectFilter::make('group_id')
                    ->relationship('group', 'name')

            ])
            ->persistFiltersInSession()
            ->actions([

//                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
//            ->recordUrl(fn($record) => static::getTableRecordUrl($record))
            ->reorderable('position')
            ->defaultSort('id', 'desc');
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||

    public static function getRelations(): array {
        return [
            //
        ];
    }

    public static function getPages(): array {
        return [
            'index' => Pages\ListFilesLists::route('/'),
            'create' => Pages\CreateFilesList::route('/create'),
//            'view' => Pages\ViewFilesList::route('/{record}'),
            'edit' => Pages\EditFilesList::route('/{record}/edit'),
        ];
    }


    public static function getTableRecordUrl($record): ?string {
        return static::getUrl('edit', ['record' => $record->getKey()]);
    }


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function infolist(Infolist $infolist): Infolist {
        return $infolist
            ->schema([
                TextEntry::make('id'),
                TextEntry::make('name')
                    ->label(__('default/lang.tableHeader.name')),

                Group::make()->schema([
                    IconEntry::make('is_active')
                        ->label(__('default/lang.tableHeader.active'))
                        ->boolean(),

                    IconEntry::make('is_feature')
                        ->label(__('default/lang.tableHeader.feature'))
                        ->boolean(),

                    IconEntry::make('is_archived')
                        ->label(__('default/lang.tableHeader.archived'))
                        ->boolean(),
                ])->columnSpanFull()->columns(3),

            ]);
    }


}
