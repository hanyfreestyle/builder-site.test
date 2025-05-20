<?php

namespace App\Filament\Admin\Resources\Builder;



use App\Filament\Admin\Resources\Builder\BuilderBlockResource\TableBuilderBlockType;
use App\Models\Builder\Block;
use App\Services\Builder\Form\BuilderBlockResourceForm;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\BuilderBlockResource\Pages;

class BuilderBlockResource extends Resource {
    use TableBuilderBlockType;


    protected static ?string $model = Block::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Site Builder';
    protected static ?int $navigationSort = 25;
    protected static ?string $navigationLabel = 'blocks';
    protected static ?string $modelLabel = 'block';
    protected static ?string $pluralModelLabel = 'blocks';

    public static function getNavigationLabel(): string {
        return __('site-builder/general.blocks');
    }

    public static function getModelLabel(): string {
        return __('site-builder/block.singular');
    }

    public static function getPluralModelLabel(): string {
        return __('site-builder/general.blocks');
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function form(Form $form): Form {
        return $form
            ->schema([
                // Basic Information Section
                BuilderBlockResourceForm::BasicInformationSection(),

                // Content Section (Conditional based on blockType)
                BuilderBlockResourceForm::BlocksContentSection(),

                // Translations Section
                BuilderBlockResourceForm::BlocksTranslationsSection(),

            ]);
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
            'index' => Pages\ListBuilderBlocks::route('/'),
            'create' => Pages\CreateBuilderBlock::route('/create'),
            'edit' => Pages\EditBuilderBlock::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
