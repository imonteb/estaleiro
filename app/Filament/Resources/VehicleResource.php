<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleResource\Pages;
use App\Filament\Resources\VehicleResource\Pages\VehicleViewPage;
use App\Filament\Resources\VehicleResource\RelationManagers;
use App\Models\Vehicle;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class VehicleResource extends Resource
{
    
    protected static ?string $model = Vehicle::class;

    //protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Veículos';
    protected static ?string $navigationBadge = '0';
    protected static ?string $navigationBadgeColor = 'info';
    protected static ?string $navigationSortGroup = 'Veículos';
    //protected static ?string $navigationSortIcon = 'heroicon-o-truck';
    protected static ?int $navigationSort = 1;
    protected static ?string $modelLabel = 'Veículo';
    protected static ?string $pluralModelLabel = 'Veículos';
    protected static ?string $navigationLabel = 'Veículos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('car_plate')
                    ->label('Matrícula')
                    ->required()
                    ->placeholder('Ej: BC-92-UU')
                    ->maxLength(9)
                    ->rules([
                        'regex:/^[A-Z0-9]{2}-[A-Z0-9]{2}-[A-Z0-9]{2}$/i',
                    ])
                    ->dehydrateStateUsing(fn($state) => strtoupper($state)),


                Forms\Components\Select::make('vehicle_brand_id')
                    ->relationship('vehicleBrand', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->label('Vehicle Brand')
                    ->placeholder('Select a vehicle brand')
                    ->reactive()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                    ]),
                Forms\Components\TextInput::make('model')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('fuel_card_number')
                    ->required()
                    ->unique(Vehicle::class, 'fuel_card_number', ignoreRecord: true)
                    ->maxLength(25),
                Forms\Components\TextInput::make('fuel_card_pin')
                    ->required()
                    ->numeric()
                    ->maxLength(4),
                Forms\Components\TextInput::make('insurance_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('insurance_validity_date'),
                Forms\Components\DatePicker::make('last_vehicle_inspection_date'),
                Forms\Components\TextInput::make('vehicle_condition')
                    ->maxLength(255),
                Forms\Components\Toggle::make('assigned')
                    ->label('Assigned')
                    ->default(false)
                    ->required(),
                Forms\Components\FileUpload::make('image_url')
                    ->label('Vehicle Image')
                    ->image()
                    ->required()
                    ->maxSize(4096) // 4MB
                    ->disk('public')
                    ->directory('vehicles')
                    ->visibility('public')
                    ->preserveFilenames()
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('16:9')
                    ->imageResizeTargetWidth('1920')
                    ->imageResizeTargetHeight('1080'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('car_plate')
                    ->searchable(),
                Tables\Columns\TextColumn::make('vehicleBrand.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('model')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fuel_card_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fuel_card_pin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('insurance_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('insurance_validity_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_vehicle_inspection_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vehicle_condition')
                    ->searchable(),
                Tables\Columns\IconColumn::make('assigned')
                    ->boolean(),
                Tables\Columns\ImageColumn::make('image_url'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVehicles::route('/'),
            'create' => Pages\CreateVehicle::route('/create'),
            'edit' => Pages\EditVehicle::route('/{record}/edit'),
            'view' => VehicleViewPage::route('/view'),


        ];
    }

    public static function getNavigationItems(): array
    {
        return [
            NavigationItem::make()
                ->label('Visualizar Veículos')
                ->icon('heroicon-o-eye')
                ->url(static::getUrl('view')) // ou 'lista' se for outra página
                ->badge(Vehicle::count()) // contador de registros
                ->group('Veículos'),
            NavigationItem::make()
                ->label('Veículos')
                ->icon('heroicon-o-clipboard-document-list')
                ->url(static::getUrl('index'))
                ->group('Veículos'),
        ];
    }




    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getNavigationBadgeColor(): ?string
    {
        return 'info';
    }
}
