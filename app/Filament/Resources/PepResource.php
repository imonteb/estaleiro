<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PepResource\Pages;
use App\Models\Pep;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;

class PepResource extends Resource
{
    
    protected static ?string $model = Pep::class;

protected static ?string $navigationIcon = 'carbon-account';
    protected static ?string $navigationGroup = 'Centros de custo';
    protected static ?string $navigationBadge = '0';
    protected static ?string $navigationBadgeColor = 'info';
    protected static ?string $navigationSortGroup = 'Centros de custo';
    //protected static ?string $navigationSortIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 1;
    protected static ?string $modelLabel = 'PEP';
    protected static ?string $pluralModelLabel = 'PEPs';
    protected static ?string $navigationLabel = 'PEP';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('code')
                ->label('Código PEP')
                ->placeholder('P.000.000/000/1')
                ->required()
                ->maxLength(20)
                ->dehydrateStateUsing(fn($state) => strtoupper($state))
                ->rules(function (callable $get) {
                    return [
                        'required',
                        'regex:/^P\.\d{3}\.\d{3}\/\d{3}(\/\d+)?$/',
                        Rule::unique('peps', 'code')->ignore($get('id')),
                    ];
                })
                ->validationMessages([
                    'regex' => 'El formato debe ser P.000.000/000 o P.000.000/000/1',
                ])
                ->helperText('Incluye "P." al inicio. Ejemplo: P.016.002/008/1'),
            Forms\Components\TextInput::make('description')
                ->required()
                ->maxLength(255),

            Forms\Components\Toggle::make('active')
                ->required()
                ->default(true)
                ->helperText('Indica si la PEP está activa actualmente'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Código PEP')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\IconColumn::make('active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('active')
                    ->label('Activo')
                    ->trueLabel('Solo activos')
                    ->falseLabel('Solo inactivos'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPeps::route('/'),
            'create' => Pages\CreatePep::route('/create'),
            'edit' => Pages\EditPep::route('/{record}/edit'),
        ];
    }
}
