<?php

use App\Enums\EmploymentType;
use App\Enums\ExpenseCategory;
use App\Enums\ExpenseStatus;
use App\Enums\LeaveStatus;
use App\Enums\LeaveType;
use App\Enums\OrderStatus;
use App\Enums\ProjectStatus;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Filament\App\Pages\Settings;
use App\Filament\DemoIconAlias;
use App\Filament\Pages\HrDashboard;
use App\Filament\Resources\Blog\Posts\PostResource;
use App\Filament\Resources\Shop\Brands\Pages\ListBrands;
use App\Filament\Resources\Shop\Products\ProductResource;
use App\Filament\Widgets\FeaturesOverview;
use App\Models\Shop\Brand;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Icons\Heroicon;
use Filament\Support\Icons\IconManager;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use Livewire\Livewire;

beforeEach(function () {
    FilamentIcon::clearResolvedInstance(IconManager::class);
    app()->instance(IconManager::class, new IconManager);
});

it('resolves every enum icon to its fallback', function (BackedEnum $case, string $alias, Heroicon $fallback) {
    expect(FilamentIcon::resolve($alias))->toBeNull()
        ->and($case->getIcon())->toBe($fallback);
})->with('enum icon aliases');

it('allows every enum icon alias to be overridden', function (BackedEnum $case, string $alias) {
    FilamentIcon::register([$alias => 'custom-enum-icon']);

    expect($case->getIcon())->toBe('custom-enum-icon');
})->with('enum icon aliases');

it('resolves string, backed enum, and htmlable icon values', function (string | BackedEnum | Htmlable $icon) {
    FilamentIcon::register([DemoIconAlias::ENUMS_ORDER_STATUS_NEW => $icon]);

    expect(OrderStatus::New->getIcon())->toBe($icon);
})->with([
    'string' => ['custom-icon'],
    'backed enum' => [Heroicon::Star],
    'htmlable' => fn () => new HtmlString('<svg></svg>'),
]);

it('uses navigation fallbacks and registered overrides', function (string $page, string $alias, Heroicon $fallback) {
    expect($page::getNavigationIcon())->toBe($fallback);

    FilamentIcon::register([$alias => 'custom-navigation-icon']);

    expect($page::getNavigationIcon())->toBe('custom-navigation-icon');
})->with([
    'HR page' => [HrDashboard::class, DemoIconAlias::PAGES_HR_DASHBOARD_NAVIGATION, Heroicon::OutlinedBriefcase],
    'Shop resource' => [ProductResource::class, DemoIconAlias::RESOURCES_SHOP_PRODUCTS_NAVIGATION, Heroicon::OutlinedBolt],
    'Blog resource' => [PostResource::class, DemoIconAlias::RESOURCES_BLOG_POSTS_NAVIGATION, Heroicon::OutlinedDocumentText],
    'App page' => [Settings::class, DemoIconAlias::APP_PAGES_SETTINGS_NAVIGATION, Heroicon::OutlinedDocumentText],
]);

it('resolves both branches of a record-dependent action icon', function () {
    $visible = Brand::factory()->create(['is_visible' => true]);
    $hidden = Brand::factory()->create(['is_visible' => false]);

    Livewire::test(ListBrands::class)
        ->assertTableActionHasIcon('toggle_visibility', Heroicon::EyeSlash, $visible)
        ->assertTableActionHasIcon('toggle_visibility', Heroicon::Eye, $hidden);

    FilamentIcon::register([
        DemoIconAlias::RESOURCES_SHOP_BRANDS_ACTIONS_HIDE => Heroicon::Minus,
        DemoIconAlias::RESOURCES_SHOP_BRANDS_ACTIONS_SHOW => Heroicon::Plus,
    ]);

    Livewire::test(ListBrands::class)
        ->assertTableActionHasIcon('toggle_visibility', Heroicon::Minus, $visible)
        ->assertTableActionHasIcon('toggle_visibility', Heroicon::Plus, $hidden);
});

it('renders feature widget blade icon aliases', function () {
    FilamentIcon::register([
        DemoIconAlias::WIDGETS_FEATURES_HEADING => new HtmlString('<svg data-icon="custom-heading"></svg>'),
        DemoIconAlias::WIDGETS_FEATURES_TABLES => new HtmlString('<svg data-icon="custom-category"></svg>'),
        DemoIconAlias::WIDGETS_FEATURES_LINK => new HtmlString('<svg data-icon="custom-link"></svg>'),
    ]);

    Livewire::test(FeaturesOverview::class)
        ->assertSeeHtml('data-icon="custom-heading"')
        ->assertSeeHtml('data-icon="custom-category"')
        ->assertSeeHtml('data-icon="custom-link"');
});

dataset('enum icon aliases', [
    [EmploymentType::from('full_time'), 'demo::enums.employment-type.full-time', Heroicon::UserGroup],
    [EmploymentType::from('part_time'), 'demo::enums.employment-type.part-time', Heroicon::Clock],
    [EmploymentType::from('contractor'), 'demo::enums.employment-type.contractor', Heroicon::Briefcase],
    [EmploymentType::from('intern'), 'demo::enums.employment-type.intern', Heroicon::AcademicCap],
    [ExpenseCategory::from('travel'), 'demo::enums.expense-category.travel', Heroicon::GlobeAlt],
    [ExpenseCategory::from('meals'), 'demo::enums.expense-category.meals', Heroicon::Cake],
    [ExpenseCategory::from('supplies'), 'demo::enums.expense-category.supplies', Heroicon::ShoppingCart],
    [ExpenseCategory::from('equipment'), 'demo::enums.expense-category.equipment', Heroicon::WrenchScrewdriver],
    [ExpenseCategory::from('software'), 'demo::enums.expense-category.software', Heroicon::ComputerDesktop],
    [ExpenseCategory::from('other'), 'demo::enums.expense-category.other', Heroicon::EllipsisHorizontal],
    [ExpenseStatus::from('draft'), 'demo::enums.expense-status.draft', Heroicon::Pencil],
    [ExpenseStatus::from('submitted'), 'demo::enums.expense-status.submitted', Heroicon::PaperAirplane],
    [ExpenseStatus::from('approved'), 'demo::enums.expense-status.approved', Heroicon::Check],
    [ExpenseStatus::from('rejected'), 'demo::enums.expense-status.rejected', Heroicon::XMark],
    [ExpenseStatus::from('reimbursed'), 'demo::enums.expense-status.reimbursed', Heroicon::Banknotes],
    [LeaveStatus::from('pending'), 'demo::enums.leave-status.pending', Heroicon::Clock],
    [LeaveStatus::from('approved'), 'demo::enums.leave-status.approved', Heroicon::Check],
    [LeaveStatus::from('rejected'), 'demo::enums.leave-status.rejected', Heroicon::XMark],
    [LeaveStatus::from('taken'), 'demo::enums.leave-status.taken', Heroicon::CheckBadge],
    [LeaveStatus::from('cancelled'), 'demo::enums.leave-status.cancelled', Heroicon::XCircle],
    [LeaveType::from('annual'), 'demo::enums.leave-type.annual', Heroicon::Sun],
    [LeaveType::from('sick'), 'demo::enums.leave-type.sick', Heroicon::Heart],
    [LeaveType::from('personal'), 'demo::enums.leave-type.personal', Heroicon::User],
    [LeaveType::from('unpaid'), 'demo::enums.leave-type.unpaid', Heroicon::Banknotes],
    [LeaveType::from('parental'), 'demo::enums.leave-type.parental', Heroicon::Gift],
    [OrderStatus::from('new'), 'demo::enums.order-status.new', Heroicon::Sparkles],
    [OrderStatus::from('processing'), 'demo::enums.order-status.processing', Heroicon::ArrowPath],
    [OrderStatus::from('shipped'), 'demo::enums.order-status.shipped', Heroicon::Truck],
    [OrderStatus::from('delivered'), 'demo::enums.order-status.delivered', Heroicon::CheckBadge],
    [OrderStatus::from('cancelled'), 'demo::enums.order-status.cancelled', Heroicon::XCircle],
    [ProjectStatus::from('planning'), 'demo::enums.project-status.planning', Heroicon::PencilSquare],
    [ProjectStatus::from('active'), 'demo::enums.project-status.active', Heroicon::Play],
    [ProjectStatus::from('on_hold'), 'demo::enums.project-status.on-hold', Heroicon::Pause],
    [ProjectStatus::from('completed'), 'demo::enums.project-status.completed', Heroicon::CheckCircle],
    [ProjectStatus::from('cancelled'), 'demo::enums.project-status.cancelled', Heroicon::XMark],
    [TaskPriority::from('low'), 'demo::enums.task-priority.low', Heroicon::ChevronDown],
    [TaskPriority::from('medium'), 'demo::enums.task-priority.medium', Heroicon::Minus],
    [TaskPriority::from('high'), 'demo::enums.task-priority.high', Heroicon::ChevronUp],
    [TaskPriority::from('critical'), 'demo::enums.task-priority.critical', Heroicon::Fire],
    [TaskStatus::from('backlog'), 'demo::enums.task-status.backlog', Heroicon::InboxStack],
    [TaskStatus::from('todo'), 'demo::enums.task-status.todo', Heroicon::QueueList],
    [TaskStatus::from('in_progress'), 'demo::enums.task-status.in-progress', Heroicon::ArrowPath],
    [TaskStatus::from('in_review'), 'demo::enums.task-status.in-review', Heroicon::Eye],
    [TaskStatus::from('completed'), 'demo::enums.task-status.completed', Heroicon::CheckCircle],
    [TaskStatus::from('cancelled'), 'demo::enums.task-status.cancelled', Heroicon::XCircle],
]);
