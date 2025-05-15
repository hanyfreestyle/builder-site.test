<?php

namespace App\Filament\Admin\Resources\SiteBuilder\BuilderBlockResource\Pages;

use App\Filament\Admin\Resources\SiteBuilder\BuilderBlockResource;
use App\Models\SiteBuilder\TemplateBlockDefinition;
use Filament\Resources\Pages\CreateRecord;
use App\Services\BuilderBlockFormService;
use Filament\Forms;

use Filament\Forms\Form;

class CreateBuilderBlock extends CreateRecord {

    protected static string $resource = BuilderBlockResource::class;


    public function form(Form $form): Form {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->label('نوع البلوك')
                    ->options(TemplateBlockDefinition::pluck('type', 'type'))
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Forms\Set $set) {
                        $set('dynamic_fields', null); // Reset dynamic fields on type change
                    }),

                Forms\Components\Placeholder::make('note')
                    ->content('اختر نوع البلوك لعرض النموذج الديناميكي.')
                    ->hidden(fn(Forms\Get $get) => !empty($get('type'))),

                // Dynamic fields based on type
                Forms\Components\Group::make()
                    ->schema(function (Forms\Get $get) {
                        $type = $get('type');

                        if (!$type) return [];

                        $definition = TemplateBlockDefinition::where('type', $type)->first();

                        return $definition
                            ? BuilderBlockFormService::generateFormFields($definition->schema)
                            : [
                                Forms\Components\TextInput::make('error')
                                    ->label('خطأ')
                                    ->default('النوع غير صحيح!')
                            ];
                    })
                    ->hidden(fn(Forms\Get $get) => !$get('type'))
                    ->key('dynamic_fields'),

                Forms\Components\Select::make('page_id')
                    ->relationship('page', 'slug')
                    ->required()
                    ->hidden(fn(Forms\Get $get) => !$get('type')),

                Forms\Components\Hidden::make('type'),
                Forms\Components\Hidden::make('position')->default(0),
            ]);
    }


}
