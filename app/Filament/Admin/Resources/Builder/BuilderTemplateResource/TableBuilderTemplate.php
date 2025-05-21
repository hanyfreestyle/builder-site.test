<?php

namespace App\Filament\Admin\Resources\Builder\BuilderTemplateResource;

use App\FilamentCustom\Table\CreatedDates;
use App\FilamentCustom\Table\ImageColumnDef;
use App\Models\Builder\Template;
use App\Services\Builder\TemplateService;
use Filament\Notifications\Notification;
use Filament\Tables\Table;
use Filament\Tables;

trait TableBuilderTemplate {
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function table(Table $table): Table {
        $thisLang = app()->getLocale();

        return $table
            ->columns([
                ImageColumnDef::make('photo_thumbnail')->width(60)->height(80),

                Tables\Columns\TextColumn::make('name.' . $thisLang)
                    ->label(__('site-builder/general.name'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label(__('site-builder/general.slug'))
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('site-builder/general.is_active'))
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_default')
                    ->label(__('site-builder/general.is_default'))
                    ->boolean()
                    ->sortable(),

                ...CreatedDates::make()->toggleable(true)->getColumns(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('site-builder/general.is_active'))
                    ->searchable()
                    ->preload(),

                Tables\Filters\TrashedFilter::make()->searchable()->preload(),
            ])
            ->actions([
                Tables\Actions\Action::make('set_default')
                    ->hidden(fn($record) => $record->trashed())
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
                    ->hidden(fn($record) => $record->trashed())
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

                Tables\Actions\EditAction::make()->hidden(fn($record) => $record->trashed()),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->persistFiltersInSession()
            ->persistSearchInSession()
            ->persistSortInSession()
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }
}

