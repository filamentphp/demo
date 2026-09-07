<?php

use App\Filament\Pages\ShopDashboard;
use App\Models\Shop\Customer;
use App\Models\Shop\Order;
use App\Models\Shop\OrderItem;
use App\Models\Shop\Product;
use App\Models\Shop\ProductCategory;
use Filament\Enums\DatabaseNotificationsPosition;
use Filament\Enums\GlobalSearchPosition;
use Filament\Enums\UserMenuPosition;

it('uses a sidebar-only main panel without removing its controls', function (): void {
    $panel = filament()->getPanel('admin');

    expect($panel->hasTopbar())->toBeFalse()
        ->and($panel->getGlobalSearchPosition())->toBe(GlobalSearchPosition::Sidebar)
        ->and($panel->getDatabaseNotificationsPosition())->toBe(DatabaseNotificationsPosition::Sidebar)
        ->and($panel->getUserMenuPosition())->toBe(UserMenuPosition::Sidebar)
        ->and(filament()->getPanel('app')->hasTopbar())->toBeTrue();

    $this->get('/')
        ->assertOk()
        ->assertSee('Focus the sidebar search with Cmd+K or Ctrl+K');
});

it('renders the shop dashboard page with all widgets', function () {
    $categories = ProductCategory::factory()->count(3)->create();
    $customers = Customer::factory()->count(5)->create();
    $products = Product::factory()->count(5)->create();

    foreach ($products as $product) {
        $product->productCategories()->attach(
            $categories->random(rand(1, 3))->pluck('id'),
            ['created_at' => now(), 'updated_at' => now()],
        );
    }

    $orders = Order::factory()->count(10)->sequence(
        fn () => ['customer_id' => $customers->random()->id],
    )->create();

    foreach ($orders as $order) {
        OrderItem::factory()->count(rand(1, 3))->create([
            'order_id' => $order->id,
            'product_id' => $products->random()->id,
        ]);
    }

    $this->get(ShopDashboard::getUrl())
        ->assertOk()
        ->assertDontSee('fi-body-has-topbar')
        ->assertSee('fi-global-search-ctn')
        ->assertSee('fi-sidebar-footer');
});
