# Filament Demo

A full-featured demo app built with Filament. It covers a realistic spread of admin panel patterns across three modules, an e-commerce shop, a blog, and an HR suite, so you can see how Filament handles real-world use cases.

## Getting started

Clone and install:

```sh
git clone https://github.com/filamentphp/demo.git filament-demo && cd filament-demo
composer install
composer setup
```

Start the dev server:

```sh
php artisan serve
```

Then log in at the URL shown in the terminal:

- **Email:** admin@filamentphp.com
- **Password:** password

## What's in the box

### Shop

Products, orders, customers, brands, and categories: the kind of CRUD you'd find in any e-commerce admin. The shop module demonstrates:

- **Order wizard**: multi-step create form with inline customer creation
- **Repeaters**: line items on orders and expenses with reactive totals
- **Query builder filters**: advanced filtering with text, number, date, and boolean constraints
- **Column summarizers**: footer totals for prices and quantities
- **Table grouping**: group orders by status, customer, or date
- **Drag-and-drop reordering**: sortable brand list
- **Media uploads**: multiple product images via Spatie Media Library
- **Soft deletes**: restore and force-delete on orders and customers
- **Dashboard**: filterable stats, charts, and a latest orders widget with live polling

### Blog

Posts, authors, and categories with a focus on content management patterns:

- **Rich text editing**: WYSIWYG content editor with prose rendering on the view page
- **Sub-navigation**: tabbed navigation between View, Edit, and Comments on posts
- **Manage related records**: dedicated comments page without leaving the post context
- **Tags**: Spatie Tags integration on posts
- **Import and export actions**: CSV import on categories, export on authors
- **Column layouts**: split and stack layouts on the authors table
- **Simple resources**: authors and categories managed with modals, no separate pages

### HR

Employees, departments, projects, tasks, timesheets, leave requests, and expenses: a more complex module showing how Filament scales:

- **Tabbed forms**: Personal, Employment, and Documents tabs on employees
- **Builder blocks**: milestone, task group, and checkpoint blocks in the project plan
- **Conditional fields**: salary vs. hourly rate based on employment type
- **Inline editing**: change leave request status directly in the table
- **Status workflows**: expense approval, rejection, and reimbursement actions
- **Expense line items**: repeatable entries with table layout in the infolist
- **Key-value editor**: freeform metadata on employees
- **Checkbox lists**: multi-select skills grid
- **Dashboard**: headcount stats, leave overview, timesheet trends, and budget charts

## Patterns worth looking at

Here are some specific things to poke at if you're learning Filament:

| Pattern | Where to find it |
|---|---|
| Wizard form | Create a new order |
| Reactive calculations | Edit an expense's line items |
| Builder blocks | Edit a project's Plan tab |
| Action groups with custom actions | Any table row with "..." menu |
| Slide-over modals | Ship an order |
| Modal forms with actions | Send email to a customer |
| Infolist with repeatable entries | View an expense |
| Sub-navigation | View or edit any post |
| Conditional field visibility | Change employment type on an employee |
| Dashboard filters | Shop dashboard date range and customer type |
| Global search | Press Cmd+K anywhere |
| Keyboard shortcuts | Cmd+Shift+P to quick-publish a post |
| Navigation badges | Check sidebar counts on orders, leave requests, and expenses |

## Callout playground

Open `/callout-playground` after signing in to compare the Blade callout with plain-JavaScript React, Vue, and Svelte consumers. Change the color (including the registered `brand` palette), icon artwork, independent icon color and size, description, and actions. Review and dismiss operate only on local state; Reset restores the notice. No release is published.

The framework examples import Support's `Callout` from the Composer path package and use the existing Filament theme. Run `npm run build` after changing them. Each renderer unmounts through `HasJsRenderer` when its host is removed. For browser checks, scope selectors to `[data-framework="react"]`, `vue`, `svelte`, or `blade`, then use `data-testid="callout"`, `color`, `icon`, `override`, `details`, `actions`, `review`, `dismiss`, `result`, and `reset`.

## Badge playground

Open `/badge-playground` after signing in to compare Blade with plain-JavaScript React, Vue, and Svelte Badge widgets. Each card shows sizes, a registered `brand` color, icons before/after the label, a local fragment link, an external-form submit badge, and a removable filter. The JavaScript cards expose explicit save/delete loading, disabled actions, tooltip/shortcut removal, and mounting/unmounting. Finish completes pending work; Reset restores local state. No database records change.

Use Alt+B for Blade, Alt+R for React, Alt+V for Vue, and Alt+S for Svelte, including while the filter input has focus. Disabled tooltip badges remain focusable but cannot activate. The host owns loading, labels, and navigation; these examples do not call PHP Actions or infer Livewire loading.

Run `npm run build` and `php artisan filament:assets` after setup. Vite deduplicates framework runtimes because the Composer path packages are symlinks to a Filament checkout with its own dependencies. Scope checks to `[data-framework="react"]`, `vue`, or `svelte`, then use `data-testid="palette"`, `interactive-badges`, `project-link`, `save-badge`, `delete-badge` (its native `button` deletes), `filter-name`, `finish`, `reset`, `disabled`, `enhanced`, `visible`, and `result`.
