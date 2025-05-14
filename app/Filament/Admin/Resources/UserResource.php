<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\FilamentCustom\Table\CreatedDates;
use App\Models\User;
use App\Traits\Admin\Helper\SmartResourceTrait;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Support\Enums\FontWeight;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section as InfolistSection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;


class UserResource extends Resource implements HasShieldPermissions {
    use SmartResourceTrait;

    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
//    protected static ?int $navigationSort = 8902;

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getPermissionPrefixes(): array {
        return static::filterPermissions(
            skipKeys: ['slug'],
            keepKeys: [],
        );
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getNavigationGroup(): ?string {
        return __('default/users.navigation_group');
    }

    public static function getModelLabel(): string {
        return __('default/users.ModelLabel');
    }

    public static function getPluralModelLabel(): string {
        return __('default/users.PluralModelLabel');
    }

    public static function getNavigationLabel(): string {
        return __('default/users.NavigationLabel');
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function form(Form $form): Form {
        return $form->schema([
            Group::make()->schema([
                Section::make(__('default/users.card.User_Information'))->schema([

                    TextInput::make('name')
                        ->label(__('default/users.name'))
                        ->maxLength(255)
                        ->required(),

                    TextInput::make('email')
                        ->label(__('default/users.email'))
                        ->email()
                        ->unique(User::class, 'email', ignoreRecord: true)
                        ->maxLength(255)
                        ->required(),

//                    PhoneInput::make('phone')
//                        ->label(__('default/users.phone'))
////                        ->countryStatePath('phone_code')
//                        ->defaultCountry('EG')
//                        ->unique('users', 'phone', ignoreRecord: true)
//
//                        ->nullable(),

                    TextInput::make('password')
                        ->label(__('default/users.password'))
                        ->password()
                        ->default(fn(string $context) => $context === 'edit' ? '' : null)
                        ->required(fn($record) => !$record) // مطلوب فقط عند الإنشاء
                        ->dehydrateStateUsing(fn($state) => !empty($state) ? Hash::make($state) : null) // تشفير كلمة المرور قبل الحفظ
                        ->dehydrated(fn($state) => !empty($state)) // يمنع إعادة تعيين كلمة المرور إذا لم يتم إدخالها
                        ->autocomplete('new-password')
                        ->maxLength(255),

                    Toggle::make('is_active')
                        ->label(__('default/lang.columns.is_active'))
                        ->default(false)
                        ->required(),


                ])->columns(2),

                Section::make(__('default/users.card.Roles'))->schema([
                    Select::make('role')
                        ->hiddenLabel()
                        ->relationship('roles', 'name')
                        ->preload()
                        ->multiple()
                        ->columnSpanFull()
                        ->required(),
                ])->columns(2),

            ])->columnSpan(2),

            Group::make()->schema([
                Section::make(__('default/users.card.User_Information'))->schema([
//                    WebpImageUpload::make('avatar_url')
//                        ->uploadDirectory('admin-avatar')
//                        ->resize(300, 300, 90)
//                        ->nullable(),
                ])->columns(1),

            ])->columnSpan(1),

        ])->columns(3);
    }


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function table(Table $table): Table {
         return $table->columns([
            ImageColumn::make('avatar_url')
                ->disk('root_folder')
                ->label('')
                ->searchable()
                ->circular()
                ->grow(false)
                ->getStateUsing(fn($record) => $record->avatar_url
                    ? $record->avatar_url
                    : "https://ui-avatars.com/api/?name=" . urlencode($record->name)),

            TextColumn::make('name')
                ->label(__('default/users.name'))
                ->weight(FontWeight::Bold)
                ->searchable(),

            TextColumn::make('phone')
                ->label(__('default/users.phone'))
                ->icon('heroicon-m-phone')
                ->weight(FontWeight::Bold)
                ->extraCellAttributes(fn() => rtlCell('en'))
                ->searchable(),

            TextColumn::make('email')
                ->label(__('default/users.email'))
                ->icon('heroicon-m-envelope')
                ->weight(FontWeight::Bold)
                ->searchable(),

            TextColumn::make('roles.name')
                ->label(__('default/users.roles'))
                ->formatStateUsing(fn($state): string => Str::headline($state))
                ->icon('heroicon-o-shield-check'),

            IconColumn::make('is_active')
                ->label(__('default/users.is_active'))
                ->boolean(),

            TextColumn::make('email_verified_at')
                ->label(__('default/lang.columns.email_verified_at'))
                ->date()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            ...CreatedDates::make()->toggleable(true)->getColumns(),

        ])->filters([
            TrashedFilter::make(),
            DateRangeFilter::make('created_at')->label(__('default/lang.columns.created_at')),

            SelectFilter::make('roles')
                ->label(__('default/users.roles'))
                ->relationship('roles', 'name')
                ->multiple()
                ->preload(),
        ])
            ->persistFiltersInSession()
            ->persistSearchInSession()
            ->actions([
                Tables\Actions\EditAction::make()->iconButton()
                    ->hidden(fn($record) => auth()->user()->id != 1 && $record->id === 1 or $record->trashed()),
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->visible(fn($record) => $record->id !== 1),
                Tables\Actions\ViewAction::make()->hidden(fn($record) => $record->trashed())->iconButton(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),

            ])
            ->headerActions([

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getRelations(): array {
        return [
            //
        ];
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getPages(): array {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
//            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function infolist(Infolist $infolist): Infolist {
        return $infolist->schema([

            InfolistSection::make(__('default/users.card.User_Information'))->schema([
                TextEntry::make('name')
                    ->icon('heroicon-m-user')
                    ->size(TextEntry\TextEntrySize::Large)
                    ->label(__('default/users.name')),

                TextEntry::make('email')
                    ->icon('heroicon-m-envelope')
                    ->size(TextEntry\TextEntrySize::Large)
                    ->label(__('default/users.email')),

                TextEntry::make('phone')
                    ->icon('heroicon-m-phone')
                    ->size(TextEntry\TextEntrySize::Large)
                    ->label(__('default/users.phone')),

            ])->columns('2'),
        ]);
    }

}
