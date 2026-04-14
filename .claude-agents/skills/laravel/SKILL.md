# Laravel Skill

Comprehensive Laravel and PHP development patterns and best practices.

## What I Know

### Laravel Patterns

**Service Layer Pattern**
```php
// app/Services/UserService.php
class UserService
{
    public function __construct(
        private UserRepository $repository,
        private EmailVerifier $verifier
    ) {}

    public function createUser(array $data): User
    {
        $user = $this->repository->create($data);
        $this->verifier->sendVerificationLink($user);
        return $user;
    }
}

// Use in controller
public function store(CreateUserRequest $request, UserService $service)
{
    $user = $service->createUser($request->validated());
    return response()->json($user, 201);
}
```

**Repository Pattern**
```php
// app/Repositories/UserRepository.php
class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    public function getActiveUsers(): Collection
    {
        return $this->model->where('active', true)->get();
    }
}
```

### Form Requests

**Validation**
```php
// app/Http/Requests/StoreUserRequest.php
class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', User::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:8', 'confirmed'],
        ];
    }
}
```

### Resources & API Responses

**API Resources**
```php
// app/Http/Resources/UserResource.php
class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}

// Use in controller
public function index(UserRepository $repository)
{
    $users = $repository->all();
    return UserResource::collection($users);
}
```

### Eloquent Best Practices

**Query Scopes**
```php
// app/Models/User.php
class User extends Model
{
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeWithEmail($query, string $email)
    {
        return $query->where('email', $email);
    }
}

// Usage
$activeUsers = User::active()->get();
$user = User::withEmail('user@example.com')->first();
```

**Relationships**
```php
class User extends Model
{
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function latestPost(): HasOne
    {
        return $this->hasOne(Post::class)->latestOfMany();
    }
}

class Post extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }
}
```

### Filament PHP

**Admin Panel**
```php
// app/Filament/Resources/UserResource.php
class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(User::class),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrated()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\BooleanColumn::make('active'),
            ])
            ->filters([
                Tables\Filters\Filter::make('active')
                    ->query(fn ($query) => $query->where('active', true)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
```

### Laravel Best Practices

**1. Use Config & Environment Variables**
```php
// config/services.php
return [
    'api_key' => env('API_KEY'),
];

// Usage
$config = config('services.api_key');
```

**2. Use Service Providers**
```php
// app/Providers/AppServiceProvider.php
public function register(): void
{
    $this->app->singleton(UserService::class);
}
```

**3. Use Middleware**
```php
// app/Http/Middleware/EnsureTokenIsValid.php
public function handle(Request $request, Closure $next)
{
    if (!$request->hasHeader('Authorization')) {
        return response()->json(['error' => 'Token missing'], 401);
    }

    return $next($request);
}
```

**4. Use Queues for Long Tasks**
```php
// app/Jobs/SendEmail.php
class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private User $user) {}

    public function handle(Mailer $mailer): void
    {
        $mailer->to($this->user->email)->send(new WelcomeEmail());
    }
}

// Dispatch
SendEmail::dispatch($user);
```

**5. Use Events & Listeners**
```php
// app/Events/UserRegistered.php
class UserRegistered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public User $user) {}
}

// app/Listeners/SendWelcomeEmail.php
class SendWelcomeEmail
{
    public function handle(UserRegistered $event): void
    {
        Mail::to($event->user->email)->send(new WelcomeEmail());
    }
}

// app/Providers/EventServiceProvider.php
protected $listen = [
    UserRegistered::class => [
        SendWelcomeEmail::class,
    ],
];
```

---

*Part of SuperAI GitHub - Centralized Claude Code Configuration*