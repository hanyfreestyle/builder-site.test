<?php

namespace App\Filament\Admin\Resources\Builder\BuilderTemplateResource;

use App\Enums\SiteBuilder\BlockCategory;
use App\FilamentCustom\Table\CreatedDates;
use App\Models\Builder\Template;
use App\Services\Builder\TemplateService;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Filament\Tables;

trait TableBuilderTemplate {
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
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
}

