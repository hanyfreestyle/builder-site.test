<?php


namespace App\Filament\Admin\Resources\Builder\BuilderTemplateResource\RelationManagers;

use App\Models\Builder\BlockType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BlockTypesRelationManager extends RelationManager {
    protected static string $relationship = 'blockTypes';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form {
        return $form
            ->schema([
                Forms\Components\Select::make('block_type_id')
                    ->label('Block Type')
                    ->options(BlockType::where('is_active', true)->pluck('name', 'id'))
                    ->required()
                    ->searchable(),

                Forms\Components\TagsInput::make('view_versions')
                    ->label('View Versions')
                    ->placeholder('Add view version')
                    ->default(['default'])
                    ->required(),

                Forms\Components\TextInput::make('default_view_version')
                    ->label('Default View Version')
                    ->default('default')
                    ->required(),

                Forms\Components\Toggle::make('is_enabled')
                    ->label('Enabled')
                    ->default(true),

                Forms\Components\TextInput::make('sort_order')
                    ->label('Sort Order')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public function table(Table $table): Table {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('pivot.view_versions')
                    ->label('View Versions')
                    ->formatStateUsing(fn($state) => implode(', ', json_decode($state) ?? ['default'])),

                Tables\Columns\TextColumn::make('pivot.default_view_version')
                    ->label('Default View'),

                Tables\Columns\IconColumn::make('pivot.is_enabled')
                    ->label('Enabled')
                    ->boolean(),

                Tables\Columns\TextColumn::make('pivot.sort_order')
                    ->label('Order')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->form(fn(Tables\Actions\AttachAction $action): array => [
                        Forms\Components\Select::make('recordId')
                            ->label('Block Type')
                            ->options(BlockType::where('is_active', true)->pluck('name', 'id'))
                            ->required()
                            ->searchable(),

                        Forms\Components\TagsInput::make('view_versions')
                            ->label('View Versions')
                            ->placeholder('Add view version')
                            ->default(['default'])
                            ->required(),

                        Forms\Components\TextInput::make('default_view_version')
                            ->label('Default View Version')
                            ->default('default')
                            ->required(),

                        Forms\Components\Toggle::make('is_enabled')
                            ->label('Enabled')
                            ->default(true),

                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0),
                    ])
                    ->mutateFormDataUsing(function (array $data): array {
                        // تأكد من تخزين view_versions كجيسون
                        if (isset($data['view_versions']) && is_array($data['view_versions'])) {
                            $data['view_versions'] = json_encode($data['view_versions']);
                        } elseif (isset($data['view_versions']) && is_string($data['view_versions'])) {
                            // إذا كان نصًا، تحقق مما إذا كان بالفعل JSON
                            if (!str_starts_with($data['view_versions'], '[')) {
                                $data['view_versions'] = json_encode([$data['view_versions']]);
                            }
                        } else {
                            $data['view_versions'] = json_encode(['default']);
                        }

                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateRecordDataUsing(function (array $data): array {
                        // تحويل view_versions من JSON إلى مصفوفة عند التعديل
                        if (isset($data['view_versions']) && is_string($data['view_versions'])) {
                            $data['view_versions'] = json_decode($data['view_versions'], true) ?? ['default'];
                        }

                        return $data;
                    })
                    ->mutateFormDataUsing(function (array $data): array {
                        // تحويل view_versions من مصفوفة إلى JSON عند الحفظ
                        if (isset($data['view_versions']) && is_array($data['view_versions'])) {
                            $data['view_versions'] = json_encode($data['view_versions']);
                        } elseif (isset($data['view_versions']) && is_string($data['view_versions'])) {
                            // إذا كان نصًا، تحقق مما إذا كان بالفعل JSON
                            if (!str_starts_with($data['view_versions'], '[')) {
                                $data['view_versions'] = json_encode([$data['view_versions']]);
                            }
                        } else {
                            $data['view_versions'] = json_encode(['default']);
                        }

                        return $data;
                    })
                    // تحديد الحقول يدويًا للتعديل
                    ->form(fn(Tables\Actions\EditAction $action): array => [
                        Forms\Components\TagsInput::make('view_versions')
                            ->label('View Versions')
                            ->placeholder('Add view version')
                            ->default(['default'])
                            ->required(),

                        Forms\Components\TextInput::make('default_view_version')
                            ->label('Default View Version')
                            ->default('default')
                            ->required(),

                        Forms\Components\Toggle::make('is_enabled')
                            ->label('Enabled')
                            ->default(true),

                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0),
                    ]),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
