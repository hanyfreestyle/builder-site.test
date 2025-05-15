<?php

namespace App\Filament\Admin\Resources\SiteBuilder\BuilderTemplateResource;

use App\FilamentCustom\Table\ImageColumnDef;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

trait TableBuilderTemplate {
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function table(Table $table): Table {
        $thisLang = app()->getLocale();

        return $table
            ->columns([
                ImageColumnDef::make('photo')->width(60)->height(40),
                TextColumn::make('name.' . $thisLang)->label(__('default/lang.columns.name'))->searchable(),
                TextColumn::make('defaultHeader.slug')->label(__('site-builder/builder-template.columns.defaultHeader')),
                TextColumn::make('defaultFooter.slug')->label(__('site-builder/builder-template.columns.defaultFooter')),
                IconColumn::make('is_active')->label(__('default/lang.columns.is_active'))->boolean(),
            ])->filters([

            ])
            ->persistFiltersInSession()
            ->persistSearchInSession()
            ->persistSortInSession()
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
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

