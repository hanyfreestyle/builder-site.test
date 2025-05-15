<?php
namespace App\Filament\Admin\Resources\SiteBuilder\BuilderTemplateLayoutResource;

use App\FilamentCustom\Table\CreatedDates;
use App\FilamentCustom\Table\ImageColumnDef;
use App\FilamentCustom\Table\TranslationTextColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

trait TableBuilderTemplateLayout{
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function table(Table $table): Table {
        return $table
//            ->modifyQueryUsing(fn($query) => $query->withCount('posts'))
            ->columns([
                ImageColumnDef::make('photo')->width(60)->height(40),

                IconColumn::make('is_active')->label(__('default/lang.columns.is_active'))->boolean(),
//                TextColumn::make('posts_count')
//                    ->label(__('posts_count'))
//                    ->size(TextColumnSize::Large)
//                    ->badge()
//                    ->sortable(),

            ])->filters([

            ])
            ->persistFiltersInSession()
            ->persistSearchInSession()
            ->persistSortInSession()
            ->actions([

                EditAction::make(),
                DeleteAction::make(),
//                DeleteAction::make()
//                    ->before(function ($record) {
//                        if ($record->posts()->withoutTrashed()->count() > 0) {
//                            Notification::make()
//                                ->title(__('filament/Menu/product.category.err_delete.title'))
//                                ->danger()
//                                ->body(__('filament/Menu/product.category.err_delete.body'))
//                                ->send();
//                            return false;
//                        }
//                    }),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->recordUrl(fn($record) => static::getTableRecordUrl($record))
            ->defaultSort('id', 'desc');
    }
}

