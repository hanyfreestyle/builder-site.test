<?php

namespace App\Filament\Admin\Resources\SiteBuilder\BuilderTemplateLayoutResource;

use App\Enums\SiteBuilder\EnumsTemplateLayouts;
use App\Enums\Status\EnumsActive;
use App\FilamentCustom\Table\ImageColumnDef;
use App\Models\SiteBuilder\BuilderTemplate;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

trait TableBuilderTemplateLayout {
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function table(Table $table): Table {
        $thisLang = app()->getLocale();
        return $table
            ->columns([
                ImageColumnDef::make('photo')->width(60)->height(40),
                TextColumn::make('name.' . $thisLang)
                    ->label(__('default/lang.columns.name'))
                    ->searchable(),

                TextColumn::make('template_id')
                    ->label(__('site-builder/builder-template-layout.columns.template_id'))
                    ->formatStateUsing(fn($state, $record) => $record->template?->name[$thisLang] ?? '---'),

                TextColumn::make('slug')->label('Slug'),

                TextColumn::make('type')
                    ->label(__('site-builder/builder-template-layout.columns.type'))
                    ->formatStateUsing(fn(string $state) => EnumsTemplateLayouts::tryFrom($state)?->label()),

                IconColumn::make('is_active')->label(__('default/lang.columns.is_active'))->boolean(),
                IconColumn::make('is_default')->boolean()
                    ->label(__('site-builder/builder-template-layout.columns.is_default')),

            ])->filters([
                SelectFilter::make('is_active')
                    ->label(__('default/lang.enum.active.label'))
                    ->options(EnumsActive::options())
                    ->multiple()
                    ->searchable()
                    ->preload(),

                SelectFilter::make('type')
                    ->label(__('site-builder/builder-template-layout.columns.type'))
                    ->options(EnumsTemplateLayouts::options())
                    ->multiple()
                    ->searchable()
                    ->preload(),

                SelectFilter::make('template_id')
                    ->label(__('site-builder/builder-template-layout.columns.template_id'))
                    ->options(function () {
                        return BuilderTemplate::all()
                            ->pluck('name.ar', 'id'); // استخراج التسمية من JSON مباشرة
                    })
                    ->multiple()
                    ->searchable()
                    ->preload(),

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

