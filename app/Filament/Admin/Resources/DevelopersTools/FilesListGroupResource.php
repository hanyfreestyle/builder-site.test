<?php

namespace App\Filament\Admin\Resources\DevelopersTools;

use App\Filament\Admin\Resources\DevelopersTools\FilesListGroupResource\Pages;


use App\Models\DevelopersTools\FilesListGroup;
use App\Traits\Admin\Helper\SmartResourceTrait;
use Filament\Forms\Components\Group;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;


class FilesListGroupResource extends Resource {
    use SmartResourceTrait;

    protected static ?string $model = FilesListGroup::class;
    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

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
        return __('developers-tools/fileList.category.NavigationLabel');
    }

    public static function getModelLabel(): string {
        return __('developers-tools/fileList.category.ModelLabel');
    }

    public static function getPluralModelLabel(): string {
        return __('developers-tools/fileList.category.PluralModelLabel');
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function form(Form $form): Form {
        return $form
            ->schema([
                Group::make()->schema([

                    TextInput::make('name')
                        ->label(__('default/lang.tableHeader.name'))
                        ->extraAttributes(['dir' => 'ltr', 'style' => 'text-align: left',])
                        ->maxLength(200)
                        ->columnSpanFull()
                        ->required(),


                ])->columnSpanFull()->columns(4),
            ]);
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function table(Table $table): Table {
        return $table
            ->columns([
                TextInputColumn::make('name'),
                ToggleColumn::make('is_active')
                    ->label('Active'),
            ])
            ->filters([


            ])
            ->persistFiltersInSession()
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
//                Tables\Actions\DeleteAction::make()->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('position')
            ->defaultSort('position');
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
            'index' => Pages\ListFilesListGroups::route('/'),
//            'create' => Pages\CreateFilesListGroup::route('/create'),
            'view' => Pages\ViewFilesListGroup::route('/{record}'),
            'edit' => Pages\EditFilesListGroup::route('/{record}/edit'),
        ];
    }


    public static function getTableRecordUrl($record): ?string {
        return static::getUrl('edit', ['record' => $record->getKey()]);
    }


}
