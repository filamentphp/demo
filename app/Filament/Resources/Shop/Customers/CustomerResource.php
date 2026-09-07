<?php

namespace App\Filament\Resources\Shop\Customers;

use App\Filament\DemoIconAlias;
use App\Filament\Resources\Shop\Customers\Pages\CreateCustomer;
use App\Filament\Resources\Shop\Customers\Pages\EditCustomer;
use App\Filament\Resources\Shop\Customers\Pages\ListCustomers;
use App\Filament\Resources\Shop\Customers\RelationManagers\AddressesRelationManager;
use App\Filament\Resources\Shop\Customers\RelationManagers\PaymentsRelationManager;
use App\Filament\Resources\Shop\Customers\Schemas\CustomerForm;
use App\Filament\Resources\Shop\Customers\Tables\CustomersTable;
use App\Models\Shop\Customer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

/**
 * @extends \Filament\Resources\Resource<Customer>
 */
class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $slug = 'shop/customers';

    protected static ?string $recordTitleAttribute = 'name';

    protected static string | UnitEnum | null $navigationGroup = 'Shop';

    protected static ?int $navigationSort = 2;

    public static function getNavigationIcon(): string | BackedEnum | Htmlable | null
    {
        return FilamentIcon::resolve(DemoIconAlias::RESOURCES_SHOP_CUSTOMERS_NAVIGATION) ?? Heroicon::OutlinedUserGroup;
    }

    public static function form(Schema $schema): Schema
    {
        return CustomerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CustomersTable::configure($table);
    }

    /** @return Builder<Customer> */
    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AddressesRelationManager::class,
            PaymentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustomers::route('/'),
            'create' => CreateCustomer::route('/create'),
            'edit' => EditCustomer::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }
}
