<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Department;
use App\Models\User;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Training Resources';

    protected static ?string $navigationLabel = 'Trainees';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
                ->schema([
                    TextInput::make('name'),
                    TextInput::make('job_title')->required(),
                    TextInput::make('email'),
                    Select::make('department_id')
                            ->preload()
                            ->options(Department::pluck('name','id'))->required(),
                    Select::make('site')
                            ->options([
                                'Dokolo' => 'Dokolo',
                                'Head Office' => 'Head Office',
                                'Kampala' => 'Kampala',
                                'Kiambere' => 'Kiambere',
                                'Nyongoro' => 'Nyongoro',
                                '7 Forks' => '7 Forks',
                            ]),
                    TextInput::make('phone_number')
                            ->label('phone no')->required(),
                    Select::make('country')
                            ->label('Country')
                            ->options([
                                'KE' => 'Kenya',
                                'UG' => 'Uganda',
                                'TZ' => 'Tanzania',
                            ])
                            ->required(),
                    TextInput::make('password')
                            ->password()
                            ->required()
                            ->visibleOn('create'),
                    Select::make('role.name')
                            ->relationship('roles', 'name')
                            ->label('Has Roles')
                            ->multiple()
                            ->preload(),
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable()->Searchable(),
                Tables\Columns\TextColumn::make('name')->sortable()->Searchable(),
                Tables\Columns\TextColumn::make('job_title')->sortable()->Searchable(),
                Tables\Columns\TextColumn::make('site')->sortable()->Searchable(),
                Tables\Columns\TextColumn::make('department.name')->sortable()->Searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->visible(fn (User $record) => auth()->id() != $record->id),
            ])
            ->bulkActions([
                //Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }    
}
