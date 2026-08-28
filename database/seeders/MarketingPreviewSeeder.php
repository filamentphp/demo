<?php

namespace Database\Seeders;

use App\Enums\EmploymentType;
use App\Enums\ExpenseStatus;
use App\Enums\LeaveStatus;
use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\ProjectStatus;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\HR\Department;
use App\Models\HR\Employee;
use App\Models\HR\Expense;
use App\Models\HR\LeaveRequest;
use App\Models\HR\Project;
use App\Models\HR\Task;
use App\Models\Shop\Brand;
use App\Models\Shop\Customer;
use App\Models\Shop\Order;
use App\Models\Shop\OrderAddress;
use App\Models\Shop\OrderItem;
use App\Models\Shop\Payment;
use App\Models\Shop\Product;
use App\Models\Shop\ProductCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use LogicException;

class MarketingPreviewSeeder extends Seeder
{
    private const COMPANY_NAME = 'Northstar Supply Co.';

    private const REFERENCE_TIME = '2026-08-28 12:00:00';

    public function run(): void
    {
        $referenceTime = Carbon::parse(self::REFERENCE_TIME, 'UTC');

        $manifest = Model::unguarded(fn (): array => DB::transaction(function () use ($referenceTime): array {
            $departments = $this->seedDepartments($referenceTime);
            $employees = $this->seedEmployees($departments, $referenceTime);
            $project = $this->seedProject($departments['product-operations'], $employees, $referenceTime);
            $product = $this->seedProduct($referenceTime);
            $this->normalizeSharedNavigationBadges($project, $product);
            $orderNumbers = $this->seedOrders($product, $referenceTime);

            return [
                'version' => 1,
                'companyName' => self::COMPANY_NAME,
                'productId' => $product->getKey(),
                'projectId' => $project->getKey(),
                'departmentId' => $departments['product-operations']->getKey(),
                'departmentName' => $departments['product-operations']->getAttribute('name'),
                'generatedForDate' => $referenceTime->toDateString(),
                'orderNumbers' => $orderNumbers,
            ];
        }));

        Storage::disk('local')->put(
            'marketing-preview.json',
            json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR) . PHP_EOL,
        );

        $this->command->info('Marketing preview records created for ' . self::COMPANY_NAME);
    }

    /**
     * @return array<string, Department>
     */
    private function seedDepartments(Carbon $referenceTime): array
    {
        $definitions = [
            'northstar-group' => [
                'name' => 'Northstar Group',
                'description' => 'The commercial and operational teams behind Northstar Supply Co.',
                'budget' => 3200000,
                'headcount_limit' => 80,
                'color' => '#334155',
            ],
            'brand-studio' => [
                'name' => 'Brand Studio',
                'description' => 'Campaign, editorial, and retail design for every Northstar collection.',
                'budget' => 460000,
                'headcount_limit' => 12,
                'color' => '#8b5cf6',
            ],
            'customer-experience' => [
                'name' => 'Customer Experience',
                'description' => 'Customer care, community programs, and post-purchase experience.',
                'budget' => 385000,
                'headcount_limit' => 16,
                'color' => '#0ea5e9',
            ],
            'field-operations' => [
                'name' => 'Field Operations',
                'description' => 'Retail partnerships, events, and the Northstar field testing network.',
                'budget' => 720000,
                'headcount_limit' => 20,
                'color' => '#f59e0b',
            ],
            'fulfilment' => [
                'name' => 'Fulfilment',
                'description' => 'Inventory planning, warehouse operations, and last-mile delivery.',
                'budget' => 980000,
                'headcount_limit' => 24,
                'color' => '#14b8a6',
            ],
            'product-operations' => [
                'name' => 'Product Operations',
                'description' => 'Turns field research into durable, thoughtfully sourced everyday equipment.',
                'budget' => 860000,
                'headcount_limit' => 18,
                'color' => '#4f46e5',
            ],
        ];

        $departments = [];
        $parent = Department::query()->updateOrCreate(
            ['slug' => 'northstar-group'],
            [
                ...$definitions['northstar-group'],
                'parent_id' => null,
                'is_active' => true,
                'created_at' => $referenceTime->copy()->subYears(4),
                'updated_at' => $referenceTime,
            ],
        );
        $departments['northstar-group'] = $parent;

        foreach (array_diff_key($definitions, ['northstar-group' => true]) as $slug => $definition) {
            $departments[$slug] = Department::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    ...$definition,
                    'parent_id' => $parent->getKey(),
                    'is_active' => true,
                    'created_at' => $referenceTime->copy()->subYears(3),
                    'updated_at' => $referenceTime,
                ],
            );
        }

        return $departments;
    }

    /**
     * @param  array<string, Department>  $departments
     * @return array<string, Employee>
     */
    private function seedEmployees(array $departments, Carbon $referenceTime): array
    {
        $definitions = [
            ['department' => 'product-operations', 'name' => 'Maya Chen', 'email' => 'maya.chen@northstar.example', 'job_title' => 'Director of Product', 'salary' => 142000, 'skills' => ['Product Strategy', 'Research', 'Roadmapping']],
            ['department' => 'product-operations', 'name' => 'Elliot Brooks', 'email' => 'elliot.brooks@northstar.example', 'job_title' => 'Senior Product Manager', 'salary' => 116000, 'skills' => ['Merchandising', 'Analytics', 'Operations']],
            ['department' => 'product-operations', 'name' => 'Priya Nair', 'email' => 'priya.nair@northstar.example', 'job_title' => 'Materials Lead', 'salary' => 108000, 'skills' => ['Materials', 'Sourcing', 'Quality']],
            ['department' => 'product-operations', 'name' => 'Theo Martin', 'email' => 'theo.martin@northstar.example', 'job_title' => 'Product Coordinator', 'salary' => 74000, 'skills' => ['Planning', 'Research', 'Vendor Management']],
            ['department' => 'brand-studio', 'name' => 'Amara Lewis', 'email' => 'amara.lewis@northstar.example', 'job_title' => 'Creative Director', 'salary' => 128000, 'skills' => ['Creative Direction', 'Editorial', 'Campaigns']],
            ['department' => 'brand-studio', 'name' => 'Jon Bell', 'email' => 'jon.bell@northstar.example', 'job_title' => 'Senior Designer', 'salary' => 94000, 'skills' => ['Design Systems', 'Photography', 'Retail']],
            ['department' => 'customer-experience', 'name' => 'Sofia Reyes', 'email' => 'sofia.reyes@northstar.example', 'job_title' => 'Customer Experience Lead', 'salary' => 96000, 'skills' => ['Service Design', 'Community', 'Insights']],
            ['department' => 'customer-experience', 'name' => 'Noah Williams', 'email' => 'noah.williams@northstar.example', 'job_title' => 'Community Specialist', 'salary' => 68000, 'skills' => ['Community', 'Support', 'Content']],
            ['department' => 'field-operations', 'name' => 'Grace Walker', 'email' => 'grace.walker@northstar.example', 'job_title' => 'Field Operations Manager', 'salary' => 102000, 'skills' => ['Events', 'Partnerships', 'Logistics']],
            ['department' => 'field-operations', 'name' => 'Luca Silva', 'email' => 'luca.silva@northstar.example', 'job_title' => 'Field Test Coordinator', 'salary' => 76000, 'skills' => ['Testing', 'Research', 'Partnerships']],
            ['department' => 'fulfilment', 'name' => 'Hannah Okafor', 'email' => 'hannah.okafor@northstar.example', 'job_title' => 'Head of Fulfilment', 'salary' => 119000, 'skills' => ['Supply Chain', 'Forecasting', 'Warehousing']],
            ['department' => 'fulfilment', 'name' => 'Marcus Reed', 'email' => 'marcus.reed@northstar.example', 'job_title' => 'Inventory Planner', 'salary' => 85000, 'skills' => ['Inventory', 'Forecasting', 'Analytics']],
        ];

        $employees = [];

        foreach ($definitions as $index => $definition) {
            $employee = Employee::query()->updateOrCreate(
                ['email' => $definition['email']],
                [
                    'department_id' => $departments[$definition['department']]->getKey(),
                    'name' => $definition['name'],
                    'phone' => '+1 503 555 ' . str_pad((string) (1100 + $index), 4, '0', STR_PAD_LEFT),
                    'date_of_birth' => $referenceTime->copy()->subYears(30 + ($index % 12))->subDays($index * 19),
                    'hire_date' => $referenceTime->copy()->subMonths(10 + ($index * 3)),
                    'job_title' => $definition['job_title'],
                    'employment_type' => EmploymentType::FullTime,
                    'salary' => $definition['salary'],
                    'hourly_rate' => null,
                    'team_color' => $departments[$definition['department']]->getAttribute('color'),
                    'skills' => $definition['skills'],
                    'metadata' => ['office' => 'Portland', 'company' => self::COMPANY_NAME],
                    'is_active' => true,
                    'created_at' => $referenceTime->copy()->subMonths(10 + ($index * 3)),
                    'updated_at' => $referenceTime,
                    'deleted_at' => null,
                ],
            );
            $employees[$definition['email']] = $employee;
        }

        return $employees;
    }

    /**
     * @param  array<string, Employee>  $employees
     */
    private function seedProject(Department $department, array $employees, Carbon $referenceTime): Project
    {
        $project = Project::query()->updateOrCreate(
            ['slug' => 'atlas-spring-launch'],
            [
                'department_id' => $department->getKey(),
                'name' => 'Atlas Spring Launch',
                'description' => "Bring the Atlas Trail Boot from field-test favourite to Northstar's lead spring release.\n\nThe launch pairs a refined product story with retailer training, a small run of community walks, and a tightly coordinated fulfilment plan.",
                'color' => '#4f46e5',
                'status' => ProjectStatus::Active,
                'priority' => TaskPriority::High,
                'budget' => 185000,
                'spent' => 108450,
                'estimated_hours' => 620,
                'actual_hours' => 386.5,
                'start_date' => $referenceTime->copy()->subMonths(2)->startOfMonth(),
                'end_date' => $referenceTime->copy()->addMonths(2)->endOfMonth(),
                'plan' => [
                    ['type' => 'milestone', 'data' => ['title' => 'Retail samples approved', 'target_date' => $referenceTime->copy()->subWeeks(2)->toDateString(), 'description' => 'Approve final materials, sizing, and packaging.']],
                    ['type' => 'task_group', 'data' => ['title' => 'Launch readiness', 'assignee' => null, 'tasks' => ['Finish campaign assets', 'Confirm inventory allocation', 'Brief retail partners']]],
                    ['type' => 'checkpoint', 'data' => ['title' => 'Go-live review', 'date' => $referenceTime->copy()->addWeeks(3)->toDateString(), 'status' => 'pending']],
                ],
                'created_at' => $referenceTime->copy()->subMonths(3),
                'updated_at' => $referenceTime,
                'deleted_at' => null,
            ],
        );

        $tasks = [
            ['title' => 'Approve final field-test revisions', 'assignee' => 'maya.chen@northstar.example', 'status' => TaskStatus::Completed, 'priority' => TaskPriority::Critical, 'estimated' => 32, 'actual' => 29.5, 'due' => -24, 'labels' => ['product', 'field-test']],
            ['title' => 'Lock launch inventory allocation', 'assignee' => 'marcus.reed@northstar.example', 'status' => TaskStatus::Completed, 'priority' => TaskPriority::High, 'estimated' => 20, 'actual' => 18, 'due' => -14, 'labels' => ['inventory', 'operations']],
            ['title' => 'Produce retailer training kit', 'assignee' => 'jon.bell@northstar.example', 'status' => TaskStatus::InReview, 'priority' => TaskPriority::High, 'estimated' => 38, 'actual' => 31, 'due' => 5, 'labels' => ['retail', 'design']],
            ['title' => 'Publish Atlas product story', 'assignee' => 'amara.lewis@northstar.example', 'status' => TaskStatus::InProgress, 'priority' => TaskPriority::High, 'estimated' => 28, 'actual' => 17.5, 'due' => 8, 'labels' => ['campaign', 'content']],
            ['title' => 'Confirm launch-day fulfilment rota', 'assignee' => 'hannah.okafor@northstar.example', 'status' => TaskStatus::InProgress, 'priority' => TaskPriority::Medium, 'estimated' => 16, 'actual' => 7, 'due' => 12, 'labels' => ['fulfilment', 'planning']],
            ['title' => 'Schedule community trail walks', 'assignee' => 'grace.walker@northstar.example', 'status' => TaskStatus::Todo, 'priority' => TaskPriority::Medium, 'estimated' => 24, 'actual' => 0, 'due' => 18, 'labels' => ['community', 'events']],
            ['title' => 'Prepare post-launch customer survey', 'assignee' => 'sofia.reyes@northstar.example', 'status' => TaskStatus::Backlog, 'priority' => TaskPriority::Low, 'estimated' => 12, 'actual' => 0, 'due' => 35, 'labels' => ['research', 'customer']],
        ];

        foreach ($tasks as $index => $definition) {
            Task::query()->updateOrCreate(
                [
                    'project_id' => $project->getKey(),
                    'title' => $definition['title'],
                ],
                [
                    'assigned_to' => $employees[$definition['assignee']]->getKey(),
                    'description' => 'A launch-critical workstream for the Atlas Spring Launch.',
                    'status' => $definition['status'],
                    'priority' => $definition['priority'],
                    'estimated_hours' => $definition['estimated'],
                    'actual_hours' => $definition['actual'],
                    'due_date' => $referenceTime->copy()->addDays($definition['due']),
                    'completed_at' => $definition['status'] === TaskStatus::Completed
                        ? $referenceTime->copy()->addDays($definition['due'])->subHours(3)
                        : null,
                    'labels' => $definition['labels'],
                    'sort' => $index + 1,
                    'created_at' => $referenceTime->copy()->subDays(20 - $index),
                    'updated_at' => $referenceTime->copy()->subHours($index + 1),
                ],
            );
        }

        return $project;
    }

    private function seedProduct(Carbon $referenceTime): Product
    {
        $brand = Brand::query()->updateOrCreate(
            ['slug' => 'northstar-supply'],
            [
                'name' => self::COMPANY_NAME,
                'website' => 'https://northstar.example',
                'description' => 'Dependable everyday equipment shaped by real journeys and responsible materials.',
                'position' => 1,
                'is_visible' => true,
                'seo_title' => 'Northstar Supply Co.',
                'seo_description' => 'Field-tested essentials for everyday exploration.',
                'created_at' => $referenceTime->copy()->subYears(4),
                'updated_at' => $referenceTime,
            ],
        );

        $categories = collect([
            [
                'slug' => 'northstar-footwear',
                'name' => 'Trail Footwear',
                'description' => 'Comfortable, repairable footwear for city miles and weekend trails.',
                'position' => 1,
            ],
            [
                'slug' => 'northstar-field-essentials',
                'name' => 'Field Essentials',
                'description' => 'The core pieces Northstar reaches for on every journey.',
                'position' => 2,
            ],
        ])->map(fn (array $definition): ProductCategory => ProductCategory::query()->updateOrCreate(
            ['slug' => $definition['slug']],
            [
                ...$definition,
                'parent_id' => null,
                'is_visible' => true,
                'seo_title' => $definition['name'],
                'seo_description' => $definition['description'],
                'created_at' => $referenceTime->copy()->subYears(2),
                'updated_at' => $referenceTime,
            ],
        ));

        $product = Product::query()->updateOrCreate(
            ['slug' => 'atlas-trail-boot'],
            [
                'brand_id' => $brand->getKey(),
                'name' => 'Atlas Trail Boot',
                'sku' => 'NST-ATLAS-01',
                'barcode' => '850042601184',
                'description' => '<p>A lightweight trail boot designed for long city days, spontaneous detours, and weekends beyond the pavement.</p><p>Built with a supportive recycled-rubber sole, water-resistant upper, and replaceable cork footbed.</p>',
                'qty' => 184,
                'security_stock' => 32,
                'featured' => true,
                'is_visible' => true,
                'old_price' => 220,
                'price' => 198,
                'cost' => 86.40,
                'type' => 'deliverable',
                'backorder' => true,
                'requires_shipping' => true,
                'published_at' => $referenceTime->copy()->subMonths(2),
                'seo_title' => 'Atlas Trail Boot',
                'seo_description' => 'A lightweight, field-tested trail boot from Northstar Supply Co.',
                'weight_value' => 1.10,
                'weight_unit' => 'kg',
                'height_value' => 16,
                'height_unit' => 'cm',
                'width_value' => 24,
                'width_unit' => 'cm',
                'depth_value' => 34,
                'depth_unit' => 'cm',
                'volume_value' => 13.10,
                'volume_unit' => 'l',
                'created_at' => $referenceTime->copy()->subMonths(6),
                'updated_at' => $referenceTime,
            ],
        );

        $product->productCategories()->sync($categories->pluck('id')->all());

        $product->clearMediaCollection('product-images');

        $product
            ->addMedia(database_path('seeders/marketing_preview/atlas-trail-boot.jpg'))
            ->preservingOriginal()
            ->usingName('Atlas Trail Boot')
            ->usingFileName('atlas-trail-boot.jpg')
            ->toMediaCollection('product-images');

        return $product;
    }

    /**
     * @return list<string>
     */
    private function seedOrders(Product $product, Carbon $referenceTime): array
    {
        $customers = collect([
            ['name' => 'Olivia Park', 'email' => 'olivia.park@customers.northstar.example', 'phone' => '+1 503 555 2011'],
            ['name' => 'James Foster', 'email' => 'james.foster@customers.northstar.example', 'phone' => '+1 206 555 1842'],
            ['name' => 'Mia Thompson', 'email' => 'mia.thompson@customers.northstar.example', 'phone' => '+1 415 555 6308'],
            ['name' => 'Ethan Cole', 'email' => 'ethan.cole@customers.northstar.example', 'phone' => '+1 303 555 7621'],
            ['name' => 'Ava Patel', 'email' => 'ava.patel@customers.northstar.example', 'phone' => '+1 512 555 4904'],
            ['name' => 'Lucas Bennett', 'email' => 'lucas.bennett@customers.northstar.example', 'phone' => '+1 617 555 9135'],
            ['name' => 'Nora Kim', 'email' => 'nora.kim@customers.northstar.example', 'phone' => '+1 503 555 4470'],
            ['name' => 'Mateo Rivera', 'email' => 'mateo.rivera@customers.northstar.example', 'phone' => '+1 312 555 2866'],
            ['name' => 'Isla Morgan', 'email' => 'isla.morgan@customers.northstar.example', 'phone' => '+1 718 555 3309'],
            ['name' => 'Sam Wilson', 'email' => 'sam.wilson@customers.northstar.example', 'phone' => '+1 971 555 8052'],
        ])->map(function (array $definition, int $index) use ($referenceTime): Customer {
            return Customer::query()->updateOrCreate(
                ['email' => $definition['email']],
                [
                    'name' => $definition['name'],
                    'phone' => $definition['phone'],
                    'birthday' => $referenceTime->copy()->subYears(26 + $index)->subDays($index * 31),
                    'created_at' => $referenceTime->copy()->subMonths(4 + $index),
                    'updated_at' => $referenceTime,
                    'deleted_at' => null,
                ],
            );
        });

        $orders = [
            ['number' => 'NS-MKT-1015', 'customer' => 0, 'status' => OrderStatus::New, 'qty' => 1, 'unit_price' => 198, 'shipping' => 12, 'method' => PaymentMethod::ApplePay],
            ['number' => 'NS-MKT-1014', 'customer' => 1, 'status' => OrderStatus::Processing, 'qty' => 2, 'unit_price' => 198, 'shipping' => 0, 'method' => PaymentMethod::CreditCard],
            ['number' => 'NS-MKT-1013', 'customer' => 2, 'status' => OrderStatus::New, 'qty' => 1, 'unit_price' => 198, 'shipping' => 18, 'method' => PaymentMethod::Paypal],
            ['number' => 'NS-MKT-1012', 'customer' => 3, 'status' => OrderStatus::Shipped, 'qty' => 3, 'unit_price' => 189, 'shipping' => 0, 'method' => PaymentMethod::CreditCard],
            ['number' => 'NS-MKT-1011', 'customer' => 4, 'status' => OrderStatus::Processing, 'qty' => 1, 'unit_price' => 198, 'shipping' => 12, 'method' => PaymentMethod::GooglePay],
            ['number' => 'NS-MKT-1010', 'customer' => 5, 'status' => OrderStatus::Delivered, 'qty' => 2, 'unit_price' => 179, 'shipping' => 0, 'method' => PaymentMethod::BankTransfer],
            ['number' => 'NS-MKT-1009', 'customer' => 6, 'status' => OrderStatus::Shipped, 'qty' => 1, 'unit_price' => 198, 'shipping' => 12, 'method' => PaymentMethod::ApplePay],
            ['number' => 'NS-MKT-1008', 'customer' => 7, 'status' => OrderStatus::Delivered, 'qty' => 4, 'unit_price' => 179, 'shipping' => 0, 'method' => PaymentMethod::CreditCard],
            ['number' => 'NS-MKT-1007', 'customer' => 8, 'status' => OrderStatus::Cancelled, 'qty' => 1, 'unit_price' => 198, 'shipping' => 0, 'method' => PaymentMethod::Paypal],
            ['number' => 'NS-MKT-1006', 'customer' => 9, 'status' => OrderStatus::Delivered, 'qty' => 2, 'unit_price' => 189, 'shipping' => 12, 'method' => PaymentMethod::CreditCard],
            ['number' => 'NS-MKT-1005', 'customer' => 0, 'status' => OrderStatus::Processing, 'qty' => 1, 'unit_price' => 198, 'shipping' => 18, 'method' => PaymentMethod::Paypal],
            ['number' => 'NS-MKT-1004', 'customer' => 2, 'status' => OrderStatus::Delivered, 'qty' => 3, 'unit_price' => 179, 'shipping' => 0, 'method' => PaymentMethod::CreditCard],
            ['number' => 'NS-MKT-1003', 'customer' => 4, 'status' => OrderStatus::Shipped, 'qty' => 1, 'unit_price' => 198, 'shipping' => 12, 'method' => PaymentMethod::GooglePay],
            ['number' => 'NS-MKT-1002', 'customer' => 6, 'status' => OrderStatus::Delivered, 'qty' => 2, 'unit_price' => 189, 'shipping' => 0, 'method' => PaymentMethod::ApplePay],
            ['number' => 'NS-MKT-1001', 'customer' => 8, 'status' => OrderStatus::Delivered, 'qty' => 1, 'unit_price' => 198, 'shipping' => 18, 'method' => PaymentMethod::BankTransfer],
        ];

        $this->normalizeBaseOrders(array_column($orders, 'number'), $referenceTime);

        foreach ($orders as $index => $definition) {
            $createdAt = $referenceTime->copy()->addSeconds(count($orders) - $index);
            $total = ($definition['qty'] * $definition['unit_price']) + $definition['shipping'];
            $customer = $customers->get($definition['customer']);

            if ($customer === null) {
                throw new LogicException('Every marketing order must reference a showcase customer.');
            }

            $order = Order::query()->updateOrCreate(
                ['number' => $definition['number']],
                [
                    'customer_id' => $customer->getKey(),
                    'total_price' => $total,
                    'status' => $definition['status'],
                    'shipping_price' => $definition['shipping'],
                    'shipping_method' => $definition['shipping'] === 0 ? 'free' : 'flat_rate',
                    'notes' => $definition['status'] === OrderStatus::Cancelled
                        ? 'Customer requested cancellation before fulfilment.'
                        : 'Atlas Spring Launch showcase order.',
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                    'deleted_at' => null,
                ],
            );

            OrderItem::query()->updateOrCreate(
                [
                    'order_id' => $order->getKey(),
                    'product_id' => $product->getKey(),
                ],
                [
                    'qty' => $definition['qty'],
                    'unit_price' => $definition['unit_price'],
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ],
            );

            OrderAddress::query()->updateOrCreate(
                [
                    'addressable_type' => $order->getMorphClass(),
                    'addressable_id' => $order->getKey(),
                ],
                [
                    'country' => 'us',
                    'street' => (120 + ($index * 17)) . ' Cedar Street',
                    'city' => $index % 2 === 0 ? 'Portland' : 'Seattle',
                    'state' => $index % 2 === 0 ? 'Oregon' : 'Washington',
                    'zip' => $index % 2 === 0 ? '97205' : '98101',
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ],
            );

            if ($definition['status'] !== OrderStatus::Cancelled) {
                Payment::query()->updateOrCreate(
                    [
                        'order_id' => $order->getKey(),
                        'reference' => 'PAY-' . $definition['number'],
                    ],
                    [
                        'provider' => $definition['method'] === PaymentMethod::Paypal ? 'paypal' : 'stripe',
                        'method' => $definition['method']->value,
                        'amount' => $total,
                        'currency' => 'usd',
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ],
                );
            }
        }

        return array_column($orders, 'number');
    }

    /**
     * Keep the navigation badges visible in every capture independent of the
     * intentionally random high-volume seed.
     */
    private function normalizeSharedNavigationBadges(Project $showcaseProject, Product $showcaseProduct): void
    {
        Project::query()
            ->where('id', '!=', $showcaseProject->getKey())
            ->update(['status' => ProjectStatus::Planning->value]);
        Project::query()
            ->whereKey(Project::query()->where('id', '!=', $showcaseProject->getKey())->orderBy('id')->limit(8)->pluck('id'))
            ->update(['status' => ProjectStatus::Active->value]);

        Product::query()
            ->where('id', '!=', $showcaseProduct->getKey())
            ->update(['qty' => 100, 'security_stock' => 20]);
        Product::query()
            ->whereKey(Product::query()->where('id', '!=', $showcaseProduct->getKey())->orderBy('id')->limit(7)->pluck('id'))
            ->update(['qty' => 8, 'security_stock' => 20]);

        Task::query()
            ->where('project_id', '!=', $showcaseProject->getKey())
            ->update(['status' => TaskStatus::Completed->value]);
        Task::query()
            ->whereKey(Task::query()->where('project_id', '!=', $showcaseProject->getKey())->orderBy('id')->limit(24)->pluck('id'))
            ->update(['status' => TaskStatus::InProgress->value]);

        Expense::query()->update(['status' => ExpenseStatus::Approved->value]);
        Expense::query()
            ->whereKey(Expense::query()->orderBy('id')->limit(36)->pluck('id'))
            ->update(['status' => ExpenseStatus::Submitted->value]);

        LeaveRequest::query()->update(['status' => LeaveStatus::Approved->value]);
        LeaveRequest::query()
            ->whereKey(LeaveRequest::query()->orderBy('id')->limit(42)->pluck('id'))
            ->update(['status' => LeaveStatus::Pending->value]);
    }

    /**
     * @param  list<string>  $showcaseNumbers
     */
    private function normalizeBaseOrders(array $showcaseNumbers, Carbon $referenceTime): void
    {
        $baseOrderIds = Order::query()
            ->whereNotIn('number', $showcaseNumbers)
            ->orderBy('id')
            ->pluck('id');

        if ($baseOrderIds->count() < 1000) {
            throw new LogicException('Marketing previews require at least 1,000 base orders.');
        }

        $removedOrderIds = $baseOrderIds->slice(1000)->values();

        if ($removedOrderIds->isNotEmpty()) {
            Payment::query()->whereIn('order_id', $removedOrderIds)->delete();
            OrderItem::query()->whereIn('order_id', $removedOrderIds)->delete();
            OrderAddress::query()
                ->where('addressable_type', (new Order)->getMorphClass())
                ->whereIn('addressable_id', $removedOrderIds)
                ->delete();
            Order::query()->whereKey($removedOrderIds)->forceDelete();
        }

        $statusPattern = [
            OrderStatus::New,
            OrderStatus::New,
            OrderStatus::New,
            OrderStatus::Processing,
            OrderStatus::Processing,
            OrderStatus::Processing,
            OrderStatus::Shipped,
            OrderStatus::Shipped,
            OrderStatus::Shipped,
            OrderStatus::Shipped,
            OrderStatus::Delivered,
            OrderStatus::Delivered,
            OrderStatus::Delivered,
            OrderStatus::Delivered,
            OrderStatus::Delivered,
            OrderStatus::Delivered,
            OrderStatus::Delivered,
            OrderStatus::Delivered,
            OrderStatus::Cancelled,
            OrderStatus::Cancelled,
        ];
        $shippingPattern = [0, 12, 18];
        $monthlyOrderCounts = [66, 128, 89, 75, 108, 97, 82, 64, 58, 92, 76, 65];
        $monthlyPriceBases = [214, 188, 246, 221, 274, 239, 198, 257, 231, 289, 263, 205];
        $monthOffsets = [];

        if (array_sum($monthlyOrderCounts) !== 1000) {
            throw new LogicException('Marketing order chart distribution must cover exactly 1,000 base orders.');
        }

        foreach ($monthlyOrderCounts as $monthsAgo => $count) {
            array_push($monthOffsets, ...array_fill(0, $count, $monthsAgo));
        }

        $groups = [];

        foreach ($baseOrderIds->take(1000)->values() as $index => $orderId) {
            $status = $statusPattern[$index % count($statusPattern)];
            $month = $monthOffsets[$index];
            $priceStep = $index % 5;
            $shipping = $shippingPattern[$index % count($shippingPattern)];
            $key = implode(':', [$status->value, $month, $priceStep, $shipping]);

            $groups[$key]['ids'][] = $orderId;
            $groups[$key]['values'] = [
                'status' => $status->value,
                'total_price' => $monthlyPriceBases[$month] + ($priceStep * 18.75),
                'shipping_price' => $shipping,
                'created_at' => $referenceTime
                    ->copy()
                    ->subMonthsNoOverflow($month)
                    ->startOfMonth()
                    ->addDays(9)
                    ->setTime(10, 0),
            ];
        }

        foreach ($groups as $group) {
            Order::query()->whereKey($group['ids'])->update($group['values']);
        }
    }
}
