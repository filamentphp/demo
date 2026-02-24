<?php

namespace App\Filament\Resources\Shop\Orders\Schemas;

use App\Enums\CurrencyCode;
use App\Enums\OrderStatus;
use App\Filament\Resources\Shop\Products\ProductResource;
use App\Forms\Components\AddressForm;
use App\Models\Shop\Order;
use App\Models\Shop\Product;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make()
                            ->schema(static::getDetailsComponents())
                            ->columns(2),

                        Section::make('Order items')
                            ->afterHeader([
                                Action::make('reset')
                                    ->modalHeading('Are you sure?')
                                    ->modalDescription('All existing items will be removed from the order.')
                                    ->requiresConfirmation()
                                    ->color('danger')
                                    ->action(fn (Set $set) => $set('items', [])),
                            ])
                            ->schema([
                                static::getItemsRepeater(),
                            ]),
                    ])
                    ->columnSpan(['lg' => fn (?Order $record) => $record === null ? 3 : 2]),

                Section::make()
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Order date')
                            ->state(fn (Order $record): ?string => $record->created_at?->diffForHumans()),

                        TextEntry::make('updated_at')
                            ->label('Last modified at')
                            ->state(fn (Order $record): ?string => $record->updated_at?->diffForHumans()),
                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn (?Order $record) => $record === null),
            ])
            ->columns(3);
    }

    /**
     * @return array<Component>
     */
    public static function getDetailsComponents(): array
    {
        return [
            TextInput::make('number')
                ->default('OR-' . random_int(100000, 999999))
                ->disabled()
                ->dehydrated()
                ->required()
                ->maxLength(32)
                ->unique(Order::class, 'number', ignoreRecord: true),

            Select::make('customer_id')
                ->relationship('customer', 'name')
                ->searchable()
                ->required()
                ->createOptionForm([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('email')
                        ->label('Email address')
                        ->required()
                        ->email()
                        ->maxLength(255)
                        ->unique(),

                    TextInput::make('phone')
                        ->maxLength(255),
                ])
                ->createOptionAction(function (Action $action) {
                    return $action
                        ->modalHeading('Create customer')
                        ->modalSubmitActionLabel('Create customer')
                        ->modalWidth(Width::Large);
                }),

            ToggleButtons::make('status')
                ->inline()
                ->options(OrderStatus::class)
                ->required(),

            Select::make('currency')
                ->options(CurrencyCode::class)
                ->searchable()
                ->required(),

            AddressForm::make('address')
                ->columnSpan('full'),

            RichEditor::make('notes')
                ->columnSpan('full'),
        ];
    }

    public static function getItemsRepeater(): Repeater
    {
        return Repeater::make('items')
            ->relationship('orderItems')
            ->table([
                TableColumn::make('Product'),
                TableColumn::make('Quantity')
                    ->width(100),
                TableColumn::make('Unit Price')
                    ->width(110),
            ])
            ->schema([
                Select::make('product_id')
                    ->label('Product')
                    ->options(Product::query()->pluck('name', 'id'))
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, Set $set) => $set('unit_price', Product::find($state)->price ?? 0))
                    ->distinct()
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->searchable(),

                TextInput::make('qty')
                    ->label('Quantity')
                    ->integer()
                    ->minValue(1)
                    ->maxValue(2147483647)
                    ->default(1)
                    ->required(),

                TextInput::make('unit_price')
                    ->disabled()
                    ->dehydrated()
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(99999999.99)
                    ->required(),
            ])
            ->extraItemActions([
                Action::make('openProduct')
                    ->tooltip('Open product')
                    ->icon(Heroicon::ArrowTopRightOnSquare)
                    ->url(function (array $arguments, Repeater $component): ?string {
                        $itemData = $component->getRawItemState($arguments['item']);

                        $product = Product::find($itemData['product_id']);

                        if (! $product) {
                            return null;
                        }

                        return ProductResource::getUrl('edit', ['record' => $product]);
                    }, shouldOpenInNewTab: true)
                    ->hidden(fn (array $arguments, Repeater $component): bool => blank($component->getRawItemState($arguments['item'])['product_id'])),
            ])
            ->orderColumn('sort')
            ->defaultItems(1)
            ->hiddenLabel()
            ->required();
    }
}
