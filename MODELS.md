# Filament Demo — Models & Relationships

This document maps every Eloquent model in the Filament Demo, their relationships, and how data flows through the three business modules: **Shop**, **Blog**, and **HR**.

---

## Architecture Overview

```
app/Models/
├── User.php              ← Auth user, multi-tenant via Team
├── Team.php              ← Tenant for multi-tenancy
├── Address.php           ← Polymorphic (shared by Customer, Brand)
├── Comment.php           ← Polymorphic (shared by Post, Product)
├── Shop/
│   ├── Brand.php
│   ├── ProductCategory.php
│   ├── Product.php
│   ├── Customer.php
│   ├── Order.php
│   ├── OrderItem.php
│   ├── OrderAddress.php
│   └── Payment.php
├── Blog/
│   ├── Author.php
│   ├── PostCategory.php
│   └── Post.php
└── HR/
    ├── Department.php
    ├── Employee.php
    ├── LeaveRequest.php
    ├── Project.php
    ├── Task.php
    ├── Timesheet.php
    ├── Expense.php
    └── ExpenseLine.php
```

**Total: 23 models** (8 Shop, 3 Blog, 8 HR, 4 Shared)

---

## Shared Models

### User (`app/Models/User.php`)

The authenticated admin user. Implements Filament's multi-tenancy via Teams.

| Relationship | Type | Target | Description |
|---|---|---|---|
| `teams()` | BelongsToMany | Team | Teams the user belongs to (pivot: `team_user`) |

### Team (`app/Models/Team.php`)

Multi-tenancy tenant. Each team can have multiple users.

| Relationship | Type | Target | Description |
|---|---|---|---|
| `users()` | BelongsToMany | User | Users in this team (pivot: `team_user`) |

### Address (`app/Models/Address.php`)

Polymorphic address shared across modules via the `addressables` pivot table.

| Relationship | Type | Target | Description |
|---|---|---|---|
| `customers()` | MorphedByMany | Customer | Customers at this address |
| `brands()` | MorphedByMany | Brand | Brands at this address |

### Comment (`app/Models/Comment.php`)

Polymorphic comment — can be attached to Products or Posts.

| Relationship | Type | Target | Description |
|---|---|---|---|
| `customer()` | BelongsTo | Customer | The customer who wrote this comment |
| `commentable()` | MorphTo | Post or Product | The thing being commented on |

---

## Shop Module

### Brand

Products are organized by brand. Brands can have media (logos) and addresses.

| Relationship | Type | Target | Description |
|---|---|---|---|
| `products()` | HasMany | Product | All products under this brand |
| `addresses()` | MorphToMany | Address | Brand addresses (via `addressables`) |

### ProductCategory

Hierarchical (self-referencing) product categories. A category can have a parent and children.

| Relationship | Type | Target | Description |
|---|---|---|---|
| `parent()` | BelongsTo | ProductCategory | Parent category |
| `children()` | HasMany | ProductCategory | Sub-categories |
| `products()` | BelongsToMany | Product | Products in this category (pivot: `product_category_product`) |

### Product

The core Shop entity. Belongs to a brand, can be in multiple categories, has media and comments.

| Relationship | Type | Target | Description |
|---|---|---|---|
| `brand()` | BelongsTo | Brand | The brand this product belongs to |
| `productCategories()` | BelongsToMany | ProductCategory | Categories (pivot: `product_category_product`) |
| `comments()` | MorphMany | Comment | Customer comments on this product |

**Key fields:** `featured`, `is_visible`, `backorder`, `requires_shipping`, `published_at`

### Customer

A shop customer who places orders and leaves comments. Supports soft deletes.

| Relationship | Type | Target | Description |
|---|---|---|---|
| `addresses()` | MorphToMany | Address | Customer addresses (via `addressables`) |
| `comments()` | HasMany | Comment | Comments written by this customer |
| `orders()` | HasMany | Order | Orders placed by this customer |
| `payments()` | HasManyThrough | Payment | All payments (through Order) |

### Order

An order placed by a customer, containing line items and payments. Supports soft deletes.

| Relationship | Type | Target | Description |
|---|---|---|---|
| `customer()` | BelongsTo | Customer | Who placed this order |
| `orderItems()` | HasMany | OrderItem | Line items in this order |
| `payments()` | HasMany | Payment | Payments made for this order |
| `address()` | MorphOne | OrderAddress | Shipping/billing address |

**Status flow:** `New` → `Processing` → `Shipped` → `Delivered` (or `Cancelled`)

### OrderItem

A line item linking an Order to a Product with quantity and price.

| Relationship | Type | Target | Description |
|---|---|---|---|
| `order()` | BelongsTo | Order | The parent order |
| `product()` | BelongsTo | Product | The product being ordered |

### OrderAddress

Polymorphic address specifically for orders (separate from the shared Address model).

| Relationship | Type | Target | Description |
|---|---|---|---|
| `addressable()` | MorphTo | Order | The order this address belongs to |

### Payment

A payment made against an order.

| Relationship | Type | Target | Description |
|---|---|---|---|
| `order()` | BelongsTo | Order | The order being paid for |

---

### Shop Data Flow: Creating an Order

```
Customer ──places──→ Order
                       ├── OrderItem ──references──→ Product ──belongs to──→ Brand
                       ├── OrderItem ──references──→ Product       └── in ──→ ProductCategory
                       ├── Payment
                       └── OrderAddress

Status: New → Processing → Shipped → Delivered
```

1. A **Customer** creates an **Order** (status: `New`)
2. **OrderItems** are added, each referencing a **Product** (with qty, unit price)
3. An **OrderAddress** is attached (shipping/billing)
4. **Payments** are recorded against the Order
5. Order status progresses: New → Processing → Shipped → Delivered

---

## Blog Module

### Author

A blog author who writes posts.

| Relationship | Type | Target | Description |
|---|---|---|---|
| `posts()` | HasMany | Post | Posts written by this author |

### PostCategory

Blog post categories (flat — no hierarchy unlike ProductCategory).

| Relationship | Type | Target | Description |
|---|---|---|---|
| `posts()` | HasMany | Post | Posts in this category |

### Post

A blog post with rich content. Supports media, tags (via Spatie), and polymorphic comments.

| Relationship | Type | Target | Description |
|---|---|---|---|
| `author()` | BelongsTo | Author | Who wrote this post |
| `postCategory()` | BelongsTo | PostCategory | Category this post belongs to |
| `comments()` | MorphMany | Comment | Comments on this post |

**Also uses:** `HasTags` (Spatie) for tagging, `HasMedia` (Spatie) for images

---

### Blog Data Flow: Publishing a Post

```
Author ──writes──→ Post ──categorized in──→ PostCategory
                     ├── Tags (Spatie)
                     ├── Media/Images (Spatie)
                     └── Comments ──written by──→ Customer
```

1. An **Author** writes a **Post** (with title, slug, content, SEO description)
2. The post is assigned to a **PostCategory**
3. **Tags** are attached via Spatie Tags
4. An **Image** is uploaded via Spatie Media Library
5. **Customers** (from the Shop module) can leave **Comments** on the post

---

## HR Module

### Department

Organizational unit. Self-referencing hierarchy (parent/child departments).

| Relationship | Type | Target | Description |
|---|---|---|---|
| `parent()` | BelongsTo | Department | Parent department |
| `children()` | HasMany | Department | Sub-departments |
| `employees()` | HasMany | Employee | Employees in this department |
| `projects()` | HasMany | Project | Projects owned by this department |

### Employee

An employee in the organization. The central HR entity. Supports soft deletes.

| Relationship | Type | Target | Description |
|---|---|---|---|
| `department()` | BelongsTo | Department | Which department they're in |
| `leaveRequests()` | HasMany | LeaveRequest | Leave requests submitted by this employee |
| `approvedLeaveRequests()` | HasMany | LeaveRequest | Leave requests this employee approved (as `approver_id`) |
| `tasks()` | HasMany | Task | Tasks assigned to this employee |
| `timesheets()` | HasMany | Timesheet | Time entries logged by this employee |
| `expenses()` | HasMany | Expense | Expense reports submitted by this employee |

**Key fields:** `employment_type` (enum), `is_active`, `skills` (JSON), `salary`, `hourly_rate`, `date_of_birth`, `hire_date`

### LeaveRequest

An employee's request for time off.

| Relationship | Type | Target | Description |
|---|---|---|---|
| `employee()` | BelongsTo | Employee | Who's requesting leave |
| `approver()` | BelongsTo | Employee | Who reviews the request (`approver_id`) |

**Status flow:** `Pending` → `Approved` → `Taken` (or `Rejected` / `Cancelled`)

### Project

A project owned by a department, with tasks, timesheets, and expenses. Supports soft deletes.

| Relationship | Type | Target | Description |
|---|---|---|---|
| `department()` | BelongsTo | Department | Owning department |
| `tasks()` | HasMany | Task | Tasks within this project |
| `timesheets()` | HasMany | Timesheet | Time logged to this project |
| `expenses()` | HasMany | Expense | Expenses charged to this project |

**Status flow:** `Planning` → `Active` → `Completed` (or `On Hold` / `Cancelled`)

### Task

A unit of work within a project, assigned to an employee.

| Relationship | Type | Target | Description |
|---|---|---|---|
| `project()` | BelongsTo | Project | Parent project |
| `assignee()` | BelongsTo | Employee | Employee assigned to this task (`assigned_to`) |
| `timesheets()` | HasMany | Timesheet | Time logged against this task |

**Status flow:** `Backlog` → `Todo` → `In Progress` → `In Review` → `Completed` (or `Cancelled`)

### Timesheet

Time logged by an employee against a project and task.

| Relationship | Type | Target | Description |
|---|---|---|---|
| `employee()` | BelongsTo | Employee | Who logged the time |
| `task()` | BelongsTo | Task | Which task the time is for |
| `project()` | BelongsTo | Project | Which project the time is for |

**Key fields:** `date`, `hours`, `hourly_rate`, `total_cost`, `is_billable`

### Expense

An expense report submitted by an employee, with individual line items.

| Relationship | Type | Target | Description |
|---|---|---|---|
| `employee()` | BelongsTo | Employee | Who submitted the expense |
| `project()` | BelongsTo | Project | Which project to charge |
| `approvedByEmployee()` | BelongsTo | Employee | Who approved it (`approved_by`) |
| `expenseLines()` | HasMany | ExpenseLine | Individual expense line items |

**Status flow:** `Draft` → `Submitted` → `Approved` → `Reimbursed` (or `Rejected`)

### ExpenseLine

An individual line item within an expense report.

| Relationship | Type | Target | Description |
|---|---|---|---|
| `expense()` | BelongsTo | Expense | Parent expense report |

**Key fields:** `amount`, `unit_price`, `date`

---

### HR Data Flow: Submitting an Expense

```
Department ──owns──→ Project ──has──→ Task ──assigned to──→ Employee
                       │                                       │
                       │              ┌── ExpenseLine           │
                       │              ├── ExpenseLine           │
                       └── Expense ←──┤── ExpenseLine ←──submitted by──┘
                             │
                             └── approved by ──→ Employee (approver)

Status: Draft → Submitted → Approved → Reimbursed
```

1. An **Employee** creates an **Expense** report (status: `Draft`)
2. **ExpenseLines** are added (description, amount, date)
3. The expense is linked to a **Project** (for budget tracking)
4. Employee submits → status becomes `Submitted`
5. An approver **Employee** reviews → `Approved` or `Rejected`
6. Finance reimburses → `Reimbursed`

---

## Cross-Module Connections

The three modules are connected through these shared patterns:

| Pattern | How It Works |
|---|---|
| **Polymorphic Comments** | `Comment` can belong to either a `Post` (Blog) or `Product` (Shop). A `Customer` (Shop) writes the comment. This means the Blog and Shop share a commenting system. |
| **Polymorphic Addresses** | `Address` is shared between `Customer` and `Brand` via the `addressables` pivot table. `OrderAddress` is separate (morph on Order). |
| **Multi-tenancy** | `User` ↔ `Team` (many-to-many). All panels share the same User auth. Teams provide data isolation. |

---

## Enums (Status Values)

| Enum | Values | Used By |
|---|---|---|
| `OrderStatus` | New, Processing, Shipped, Delivered, Cancelled | Order |
| `ExpenseStatus` | Draft, Submitted, Approved, Rejected, Reimbursed | Expense |
| `LeaveStatus` | Pending, Approved, Rejected, Taken, Cancelled | LeaveRequest |
| `TaskStatus` | Backlog, Todo, InProgress, InReview, Completed, Cancelled | Task |
| `ProjectStatus` | Planning, Active, OnHold, Completed, Cancelled | Project |
| `EmploymentType` | — | Employee |
| `ExpenseCategory` | — | Expense |
| `LeaveType` | — | LeaveRequest |
| `TaskPriority` | — | Task, Project |
| `CurrencyCode` | — | Order, Payment |
| `CountryCode` | — | Address, OrderAddress |
| `PaymentMethod` | — | Payment |

All status enums implement Filament's `HasLabel`, `HasColor`, and `HasIcon` contracts, meaning the UI automatically renders colored badges with icons.

---

## Key Design Patterns

1. **Soft Deletes** — Used on: Order, Customer, Employee, Project. Records are never truly deleted.
2. **Polymorphic Relations** — Comment and Address are shared across modules, avoiding table duplication.
3. **Self-Referencing Hierarchy** — Both ProductCategory and Department support parent/child nesting.
4. **Spatie Packages** — Media Library (Product, Post, Brand, ProductCategory images) and Tags (Post tags).
5. **Enums with UI Contracts** — Every status field uses a PHP enum that defines its label, color, and icon.
6. **HasManyThrough** — Customer → payments flows through Order for direct access without intermediate queries.
