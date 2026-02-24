<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\ShopDashboard;
use App\Filament\Resources\Blog\Authors\AuthorResource;
use App\Filament\Resources\Blog\Categories\CategoryResource as BlogCategoryResource;
use App\Filament\Resources\Blog\Posts\PostResource;
use App\Filament\Resources\HR\Departments\DepartmentResource;
use App\Filament\Resources\HR\Employees\EmployeeResource;
use App\Filament\Resources\HR\Expenses\ExpenseResource;
use App\Filament\Resources\HR\LeaveRequests\LeaveRequestResource;
use App\Filament\Resources\HR\Projects\ProjectResource;
use App\Filament\Resources\Shop\Brands\BrandResource;
use App\Filament\Resources\Shop\Customers\CustomerResource;
use App\Filament\Resources\Shop\Orders\OrderResource;
use App\Filament\Resources\Shop\Products\ProductResource;
use App\Models\Blog\Post;
use App\Models\HR\Employee;
use App\Models\HR\Expense;
use App\Models\HR\Project;
use App\Models\Shop\Order;
use App\Models\Shop\Product;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;

class FeaturesOverview extends Widget
{
    protected string $view = 'filament.widgets.features-overview';

    protected int | string | array $columnSpan = 'full';

    protected static bool $isLazy = false;

    /**
     * @return array<int, array{name: string, icon: string, color: string, features: array<int, array{name: string, description: string, url: string, resource: string}>}>
     */
    public function getCategories(): array
    {
        $post = Post::query()->first();
        $order = Order::query()->first();
        $expense = Expense::query()->first();
        $product = Product::query()->first();
        $project = Project::query()->first();
        $employee = Employee::query()->first();

        return array_filter(array_map(
            fn (?array $category): ?array => $category && count($category['features']) > 0 ? $category : null,
            [
                $this->tablesCategory(),
                $this->formsCategory($order, $post, $product, $project, $employee, $expense),
                $this->filtersCategory(),
                $this->actionsCategory(),
                $this->infolistsCategory($post, $expense),
                $this->pageActionsCategory($order, $post, $expense),
                $this->navigationCategory($post, $product),
            ],
        ));
    }

    /**
     * @return array{name: string, icon: string, color: string, features: list<array{name: string, description: string, url: string, resource: string}>}
     */
    protected function tablesCategory(): array
    {
        return [
            'name' => 'Tables & Columns',
            'icon' => 'heroicon-o-table-cells',
            'color' => 'blue',
            'features' => [
                ['name' => 'Searchable & sortable', 'description' => 'Full-text search with sortable column headers', 'url' => ProductResource::getUrl('index'), 'resource' => 'Products'],
                ['name' => 'Image columns', 'description' => 'Thumbnails from Spatie Media Library', 'url' => ProductResource::getUrl('index'), 'resource' => 'Products'],
                ['name' => 'Column summarizers', 'description' => 'Scroll to the table footer to see sum totals for price and shipping', 'url' => OrderResource::getUrl('index'), 'resource' => 'Orders'],
                ['name' => 'Inline editing', 'description' => 'Click a status cell to change it inline', 'url' => LeaveRequestResource::getUrl('index'), 'resource' => 'Leave Requests'],
                ['name' => 'Table grouping', 'description' => 'Toggle grouping using the group icon in table header', 'url' => OrderResource::getUrl('index'), 'resource' => 'Orders'],
                ['name' => 'Live polling', 'description' => 'Table data auto-refreshes every 30 seconds in the background', 'url' => ExpenseResource::getUrl('index'), 'resource' => 'Expenses'],
                ['name' => 'Toggleable columns', 'description' => 'Click the column toggle icon in the table header', 'url' => EmployeeResource::getUrl('index'), 'resource' => 'Employees'],
                ['name' => 'Color columns', 'description' => 'Hidden by default — enable "Team color" via the column toggle icon to see swatches', 'url' => EmployeeResource::getUrl('index'), 'resource' => 'Employees'],
                ['name' => 'Column layouts', 'description' => 'Custom multi-row layouts with split and stack', 'url' => AuthorResource::getUrl('index'), 'resource' => 'Authors'],
                ['name' => 'Drag-and-drop reordering', 'description' => 'Click the reorder toggle in the table header, then drag rows', 'url' => BrandResource::getUrl('index'), 'resource' => 'Brands'],
                ['name' => 'Copyable columns', 'description' => 'Click any email cell to copy the value to your clipboard', 'url' => EmployeeResource::getUrl('index'), 'resource' => 'Employees'],
            ],
        ];
    }

    /**
     * @return array{name: string, icon: string, color: string, features: list<array{name: string, description: string, url: string, resource: string}>}
     */
    protected function filtersCategory(): array
    {
        return [
            'name' => 'Filters',
            'icon' => 'heroicon-o-funnel',
            'color' => 'violet',
            'features' => [
                ['name' => 'Query builder', 'description' => 'Click the filter icon above the table', 'url' => ProductResource::getUrl('index'), 'resource' => 'Products'],
                ['name' => 'Select filters', 'description' => 'Open the filter panel to see select-based filters for type, department & more', 'url' => EmployeeResource::getUrl('index'), 'resource' => 'Employees'],
                ['name' => 'Ternary filter', 'description' => 'Open the filter panel to find the three-state "Is active" toggle', 'url' => EmployeeResource::getUrl('index'), 'resource' => 'Employees'],
                ['name' => 'Trashed filter', 'description' => 'Open the filter panel to toggle soft-deleted record visibility', 'url' => OrderResource::getUrl('index'), 'resource' => 'Orders'],
                ['name' => 'Filters above content', 'description' => 'Click the collapsed filter bar above the table to expand it', 'url' => ProductResource::getUrl('index'), 'resource' => 'Products'],
            ],
        ];
    }

    /**
     * @return array{name: string, icon: string, color: string, features: list<array{name: string, description: string, url: string, resource: string}>}
     */
    protected function actionsCategory(): array
    {
        return [
            'name' => 'Table Actions',
            'icon' => 'heroicon-o-bolt',
            'color' => 'amber',
            'features' => [
                ['name' => 'Action groups', 'description' => 'Dropdown menu grouping multiple actions — click the "..." button on any row', 'url' => ProductResource::getUrl('index'), 'resource' => 'Products'],
                ['name' => 'Slide-over modals', 'description' => 'Click "Ship" on a processing order to see a full-height slide-over panel', 'url' => OrderResource::getUrl('index'), 'resource' => 'Orders'],
                ['name' => 'Modal forms', 'description' => 'Click "Send email" on any row to open a form modal', 'url' => CustomerResource::getUrl('index'), 'resource' => 'Customers'],
                ['name' => 'Modal icon & color', 'description' => 'Click "..." then "Adjust price" on any row to see the custom modal icon and color', 'url' => ProductResource::getUrl('index'), 'resource' => 'Products'],
                ['name' => 'Custom confirmation', 'description' => 'Click "Put on hold" on an active project row', 'url' => ProjectResource::getUrl('index'), 'resource' => 'Projects'],
                ['name' => 'URL actions', 'description' => 'Click the globe icon to visit the brand\'s website', 'url' => BrandResource::getUrl('index'), 'resource' => 'Brands'],
                ['name' => 'Tooltips', 'description' => 'Hover over the row action icons to see tooltips', 'url' => BrandResource::getUrl('index'), 'resource' => 'Brands'],
                ['name' => 'Dynamic state', 'description' => 'Click "..." on any row — the toggle action shows different icon, label & color per record', 'url' => EmployeeResource::getUrl('index'), 'resource' => 'Employees'],
                ['name' => 'Lifecycle hooks', 'description' => 'Click "Submit" on a draft expense row — validates total > 0 before running', 'url' => ExpenseResource::getUrl('index'), 'resource' => 'Expenses'],
                ['name' => 'Infolist modals', 'description' => 'Click "View profile" in the row dropdown for a read-only slide-over', 'url' => EmployeeResource::getUrl('index'), 'resource' => 'Employees'],
                ['name' => 'Bulk actions', 'description' => 'Select rows with checkboxes, then use the bulk action dropdown', 'url' => ProductResource::getUrl('index'), 'resource' => 'Products'],
                ['name' => 'Conditional logic', 'description' => 'Click "..." on different order rows — each status shows different actions', 'url' => OrderResource::getUrl('index'), 'resource' => 'Orders'],
                ['name' => 'Extra modal footer actions', 'description' => 'Additional buttons in modal footer — click "Ship" then see "Ship & notify"', 'url' => OrderResource::getUrl('index'), 'resource' => 'Orders'],
            ],
        ];
    }

    /**
     * @return array{name: string, icon: string, color: string, features: list<array{name: string, description: string, url: string, resource: string}>}
     */
    protected function pageActionsCategory(?Model $order, ?Model $post, ?Model $expense): array
    {
        return [
            'name' => 'Page & Header Actions',
            'icon' => 'heroicon-o-rectangle-stack',
            'color' => 'rose',
            'features' => array_values(array_filter([
                $order ? ['name' => 'Replicate action', 'description' => 'Click "Replicate" in the edit page header', 'url' => OrderResource::getUrl('edit', ['record' => $order]), 'resource' => 'Orders'] : null,
                $post ? ['name' => 'Keyboard shortcuts', 'description' => 'Press Cmd+Shift+P on the view page to quick-publish (only on unpublished posts)', 'url' => PostResource::getUrl('view', ['record' => $post]), 'resource' => 'Posts'] : null,
                ['name' => 'Export action', 'description' => 'Click "Export" in the page header', 'url' => AuthorResource::getUrl('index'), 'resource' => 'Authors'],
                ['name' => 'Import action', 'description' => 'Click "Import" in the page header', 'url' => BlogCategoryResource::getUrl('index'), 'resource' => 'Blog Categories'],
                ['name' => 'Badge on action', 'description' => 'Dynamic count badge on action buttons — see "Leave Requests" in the page header', 'url' => EmployeeResource::getUrl('index'), 'resource' => 'Employees'],
                $expense ? ['name' => 'Status workflow', 'description' => 'Actions shown depend on expense status — try a submitted expense\'s view page', 'url' => ExpenseResource::getUrl('view', ['record' => $expense]), 'resource' => 'Expenses'] : null,
            ])),
        ];
    }

    /**
     * @return array{name: string, icon: string, color: string, features: list<array{name: string, description: string, url: string, resource: string}>}
     */
    protected function formsCategory(?Model $order, ?Model $post, ?Model $product, ?Model $project, ?Model $employee, ?Model $expense): array
    {
        return [
            'name' => 'Forms',
            'icon' => 'heroicon-o-pencil-square',
            'color' => 'emerald',
            'features' => array_values(array_filter([
                ['name' => 'Wizard', 'description' => 'The create form uses a step wizard at the top', 'url' => OrderResource::getUrl('create'), 'resource' => 'Orders'],
                $order ? ['name' => 'Repeater', 'description' => 'See the order items table with existing line items', 'url' => OrderResource::getUrl('edit', ['record' => $order]), 'resource' => 'Orders'] : null,
                $project ? ['name' => 'Builder blocks', 'description' => 'See the "Plan" tab for existing milestone and task group blocks', 'url' => ProjectResource::getUrl('edit', ['record' => $project]), 'resource' => 'Projects'] : null,
                $post ? ['name' => 'Rich editor', 'description' => 'WYSIWYG content editing with existing content', 'url' => PostResource::getUrl('edit', ['record' => $post]), 'resource' => 'Posts'] : null,
                $product ? ['name' => 'Media uploads', 'description' => 'Multiple images with reordering', 'url' => ProductResource::getUrl('edit', ['record' => $product]), 'resource' => 'Products'] : null,
                ['name' => 'Color picker', 'description' => 'Open the create/edit modal to see the color field', 'url' => DepartmentResource::getUrl('index'), 'resource' => 'Departments'],
                ['name' => 'Inline create', 'description' => 'Click the "+" icon in the Customer select to create a new customer inline', 'url' => OrderResource::getUrl('create'), 'resource' => 'Orders'],
                $employee ? ['name' => 'Conditional fields', 'description' => 'Change "Employment type" to see salary vs hourly rate toggle', 'url' => EmployeeResource::getUrl('edit', ['record' => $employee]), 'resource' => 'Employees'] : null,
                $post ? ['name' => 'Tags input', 'description' => 'See existing tags on the post form', 'url' => PostResource::getUrl('edit', ['record' => $post]), 'resource' => 'Posts'] : null,
                $expense ? ['name' => 'Reactive fields', 'description' => 'Edit line items and watch the total auto-calculate as you type', 'url' => ExpenseResource::getUrl('edit', ['record' => $expense]), 'resource' => 'Expenses'] : null,
                $employee ? ['name' => 'Form tabs', 'description' => 'See Personal, Employment & Documents tabs with existing data', 'url' => EmployeeResource::getUrl('edit', ['record' => $employee]), 'resource' => 'Employees'] : null,
            ])),
        ];
    }

    /**
     * @return array{name: string, icon: string, color: string, features: list<array{name: string, description: string, url: string, resource: string}>}
     */
    protected function infolistsCategory(?Model $post, ?Model $expense): array
    {
        return [
            'name' => 'Infolists',
            'icon' => 'heroicon-o-eye',
            'color' => 'cyan',
            'features' => array_values(array_filter([
                $post ? ['name' => 'Rich text entries', 'description' => 'Formatted text, icons, badges & prose', 'url' => PostResource::getUrl('view', ['record' => $post]), 'resource' => 'Posts'] : null,
                $post ? ['name' => 'Media entries', 'description' => 'Display Spatie Media Library images', 'url' => PostResource::getUrl('view', ['record' => $post]), 'resource' => 'Posts'] : null,
                $expense ? ['name' => 'Repeatable entries', 'description' => 'Table-style related record display', 'url' => ExpenseResource::getUrl('view', ['record' => $expense]), 'resource' => 'Expenses'] : null,
            ])),
        ];
    }

    /**
     * @return array{name: string, icon: string, color: string, features: list<array{name: string, description: string, url: string, resource: string}>}
     */
    protected function navigationCategory(?Model $post, ?Model $product): array
    {
        return [
            'name' => 'Navigation & Pages',
            'icon' => 'heroicon-o-squares-2x2',
            'color' => 'gray',
            'features' => array_values(array_filter([
                ['name' => 'Navigation badges', 'description' => 'Live record counts on sidebar items', 'url' => OrderResource::getUrl('index'), 'resource' => 'Orders'],
                $post ? ['name' => 'Sub-navigation', 'description' => 'See the tabs at the top switching between View, Edit, and Comments', 'url' => PostResource::getUrl('view', ['record' => $post]), 'resource' => 'Posts'] : null,
                ['name' => 'Page tabs', 'description' => 'Filter table content with tab toggles', 'url' => EmployeeResource::getUrl('index'), 'resource' => 'Employees'],
                ['name' => 'Dashboard widgets', 'description' => 'See the stats, line charts, and order table on this page', 'url' => ShopDashboard::getUrl(), 'resource' => 'Shop'],
                ['name' => 'Manage records', 'description' => 'Create, edit, and delete authors without leaving the list page', 'url' => AuthorResource::getUrl('index'), 'resource' => 'Authors'],
                $product ? ['name' => 'Relation managers', 'description' => 'Scroll below the form to see the Comments relation manager', 'url' => ProductResource::getUrl('edit', ['record' => $product]), 'resource' => 'Products'] : null,
                ['name' => 'Soft deletes', 'description' => 'See Restore & Force Delete in the edit page header, and TrashedFilter in filters', 'url' => OrderResource::getUrl('index'), 'resource' => 'Orders'],
                ['name' => 'Global search', 'description' => 'Use the search bar (Cmd+K) in the top navigation', 'url' => ProductResource::getUrl('index'), 'resource' => 'Try it!'],
                $post ? ['name' => 'Manage related records', 'description' => 'Dedicated page for child records — see the Comments tab in sub-navigation', 'url' => PostResource::getUrl('comments', ['record' => $post]), 'resource' => 'Posts'] : null,
            ])),
        ];
    }
}
