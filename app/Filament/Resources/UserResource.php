<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\CheckboxList;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    // Menambahkan schema untuk form
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->required()
                    ->email(),
                Forms\Components\TextInput::make('password')
                    ->label('Password')
                    ->password()
                    -> nullable()
                    ->minLength(8)
                    ->dehydrated(fn($state) => !empty($state)),
                Select::make('roles')
                    ->label('Role')
                    ->multiple() // Memungkinkan untuk memilih beberapa role
                    ->options(
                        Role::all()->pluck('name', 'id')->toArray()
                    )
                    ->reactive()
                    ->afterStateUpdated(fn(callable $set) => $set('permissions', [])), // Menghapus permissions ketika role diubah
                // Menambahkan field Permissions
                CheckboxList::make('permissions')
                    ->label('Permissions')
                    ->options(
                        Permission::all()->pluck('name', 'id')->toArray()
                    )
                    ->visible(fn($get) => !is_null($get('roles')) && count($get('roles')) > 0), // Pastikan roles sudah ada
            ]);
    }

    // Menambahkan tabel untuk user
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('roles.name')->label('Roles')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUsers::route('/'),
        ];
    }

    // Menyimpan perubahan role dan permissions saat menyimpan user
    public static function afterSave(User $user, array $data): void
    {
        // Menyinkronkan role pengguna
        $user->syncRoles($data['roles']);
        // Menyinkronkan permissions pengguna
        $user->syncPermissions($data['permissions']);
    }
}
