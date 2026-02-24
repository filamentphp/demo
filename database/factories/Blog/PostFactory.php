<?php

namespace Database\Factories\Blog;

use App\Models\Blog\Post;
use Database\Seeders\LocalImages;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Post::class;

    public function definition(): array
    {
        $template = $this->faker->randomElement($this->contentTemplates());
        $title = $template['title'];

        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . $this->faker->unique()->randomNumber(5),
            'content' => $template['content'],
            'published_at' => $this->faker->dateTimeBetween('-6 month', '+1 month'),
            'created_at' => $this->faker->dateTimeBetween('-1 year', '-6 month'),
            'updated_at' => $this->faker->dateTimeBetween('-5 month', 'now'),
        ];
    }

    public function configure(): PostFactory
    {
        return $this->afterCreating(function (Post $post): void {
            $post
                ->addMedia(LocalImages::getRandomFile(LocalImages::SIZE_200x200))
                ->preservingOriginal()
                ->toMediaCollection('post-images');
        });
    }

    /**
     * @return list<array{title: string, content: string}>
     */
    protected function contentTemplates(): array
    {
        return [
            [
                'title' => 'Getting Started with Laravel Queues',
                'content' => '<h2>Why Use Queues?</h2><p>Queues allow you to <strong>defer time-consuming tasks</strong> like sending emails or processing uploads, keeping your application fast for users.</p><h3>Setting Up Your First Queue</h3><p>Start by configuring your queue driver in <code>.env</code>:</p><pre><code>QUEUE_CONNECTION=redis</code></pre><p>Then create a job using Artisan:</p><pre><code>php artisan make:job ProcessPodcast</code></pre><h3>Key Benefits</h3><ul><li>Improved response times for web requests</li><li>Better resource utilization across workers</li><li>Automatic retry logic for failed jobs</li><li>Priority queues for critical tasks</li></ul><blockquote><p>Tip: Always implement the <code>ShouldQueue</code> interface on your job classes to ensure they run asynchronously.</p></blockquote><p>For more details, check the <a href="https://laravel.com/docs/queues">official documentation</a>.</p>',
            ],
            [
                'title' => 'Building Accessible Web Forms',
                'content' => '<h2>Accessibility Matters</h2><p>Over <strong>1 billion people worldwide</strong> live with some form of disability. Building accessible forms isn\'t just good practice — it\'s essential.</p><h3>The Basics</h3><ol><li>Always associate <code>&lt;label&gt;</code> elements with their inputs</li><li>Use <code>aria-describedby</code> for error messages</li><li>Ensure keyboard navigation works for every field</li><li>Provide clear focus indicators</li></ol><h3>Common Mistakes</h3><p>Here are patterns to <em>avoid</em>:</p><ul><li>Using placeholder text as the only label</li><li>Relying solely on color to indicate errors</li><li>Disabling paste on password fields</li><li>Auto-advancing focus without warning</li></ul><hr><h3>Testing Your Forms</h3><p>Use tools like <strong>axe DevTools</strong> or <strong>Lighthouse</strong> to audit accessibility. Manual testing with a screen reader (like <em>VoiceOver</em> or <em>NVDA</em>) is equally important.</p><blockquote><p>Remember: if a user can\'t complete your form, they can\'t use your product.</p></blockquote>',
            ],
            [
                'title' => 'Understanding Database Indexing',
                'content' => '<h2>What Are Indexes?</h2><p>A database index is a data structure that <strong>speeds up data retrieval</strong> at the cost of additional storage and slower writes. Think of it like a book\'s index — instead of scanning every page, you jump directly to the right one.</p><h3>When to Add an Index</h3><ul><li>Columns used frequently in <code>WHERE</code> clauses</li><li>Foreign key columns used in <code>JOIN</code> operations</li><li>Columns used in <code>ORDER BY</code> or <code>GROUP BY</code></li></ul><h3>When NOT to Index</h3><ul><li>Tables with very few rows</li><li>Columns with low cardinality (e.g., boolean flags)</li><li>Tables that are write-heavy with rare reads</li></ul><h3>Composite Indexes</h3><p>A composite index covers <em>multiple columns</em>. The column order matters:</p><pre><code>CREATE INDEX idx_user_status ON orders (user_id, status);</code></pre><blockquote><p>The "leftmost prefix" rule means this index helps queries filtering by <code>user_id</code> alone, or <code>user_id</code> + <code>status</code>, but <strong>not</strong> <code>status</code> alone.</p></blockquote><hr><p>Run <code>EXPLAIN</code> on your slow queries to verify indexes are being used effectively.</p>',
            ],
            [
                'title' => 'A Guide to Code Review Best Practices',
                'content' => '<h2>Why Code Reviews Matter</h2><p>Code reviews catch bugs, spread knowledge, and maintain consistency. But a <em>bad</em> review process can slow teams down and damage morale.</p><h3>For Reviewers</h3><ol><li><strong>Be kind.</strong> Critique the code, not the person</li><li><strong>Be specific.</strong> Say <em>what</em> to change and <em>why</em></li><li><strong>Be timely.</strong> Review within 24 hours when possible</li><li><strong>Pick your battles.</strong> Not every style preference is worth debating</li></ol><h3>For Authors</h3><ol><li>Keep pull requests small — under 400 lines if possible</li><li>Write a clear description explaining the <em>why</em></li><li>Self-review before requesting others</li><li>Don\'t take feedback personally</li></ol><blockquote><p>"Ask a programmer to review 10 lines of code, they\'ll find 10 issues. Ask them to review 500 lines, they\'ll say it looks good."</p></blockquote><h3>What to Look For</h3><ul><li>Logic errors and edge cases</li><li>Security vulnerabilities (SQL injection, XSS)</li><li>Performance concerns (N+1 queries, unnecessary loops)</li><li>Test coverage for new behavior</li></ul>',
            ],
            [
                'title' => 'Designing RESTful APIs That Developers Love',
                'content' => '<h2>Principles of Good API Design</h2><p>A well-designed API is <strong>intuitive</strong>, <strong>consistent</strong>, and <strong>forgiving</strong>. Here are the patterns that make developers reach for your API first.</p><h3>Use Nouns, Not Verbs</h3><pre><code>GET    /api/users          (list)\nGET    /api/users/123       (show)\nPOST   /api/users           (create)\nPUT    /api/users/123       (update)\nDELETE /api/users/123       (destroy)</code></pre><h3>Consistent Error Responses</h3><p>Always return errors in a predictable format:</p><pre><code>{\n  "message": "Validation failed",\n  "errors": {\n    "email": ["The email field is required."]\n  }\n}</code></pre><h3>Pagination</h3><p>Never return unbounded lists. Use <em>cursor-based</em> or <em>offset-based</em> pagination:</p><ul><li><strong>Offset:</strong> Simple but slow on large datasets</li><li><strong>Cursor:</strong> Performant and consistent, preferred for feeds</li></ul><hr><h3>Versioning</h3><p>Version your API from day one. The two common approaches:</p><ol><li>URL path: <code>/api/v1/users</code></li><li>Header: <code>Accept: application/vnd.api.v1+json</code></li></ol><blockquote><p>Breaking changes should always go in a new version. Adding fields is not a breaking change.</p></blockquote>',
            ],
            [
                'title' => 'The Art of Writing Clean CSS',
                'content' => '<h2>Why Clean CSS Matters</h2><p>CSS is deceptively simple to write and <strong>incredibly hard</strong> to maintain. A codebase with messy CSS becomes a minefield where changing one rule breaks three others.</p><h3>Naming Conventions</h3><p>Adopt a methodology like <em>BEM</em> (Block Element Modifier):</p><pre><code>.card { }\n.card__title { }\n.card__title--highlighted { }</code></pre><h3>Avoid These Anti-Patterns</h3><ul><li>Using <code>!important</code> as a fix for specificity issues</li><li>Deeply nested selectors (more than 3 levels)</li><li>Magic numbers like <code>margin-top: 37px</code></li><li>Styling IDs instead of classes</li></ul><h3>Modern CSS Features Worth Using</h3><ol><li><strong>CSS Custom Properties</strong> for theming and consistency</li><li><strong>CSS Grid</strong> for two-dimensional layouts</li><li><strong>Container queries</strong> for truly reusable components</li><li><strong><code>:has()</code> selector</strong> for parent-based styling</li></ol><blockquote><p>The best CSS is the CSS you don\'t have to write. Utility frameworks like <strong>Tailwind CSS</strong> eliminate entire categories of maintenance burden.</p></blockquote><hr><p>Remember: every line of CSS you write is a line someone else has to understand later.</p>',
            ],
            [
                'title' => 'Testing Strategies for Modern Applications',
                'content' => '<h2>The Testing Pyramid</h2><p>Not all tests are created equal. A healthy test suite follows the <strong>testing pyramid</strong>:</p><ol><li><strong>Unit tests</strong> (base) — fast, isolated, many of them</li><li><strong>Integration tests</strong> (middle) — test component interactions</li><li><strong>End-to-end tests</strong> (top) — few, slow, high confidence</li></ol><h3>What Makes a Good Test?</h3><p>A good test is:</p><ul><li><strong>Fast</strong> — if tests are slow, developers won\'t run them</li><li><strong>Deterministic</strong> — same input, same result, every time</li><li><strong>Independent</strong> — no test should depend on another</li><li><strong>Readable</strong> — the test name should describe the expected behavior</li></ul><h3>Test Naming</h3><p>Use descriptive names that read like specifications:</p><pre><code>public function test_guest_cannot_access_dashboard(): void\npublic function test_admin_can_delete_users(): void\npublic function test_order_total_includes_shipping(): void</code></pre><blockquote><p>Write the test name <em>before</em> the implementation. If you can\'t name it clearly, you don\'t understand the requirement yet.</p></blockquote><hr><h3>When to Skip Tests</h3><p>Tests aren\'t free. <em>Don\'t</em> test:</p><ul><li>Framework internals (trust Laravel\'s own tests)</li><li>Simple getters and setters with no logic</li><li>Third-party packages you don\'t control</li></ul>',
            ],
            [
                'title' => 'Deploying with Zero Downtime',
                'content' => '<h2>Why Zero Downtime?</h2><p>Every minute of downtime costs money and erodes user trust. Modern deployment strategies let you ship code <strong>without taking your application offline</strong>.</p><h3>Blue-Green Deployments</h3><p>Run two identical production environments:</p><ol><li><strong>Blue</strong> serves live traffic</li><li><strong>Green</strong> receives the new deployment</li><li>Once verified, swap the router to <strong>Green</strong></li><li>Blue becomes your instant rollback target</li></ol><h3>Rolling Deployments</h3><p>Update instances <em>gradually</em>, one at a time:</p><ul><li>Works well with container orchestration (Kubernetes, ECS)</li><li>Lower resource overhead than blue-green</li><li>Slower rollback compared to blue-green</li></ul><h3>Database Migrations</h3><p>The trickiest part of zero-downtime deploys is <strong>database changes</strong>. Follow these rules:</p><blockquote><p>Never rename or remove a column in a single deploy. Instead: <strong>add the new column → migrate data → deploy code using the new column → remove the old column.</strong></p></blockquote><pre><code>// Step 1: Add column\nSchema::table(\'users\', function (Blueprint $table) {\n    $table->string(\'full_name\')->nullable();\n});</code></pre><hr><p>Pair your deployment pipeline with <strong>health checks</strong> and <strong>automated rollbacks</strong> for maximum safety.</p>',
            ],
            [
                'title' => 'Understanding SOLID Principles',
                'content' => '<h2>What Is SOLID?</h2><p>SOLID is a set of five design principles that help developers write <strong>maintainable, flexible, and understandable</strong> code. Let\'s break each one down.</p><h3>S — Single Responsibility</h3><p>A class should have <em>one reason to change</em>. If your <code>UserController</code> handles authentication, profile updates, and email notifications, it has too many responsibilities.</p><h3>O — Open/Closed</h3><p>Classes should be <strong>open for extension</strong> but <strong>closed for modification</strong>. Use interfaces and abstract classes to allow new behavior without changing existing code.</p><h3>L — Liskov Substitution</h3><p>Subtypes must be substitutable for their base types without breaking the program. If <code>Square extends Rectangle</code>, setting width shouldn\'t change height.</p><h3>I — Interface Segregation</h3><p>Don\'t force classes to implement interfaces they don\'t use:</p><ul><li><em>Bad:</em> One massive <code>WorkerInterface</code> with 20 methods</li><li><em>Good:</em> Small, focused interfaces like <code>CanSendEmail</code> and <code>CanGenerateReport</code></li></ul><h3>D — Dependency Inversion</h3><p>Depend on <em>abstractions</em>, not <em>concretions</em>:</p><pre><code>// Good: depends on interface\npublic function __construct(private PaymentGateway $gateway) {}\n\n// Bad: depends on concrete class\npublic function __construct(private StripeGateway $gateway) {}</code></pre><blockquote><p>SOLID principles are guidelines, not laws. Apply them where they reduce complexity — don\'t over-engineer simple code to satisfy a principle.</p></blockquote>',
            ],
            [
                'title' => 'A Practical Guide to Git Branching Strategies',
                'content' => '<h2>Choosing the Right Strategy</h2><p>There\'s no single "correct" branching strategy. The right choice depends on your <strong>team size</strong>, <strong>release cadence</strong>, and <strong>deployment process</strong>.</p><h3>Trunk-Based Development</h3><p>Everyone commits to <code>main</code> with short-lived feature branches (< 1 day):</p><ul><li><strong>Pros:</strong> Fast integration, fewer merge conflicts, encourages small changes</li><li><strong>Cons:</strong> Requires strong CI/CD, feature flags for incomplete work</li><li><strong>Best for:</strong> Teams with mature testing and continuous deployment</li></ul><h3>GitHub Flow</h3><p>A simplified workflow:</p><ol><li>Create a branch from <code>main</code></li><li>Make changes and open a pull request</li><li>Review, discuss, and test</li><li>Merge to <code>main</code> and deploy</li></ol><h3>Git Flow</h3><p>A more structured approach with <code>develop</code>, <code>release</code>, and <code>hotfix</code> branches:</p><blockquote><p>Git Flow adds overhead. Only use it if you need to maintain multiple release versions simultaneously.</p></blockquote><hr><h3>Branch Naming Conventions</h3><p>Consistent names make history readable:</p><pre><code>feature/user-authentication\nbugfix/login-redirect-loop\nhotfix/payment-rounding-error\nchore/upgrade-laravel-12</code></pre><p>Whatever strategy you choose, <strong>document it</strong> and make sure the whole team follows it.</p>',
            ],
        ];
    }
}
