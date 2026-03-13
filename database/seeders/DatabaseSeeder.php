<?php

namespace Database\Seeders;

use App\Enums\EmploymentType;
use App\Filament\Resources\Shop\Orders\OrderResource;
use App\Models\Address;
use App\Models\Blog\Author;
use App\Models\Blog\Post;
use App\Models\Blog\PostCategory;
use App\Models\Comment;
use App\Models\HR\Department;
use App\Models\HR\Employee;
use App\Models\HR\Expense;
use App\Models\HR\ExpenseLine;
use App\Models\HR\LeaveRequest;
use App\Models\HR\Project;
use App\Models\HR\Task;
use App\Models\HR\Timesheet;
use App\Models\Shop\Brand;
use App\Models\Shop\Customer;
use App\Models\Shop\Order;
use App\Models\Shop\OrderItem;
use App\Models\Shop\Payment;
use App\Models\Shop\Product;
use App\Models\Shop\ProductCategory;
use App\Models\User;
use Closure;
use DateTimeInterface;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Console\Helper\ProgressBar;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::raw('SET time_zone=\'+00:00\'');

        // +10% random variation so seeded counts aren't perfectly round
        $vary = fn (int $n): int => rand($n, (int) ceil($n * 1.1));

        // Clear images
        $publicDisk = Storage::disk('public');
        collect($publicDisk->allDirectories())->each(fn ($dir) => $publicDisk->deleteDirectory($dir));
        collect($publicDisk->allFiles())->each(fn ($file) => $publicDisk->delete($file));

        // Admin
        $this->command->warn(PHP_EOL . 'Creating admin user...');
        $user = $this->withProgressBar(1, fn () => User::factory(1)->create([
            'name' => 'Demo User',
            'email' => 'admin@filamentphp.com',
            'password' => Hash::make('demo.Filament@2021!'),
        ]));
        $this->command->info('Admin user created.');

        // Shop
        $this->command->warn(PHP_EOL . 'Creating brands...');
        $brands = $this->withProgressBar($vary(20), fn () => Brand::factory()->count(20)
            ->has(Address::factory()->count(rand(1, 3)))
            ->create());
        Brand::query()->update(['sort' => new Expression('id')]);
        $this->command->info('Brands created.');

        $this->command->warn(PHP_EOL . 'Creating product categories...');
        $categories = $this->withProgressBar($vary(20), fn () => ProductCategory::factory(1)
            ->has(
                ProductCategory::factory()->count(3),
                'children'
            )->create());
        $this->command->info('Product categories created.');

        $this->command->warn(PHP_EOL . 'Creating customers...');

        // Weighted monthly distribution for realistic customer growth chart.
        // Index 0 = 24 months ago → index 23 = this month.
        // Slow early trickle → steady ramp → recent acceleration with seasonal dips.
        $customerMonthlyWeights = [
            // 24–13 months ago (early days, slow organic growth)
            8, 10, 12, 14, 18, 22, 20, 25, 28, 24, 35, 15,
            // 12–1 months ago (growth phase after marketing push)
            38, 30, 50, 55, 70, 85, 95, 80, 100, 75, 130, 45,
        ];
        $customerTotalWeight = array_sum($customerMonthlyWeights);

        $pickCustomerMonth = function () use ($customerMonthlyWeights, $customerTotalWeight): int {
            $rand = rand(1, $customerTotalWeight);
            $cumulative = 0;

            foreach ($customerMonthlyWeights as $i => $weight) {
                $cumulative += $weight;

                if ($rand <= $cumulative) {
                    return $i;
                }
            }

            return count($customerMonthlyWeights) - 1;
        };

        $customers = $this->withProgressBar($vary(1000), fn () => Customer::factory(1)
            ->state(function () use ($pickCustomerMonth) {
                $monthIndex = $pickCustomerMonth();
                $monthsAgo = 23 - $monthIndex;
                $start = now()->subMonths($monthsAgo)->startOfMonth();
                $end = min(now(), now()->subMonths($monthsAgo)->endOfMonth());

                $createdAt = fake()->dateTimeBetween($start, $end);

                return [
                    'created_at' => $createdAt,
                    'updated_at' => fake()->dateTimeBetween($createdAt, 'now'),
                ];
            })
            ->has(Address::factory()->count(rand(1, 3)))
            ->create());
        $this->command->info('Customers created.');

        $this->command->warn(PHP_EOL . 'Creating products...');
        $products = $this->withProgressBar($vary(50), fn () => Product::factory(1)
            ->sequence(fn ($sequence) => ['brand_id' => $brands->random(1)->first()->id])
            ->hasAttached($categories->random(rand(3, 6)), ['created_at' => now(), 'updated_at' => now()])
            ->has(
                Comment::factory()->count(rand(10, 20))
                    ->state(fn (array $attributes, Product $product) => [
                        'customer_id' => $customers->random(1)->first()->id,
                        'created_at' => fake()->dateTimeBetween('-2 years', 'now'),
                    ]),
            )
            ->create());
        $this->command->info('Products created.');

        // Anomaly products for interesting scatter chart (Price vs Stock Quantity)
        $anomalies = [
            ['price' => 450, 'qty' => 85],  // Expensive but high stock (bulk premium)
            ['price' => 480, 'qty' => 60],  // Expensive but decent stock
            ['price' => 12, 'qty' => 3],    // Cheap but rare (clearance)
            ['price' => 8, 'qty' => 2],     // Cheap but rare
            ['price' => 250, 'qty' => 70],  // Mid-high price, very high stock
        ];

        foreach ($anomalies as $anomaly) {
            $products->push(Product::factory()
                ->state([
                    'old_price' => $anomaly['price'],
                    'price' => $anomaly['price'] * fake()->randomFloat(2, 0.7, 1.0),
                    'cost' => $anomaly['price'] * fake()->randomFloat(2, 0.3, 0.6),
                    'qty' => $anomaly['qty'],
                ])
                ->sequence(fn ($sequence) => ['brand_id' => $brands->random(1)->first()->id])
                ->hasAttached($categories->random(rand(3, 6)), ['created_at' => now(), 'updated_at' => now()])
                ->create());
        }

        $this->command->warn(PHP_EOL . 'Creating orders...');

        // Weighted monthly distribution for interesting charts.
        // Index 0 = 24 months ago → index 23 = this month.
        // Two "years" of seasonal patterns so cumulative charts have historical baseline.
        $monthlyWeights = [
            // 24–13 months ago (historical baseline, lower volume)
            25, 20, 30, 35, 45, 55, 65, 60, 50, 40, 80, 30,
            // 12–1 months ago (recent year, higher volume)
            40, 30, 45, 60, 75, 90, 110, 100, 80, 65, 130, 50,
        ];
        $totalWeight = array_sum($monthlyWeights);

        // Higher-value orders during peak months (repeating seasonal pattern).
        $monthlyPriceRanges = [
            [100, 1000], [100, 800], [100, 1200], [100, 1400],
            [150, 1600], [150, 1800], [200, 2000], [150, 1800],
            [100, 1500], [100, 1200], [200, 2500], [100, 1000],
            [100, 1200], [100, 1000], [100, 1400], [150, 1600],
            [150, 1800], [200, 2200], [200, 2500], [200, 2200],
            [150, 1800], [100, 1500], [250, 3000], [100, 1200],
        ];

        $pickMonth = function () use ($monthlyWeights, $totalWeight): int {
            $rand = rand(1, $totalWeight);
            $cumulative = 0;

            foreach ($monthlyWeights as $i => $weight) {
                $cumulative += $weight;

                if ($rand <= $cumulative) {
                    return $i;
                }
            }

            return count($monthlyWeights) - 1;
        };

        // Weighted order item counts for varied Order Size Distribution chart
        $pickOrderItemCount = fn (): int => fake()->randomElement([
            // ~30% have 1 item, ~25% have 2, ~20% have 3, ~12% have 4, ~8% have 5, ~5% have 6-8
            ...array_fill(0, 6, 1),
            ...array_fill(0, 5, 2),
            ...array_fill(0, 4, 3),
            ...array_fill(0, 2, 4),
            ...array_fill(0, 2, 5),
            1 => 6, 7, 8,
        ]);

        $orders = $this->withProgressBar($vary(1000), fn () => Order::factory(1)
            ->sequence(function () use ($customers, $pickMonth, $monthlyPriceRanges) {
                $monthIndex = $pickMonth();
                $monthsAgo = 23 - $monthIndex;
                $start = now()->subMonths($monthsAgo)->startOfMonth();
                $end = min(now(), now()->subMonths($monthsAgo)->endOfMonth());
                [$minPrice, $maxPrice] = $monthlyPriceRanges[$monthIndex];

                return [
                    // ~15% of orders have no customer (guest checkout)
                    'customer_id' => fake()->boolean(85) ? $customers->random(1)->first()->id : null,
                    'created_at' => fake()->dateTimeBetween($start, $end),
                    'total_price' => fake()->randomFloat(2, $minPrice, $maxPrice),
                ];
            })
            ->has(Payment::factory()->count(rand(1, 3)))
            ->has(
                OrderItem::factory()->count($pickOrderItemCount())
                    ->state(fn (array $attributes, Order $order) => ['product_id' => $products->random(1)->first()->id]),
                'orderItems'
            )
            ->create());

        foreach ($orders->random(rand(5, 8)) as $order) {
            $customerName = $order->customer->name ?? 'A guest customer';

            Notification::make()
                ->title('New order')
                ->icon('heroicon-o-shopping-bag')
                ->body("{$customerName} ordered {$order->orderItems->count()} products.")
                ->actions([
                    Action::make('View')
                        ->url(OrderResource::getUrl('edit', ['record' => $order])),
                ])
                ->sendToDatabase($user);
        }
        $this->command->info('Orders created.');

        // Blog
        $this->command->warn(PHP_EOL . 'Creating post categories...');
        $blogCategories = $this->withProgressBar($vary(20), fn () => PostCategory::factory(1)
            ->count(20)
            ->create());
        $this->command->info('Post categories created.');

        $this->command->warn(PHP_EOL . 'Creating authors and posts...');
        $postTags = ['Laravel', 'PHP', 'JavaScript', 'CSS', 'DevOps', 'Testing', 'API', 'Security', 'Performance', 'Architecture', 'Git', 'Database', 'Accessibility', 'Deployment', 'Design Patterns'];

        // Weighted monthly publishing cadence over 2 years.
        // Growth story: slow start → steady acceleration → recent burst.
        $blogMonthlyWeights = [
            // 24–13 months ago (early days, sparse publishing)
            2, 1, 3, 2, 4, 5, 3, 6, 4, 5, 7, 3,
            // 12–1 months ago (growth phase, much more active)
            8, 6, 10, 12, 14, 18, 20, 16, 22, 15, 25, 10,
        ];
        $blogTotalWeight = array_sum($blogMonthlyWeights);

        $pickBlogMonth = function () use ($blogMonthlyWeights, $blogTotalWeight): int {
            $rand = rand(1, $blogTotalWeight);
            $cumulative = 0;

            foreach ($blogMonthlyWeights as $i => $weight) {
                $cumulative += $weight;

                if ($rand <= $cumulative) {
                    return $i;
                }
            }

            return count($blogMonthlyWeights) - 1;
        };

        $this->withProgressBar($vary(10), fn () => Author::factory(1)
            ->has(
                Post::factory()->count(rand(5, 12))
                    ->has(
                        Comment::factory()->count(rand(5, 10))
                            ->state(fn (array $attributes, Post $post) => [
                                'customer_id' => $customers->random(1)->first()->id,
                                'created_at' => fake()->dateTimeBetween('-2 years', 'now'),
                            ]),
                    )
                    ->state(function (array $attributes, Author $author) use ($blogCategories, $pickBlogMonth) {
                        $monthIndex = $pickBlogMonth();
                        $monthsAgo = 23 - $monthIndex;
                        $start = now()->subMonths($monthsAgo)->startOfMonth();
                        $end = min(now(), now()->subMonths($monthsAgo)->endOfMonth());

                        return [
                            'post_category_id' => fake()->boolean(85) ? $blogCategories->random(1)->first()->id : null,
                            'published_at' => fake()->boolean(80) ? fake()->dateTimeBetween($start, $end) : null,
                        ];
                    })
                    ->afterCreating(function (Post $post) use ($postTags): void {
                        $post->attachTags(fake()->randomElements($postTags, rand(2, 5)));
                    }),
                'posts'
            )
            ->create());
        $this->command->info('Authors and posts created.');

        // HR & Projects
        $this->command->warn(PHP_EOL . 'Creating departments...');
        $topDepartments = $this->withProgressBar(4, fn () => Department::factory(1)->create());

        $childNames = collect(['Frontend', 'Backend', 'DevOps', 'QA', 'Content', 'SEO'])->shuffle()->values();
        $childIndex = 0;

        $childDepartments = new Collection;
        foreach ($topDepartments as $parent) {
            $count = min(rand(1, 2), $childNames->count() - $childIndex);
            for ($i = 0; $i < $count; $i++) {
                $childName = $childNames[$childIndex++];
                $child = Department::factory()->create([
                    'parent_id' => $parent->id,
                    'name' => $childName,
                    'slug' => Str::slug($childName),
                ]);
                $childDepartments->push($child);
            }
        }

        $allDepartments = $topDepartments->merge($childDepartments);
        $this->command->info('Departments created.');

        $this->command->warn(PHP_EOL . 'Creating employees...');
        $employees = $this->withProgressBar($vary(100), fn () => Employee::factory(1)
            ->state(fn () => [
                'department_id' => $allDepartments->random()->id,
                'hire_date' => fake()->dateTimeBetween('-5 years', 'now'),
            ])
            ->afterCreating(function (Employee $employee): void {
                if ($employee->salary === null) {
                    return;
                }

                $yearsAgo = now()->diffInDays($employee->hire_date) / 365;

                // Slight upward trend for recent hires (~2K/year) with ±35% variance
                $baseSalary = 78000 + (5 - $yearsAgo) * 2000;

                $employee->updateQuietly(['salary' => round($baseSalary * fake()->randomFloat(2, 0.65, 1.40), 2)]);
            })
            ->create());

        // Anomaly employees for interesting scatter chart (Hire Date vs Salary)
        $salaryAnomalies = [
            ['hire_date' => now()->subMonths(4), 'salary' => 168000],  // Recent hire, very high (hot market)
            ['hire_date' => now()->subMonths(8), 'salary' => 158000],  // Junior but high salary
            ['hire_date' => now()->subYears(4), 'salary' => 45000],    // Long tenure, underpaid
            ['hire_date' => now()->subYears(3), 'salary' => 42000],    // Senior, low salary
            ['hire_date' => now()->subMonths(2), 'salary' => 41000],   // Recent, low salary (career changer)
            ['hire_date' => now()->subYears(2), 'salary' => 170000],   // Mid tenure, very high
        ];

        foreach ($salaryAnomalies as $anomaly) {
            $employees->push(Employee::factory()->create([
                'department_id' => $allDepartments->random()->id,
                'employment_type' => EmploymentType::FullTime,
                'hire_date' => $anomaly['hire_date'],
                'salary' => $anomaly['salary'],
                'hourly_rate' => null,
            ]));
        }

        $this->command->info('Employees created.');

        $this->command->warn(PHP_EOL . 'Creating leave requests...');
        $this->withProgressBar($vary(500), fn () => LeaveRequest::factory(1)
            ->state(fn () => [
                'employee_id' => $employees->random()->id,
                'approver_id' => fake()->boolean(60) ? $employees->random()->id : null,
            ])
            ->create());
        $this->command->info('Leave requests created.');

        $this->command->warn(PHP_EOL . 'Creating projects...');
        $projects = $this->withProgressBar($vary(20), fn () => Project::factory(1)
            ->state(fn () => ['department_id' => $allDepartments->random()->id])
            ->create());

        // Anomaly projects for interesting scatter chart (Budget vs Actual Spend)
        $projectAnomalies = [
            ['budget' => 400000, 'spent' => 50000],   // Huge budget, barely spent
            ['budget' => 20000, 'spent' => 95000],     // Small budget, way over
            ['budget' => 300000, 'spent' => 300000],   // Exactly on budget
            ['budget' => 15000, 'spent' => 45000],     // 3x over budget
            ['budget' => 500000, 'spent' => 10000],    // Massive budget, just started
        ];

        foreach ($projectAnomalies as $anomaly) {
            $projects->push(Project::factory()->create([
                'department_id' => $allDepartments->random()->id,
                'budget' => $anomaly['budget'],
                'spent' => $anomaly['spent'],
            ]));
        }

        $this->command->info('Projects created.');

        $this->command->warn(PHP_EOL . 'Creating tasks...');
        $tasks = $this->withProgressBar($vary(200), fn () => Task::factory(1)
            ->state(fn () => [
                'project_id' => $projects->random()->id,
                'assigned_to' => fake()->boolean(70) ? $employees->random()->id : null,
                'created_at' => fake()->dateTimeBetween('-10 years', 'now'),
            ])
            ->create());
        $this->command->info('Tasks created.');

        $this->command->warn(PHP_EOL . 'Creating timesheets...');

        // Generate weekday-heavy dates so Daily Billable Hours chart dips on weekends.
        // ~90% of timesheets land on weekdays (Mon-Fri), ~10% on weekends.
        $pickTimesheetDate = function (): DateTimeInterface {
            $date = fake()->dateTimeBetween('-90 days', 'now');
            $dayOfWeek = (int) $date->format('N'); // 1=Mon, 7=Sun

            if ($dayOfWeek >= 6 && fake()->boolean(90)) {
                // Shift weekend entries to nearest weekday
                $shift = $dayOfWeek === 6 ? -1 : 1; // Sat→Fri, Sun→Mon
                $date->modify("{$shift} day");
            }

            return $date;
        };

        $this->withProgressBar($vary(5000), fn () => Timesheet::factory(1)
            ->state(function () use ($employees, $projects, $tasks, $pickTimesheetDate) {
                $project = $projects->random();
                $projectTasks = $tasks->where('project_id', $project->id);
                $employee = $employees->random();

                return [
                    'employee_id' => $employee->id,
                    'project_id' => $project->id,
                    'task_id' => $projectTasks->isNotEmpty() ? $projectTasks->random()->id : null,
                    'hourly_rate' => $employee->hourly_rate ?? fake()->randomFloat(2, 50, 200),
                    'date' => $pickTimesheetDate(),
                ];
            })
            ->create());
        $this->command->info('Timesheets created.');

        $this->command->warn(PHP_EOL . 'Creating expenses...');
        $this->withProgressBar($vary(300), fn () => Expense::factory(1)
            ->state(fn () => [
                'employee_id' => $employees->random()->id,
                'project_id' => fake()->boolean(60) ? $projects->random()->id : null,
                'approved_by' => fake()->boolean(30) ? $employees->random()->id : null,
            ])
            ->has(
                ExpenseLine::factory()->count(rand(1, 5)),
                'expenseLines'
            )
            ->create()
            ->each(function (Expense $expense): void {
                $expense->update([
                    'total_amount' => $expense->expenseLines()->sum('amount'),
                ]);
            }));
        $this->command->info('Expenses created.');
    }

    protected function withProgressBar(int $amount, Closure $createCollectionOfOne): Collection
    {
        $progressBar = new ProgressBar($this->command->getOutput(), $amount);

        $progressBar->start();

        $items = new Collection;

        foreach (range(1, $amount) as $i) {
            $items->push(...$createCollectionOfOne());
            $progressBar->advance();
        }

        $progressBar->finish();

        $this->command->getOutput()->writeln('');

        return $items;
    }
}
