# Implementation Plan: SSLCommerz + RBAC + Enhanced CRUD

## User Requirements Summary
1. **Donations**: User + project-wise. Must be logged in to donate.
2. **SSLCommerz**: Payment gateway integration for donations.
3. **User Dashboard** (after login): Full donation history table + yearly chart showing how many donations per project per year.
4. **Admin Panel**: Header + collapsible sidebar, RBAC (admin/manager/editor/user), CRUD with filter/search/pagination on all entities.
5. **Bilingual**: All admin pages continue to support EN/BN.

---

## Phase 1: Backend

### 1.1 SSLCommerz Integration

**Files to create:**
- `config/sslcommerz.php` — sandbox/live config, store_id, store_pass, URLs
- `app/Services/SSLCommerzService.php` — `initiate()`, `validate()` methods calling SSLCommerz API via Http facade
- `app/Http/Controllers/SSLCommerzController.php` — handles success/cancel/fail/ipn callbacks
- `database/migrations/XXXX_add_sslcommerz_fields_to_donations.php`

**Files to modify:**
- `app/Models/Donation.php` — add fillable fields: `bank_tran_id`, `card_type`, `card_issuer`, `card_brand`, `card_issuer_country`, `tran_date`, `currency_amount`, `currency_type`. Update `status` casts to include `pending`, `processing`, `completed`, `failed`, `cancelled`.
- `app/Http/Controllers/DonationController.php` — replace immediate completion with SSLCommerz flow:
  - `store()` → renamed to `initiate()`: creates Donation with `status='pending'`, calls `SSLCommerzService::initiate()`, returns `redirect_url` to frontend
  - Add `scopePending()` to Donation model
- `routes/api.php` — add:
  ```
  POST /api/donations/initiate          (auth required)
  POST /api/sslcommerz/success          (no auth)
  POST /api/sslcommerz/cancel           (no auth)
  POST /api/sslcommerz/fail             (no auth)
  POST /api/sslcommerz/ipn              (no auth)
  ```

**Donation flow:**
1. User (logged in) selects project + amount → clicks Donate
2. Frontend calls `POST /api/donations/initiate` with project_id, amount
3. Server: creates Donation record (`status=pending`, `transaction_id=TXN-xxx`), calls SSLCommerz API
4. SSLCommerz returns `GatewayPageURL` → server returns it to frontend
5. Frontend redirects browser to SSLCommerz payment page
6. User pays → SSLCommerz calls `success_url` (server-side)
7. Server validates transaction via `SSLCommerzService::validate(val_id)`
8. On success: updates Donation (`status=completed`, fills sslcommerz fields)
9. Browser redirects to `{APP_URL}/donations/success?tran_id=xxx`
10. On cancel/fail: updates Donation status, redirects to appropriate page

**Migration fields to add to `donations`:**
```php
$table->string('bank_tran_id')->nullable()->after('transaction_id');
$table->string('card_type')->nullable();
$table->string('card_no')->nullable();
$table->string('card_issuer')->nullable();
$table->string('card_brand')->nullable();
$table->string('card_issuer_country')->nullable();
$table->string('currency_amount')->nullable();
$table->string('currency_type')->nullable();
$table->string('tran_date')->nullable();
$table->string('val_id')->nullable();
// Also add donor fields for non-logged-in fallback:
$table->string('donor_name')->nullable()->change();
$table->string('donor_email')->nullable()->change();
```

### 1.2 RBAC (Multi-role)

**Files to create:**
- `database/migrations/XXXX_create_roles_table.php`
- `database/migrations/XXXX_create_permissions_table.php`
- `database/migrations/XXXX_create_permission_role_table.php`
- `database/migrations/XXXX_create_role_user_table.php`
- `app/Models/Role.php`
- `app/Models/Permission.php`
- `app/Http/Middleware/PermissionMiddleware.php`
- `database/seeders/RolePermissionSeeder.php`
- `app/Http/Controllers/Admin/RoleController.php` (CRUD for roles + permissions)

**Files to modify:**
- `app/Models/User.php` — add `roles()` BelongsToMany, `permissions()` through roles, `hasPermission($permission)` method, `hasRole($role)` method
- `bootstrap/app.php` — register `permission` middleware alias
- `routes/api.php` — add admin role/permission routes (under auth+admin middleware)

**Roles & Permissions:**
```
Roles: admin, manager, editor, user

Permissions (string-based):
- projects.view, projects.create, projects.edit, projects.delete
- donations.view, donations.edit
- gallery.view, gallery.create, gallery.edit, gallery.delete
- videos.view, videos.create, videos.edit, videos.delete
- cms.view, cms.create, cms.edit, cms.delete
- users.view, users.create, users.edit, users.delete
- roles.view, roles.create, roles.edit, roles.delete
- dashboard.view

Role → Permission mapping:
  admin:     all (*)
  manager:   dashboard.view, projects.*, donations.*, gallery.*, videos.*, cms.*, users.view
  editor:    dashboard.view, projects.view, projects.edit, gallery.*, videos.*, cms.*
  user:      dashboard.view (frontend only, not admin)
```

**Migration schemas:**
```php
// roles: id, name(string unique), label(string nullable), guard_name(string default='web'), timestamps
// permissions: id, name(string unique), label(string nullable), guard_name(string default='web'), timestamps
// permission_role: permission_id, role_id
// role_user: role_id, user_id
```

Keep the existing `role` column on users for backward compat. The pivot table approach is the primary mechanism.

**Middleware** (`PermissionMiddleware`):
```php
// handle(): check if user has any of the required permissions (comma-separated)
// usage: Route::get(...)->middleware('permission:projects.view')
```

**Admin RoleController routes:**
```
GET    /api/admin/roles          → index (paginated, searchable)
POST   /api/admin/roles          → store
GET    /api/admin/roles/{id}     → show (with permissions)
PUT    /api/admin/roles/{id}     → update (sync permissions)
DELETE /api/admin/roles/{id}     → destroy
```

### 1.3 Enhanced CRUD API — Filter + Paginate + Search

**Files to modify** (all admin controllers):
- `Admin/ProjectController.php`
- `Admin/DonationController.php`
- `Admin/GalleryController.php`
- `Admin/VideoController.php`
- `Admin/CmsPageController.php`
- `Admin/UserController.php`

**Pattern for each controller** (add query params support):
```php
$query = Model::query();

// Search
if ($search = $request->search) {
    $query->where(function($q) use ($search) {
        $q->where('title', 'like', "%{$search}%")
          ->orWhere('title_bn', 'like', "%{$search}%");
    });
}

// Filter
if ($request->status) {
    $query->where('status', $request->status);
}
if ($request->project_id) {
    $query->where('project_id', $request->project_id);
}

// Sort
$sortBy = $request->sort_by ?? 'created_at';
$sortDir = $request->sort_dir ?? 'desc';
$query->orderBy($sortBy, $sortDir);

// Paginate
$perPage = min((int)($request->per_page ?? 15), 100);
return response()->json($query->paginate($perPage));
```

**DonationController** (frontend):
- Add `yearlyStats()` endpoint returning per-year, per-project donation counts:
  ```php
  Donation::completed()
    ->where('user_id', $request->user()->id)
    ->selectRaw("YEAR(created_at) as year, project_id, COUNT(*) as count, SUM(amount) as total")
    ->groupBy('year', 'project_id')
    ->with('project:id,title')
    ->get()
    ->groupBy('year')
  ```

---

## Phase 2: Frontend (Vue)

### 2.1 Admin Layout — Header + Collapsible Sidebar

**Files to create:**
- `resources/js/components/admin/AdminHeader.vue` — top header bar
- `resources/js/components/admin/AdminSidebar.vue` — collapsible sidebar

**Files to modify:**
- `resources/js/layouts/AdminLayout.vue` — use new header + sidebar components
- `resources/css/app.css` — add admin layout styles

**AdminHeader.vue design:**
```
┌──────────────────────────────────────────────────────────────┐
│  [logo] FBF Admin              [lang] [theme] [user] [logout] │
└──────────────────────────────────────────────────────────────┘
```
- Fixed at top, full width
- Left: logo + brand name
- Right: language toggle → theme toggle → user avatar/name dropdown → logout button
- Z-index above sidebar

**AdminSidebar.vue design:**
```
┌──────────┐
│  [icon]  │  Dashboard
│  [icon]  │  Projects
│  [icon]  │  Donations
│  [icon]  │  Gallery
│  [icon]  │  Videos
│  [icon]  │  CMS Pages
│  ──────  │
│  [icon]  │  Users
│  [icon]  │  Roles
└──────────┘
```
- Collapsible: when collapsed, shows only icons (64px wide), when expanded shows labels (240px wide)
- Smooth CSS transition on width
- Menu groups: "Content" (projects, donations, gallery, videos, cms), "Administration" (users, roles)
- Active state highlighting
- Toggle button (hamburger) to collapse/expand

### 2.2 RBAC Admin Pages

**Files to create:**
- `resources/js/pages/admin/roles.vue` — manage roles and permissions

**Files to modify:**
- `resources/js/pages/admin/users.vue` — enhanced with CRUD, role assignment

**roles.vue:**
- Table: name, label, permissions count, users count, actions
- Create/Edit modal: role name, label, permission checkboxes grouped by module (Projects, Donations, Gallery, Videos, CMS, Users, Roles)
- Delete with confirmation
- Bilingual support (EN/BN labels)

**users.vue enhancement:**
- Search by name/email
- Filter by role dropdown
- Pagination
- Create user modal: name, email, password, role select
- Edit user modal: name, email, role select, status toggle
- View user modal: all info + donations count + last donation date
- Delete user with confirmation

### 2.3 Enhanced Admin CRUD Pages

**Pattern for all CRUD pages:**

Each admin page will have this structure:
```
┌─────────────────────────────────────────────────────┐
│  [Title]                                [+ Add New] │
├─────────────────────────────────────────────────────┤
│  [Search input]  [Status filter]  [Project filter]   │
├─────────────────────────────────────────────────────┤
│  ┌─────────────────────────────────────────────────┐│
│  │  Table with sortable column headers             ││
│  │  Actions: [View] [Edit] [Delete]                ││
│  └─────────────────────────────────────────────────┘│
│  [Pagination: < 1 2 3 ... >]                        │
└─────────────────────────────────────────────────────┘
```

**Files to modify:**
- `resources/js/pages/admin/projects.vue`
- `resources/js/pages/admin/donations.vue`
- `resources/js/pages/admin/gallery.vue`
- `resources/js/pages/admin/videos.vue`
- `resources/js/pages/admin/cms-pages.vue`

**Optional reusable components:**
- `AdminDataTable.vue` — table wrapper with sorting
- `AdminFilterBar.vue` — search + filter dropdowns
- `AdminPagination.vue` — page navigation
- `AdminModal.vue` — generic modal for create/edit forms

**Per-page specifics:**

**Projects:**
- Columns: Title (EN/BN), Goal, Collected, Status, Created
- Filters: status (draft/active/completed), search (title/title_bn)
- Form: all bilingual fields + icon/color selects + image URL + dates

**Donations:**
- Columns: Date, Donor, Project, Amount (BDT), Transaction ID, Status, Payment Method
- Filters: project, status (pending/completed/failed/cancelled), date range, search (donor/transaction)
- View modal: full SSLCommerz details (card type, bank tran id, etc.)

**Gallery:**
- Columns: Image (thumbnail), Title, Project, Created
- Filters: project, search (title)
- Grid view with thumbnails, table view as alternative

**Videos:**
- Same as gallery pattern
- Embed preview in table row

**CMS Pages:**
- Columns: Title, Slug, Status, Updated
- Filters: status, search (title/slug)
- Editor: textarea for content

### 2.4 Admin Dashboard Redesign

**Files to modify:**
- `resources/js/pages/admin/dashboard.vue`

**Design:**
```
┌────────────────────────────────────────────────────────┐
│  Admin Dashboard                                        │
├──────────┬──────────┬──────────┬──────────┬───────────┤
│  $XX,XXX │    XX    │    XX    │    XX    │    XX     │
│  Donations│ Projects │  Users   │ Gallery  │  Videos   │
├──────────┴──────────┴──────────┴──────────┴───────────┤
│  Monthly Donations Chart (bar)                         │
├────────────────────────────────────────────────────────┤
│  Recent Donations (table, last 10)                     │
├────────────────────────────────────────────────────────┤
│  Quick Actions: [New Project] [Add Image] [Add Video]  │
└────────────────────────────────────────────────────────┘
```

### 2.5 User Dashboard Redesign

**Files to modify:**
- `resources/js/pages/dashboard.vue`
- `resources/js/pages/dashboard/donations.vue` (optional, may merge into dashboard)

**User Dashboard design:**
```
┌────────────────────────────────────────────────────────┐
│  My Dashboard                                           │
├──────────────┬──────────────────┬─────────────────────┤
│   $XX,XXX    │       XX         │        XX           │
│  Total Donated│ Donations Made  │  Projects Supported  │
├──────────────┴──────────────────┴─────────────────────┤
│  Yearly Donations Chart (bar: years on X,              │
│  projects as colored stacks/bars, amount on Y)         │
├────────────────────────────────────────────────────────┤
│  Full Donation History (paginated table)                │
│  ┌──────┬────────┬─────────┬────────┬────────┬──────┐ │
│  │ Date │ Project│ Amount  │ Tran ID│ Status │ View │ │
│  ├──────┼────────┼─────────┼────────┼────────┼──────┤ │
│  │ ...  │  ...   │  ...    │  ...   │  ...   │ ...  │ │
│  └──────┴────────┴─────────┴────────┴────────┴──────┘ │
│  [Pagination]                                           │
├────────────────────────────────────────────────────────┤
│  Project-wise Breakdown (table: project, donations,    │
│  total amount, last donated)                            │
└────────────────────────────────────────────────────────┘
```

**Yearly chart API** (new endpoint):
```
GET /api/donations/yearly-stats  (auth required)
Response:
{
  "2024": [
    { "project_id": 1, "project_title": "Food Aid", "count": 5, "total": 25000 },
    { "project_id": 2, "project_title": "Education", "count": 3, "total": 15000 }
  ],
  "2025": [
    { "project_id": 1, "project_title": "Food Aid", "count": 8, "total": 40000 },
    ...
  ]
}
```

Vue chart will display this as a stacked bar chart (year on X, projects as colored segments, count or amount on Y).

### 2.6 SSLCommerz Donation Flow (Frontend)

**Files to modify:**
- `resources/js/pages/projects/[slug].vue` — donate button flow
- `resources/js/stores/auth.js` — ensure token is sent with donation requests

**Flow:**
1. User is on project detail page, selects amount, clicks "Donate now"
2. If not logged in → redirect to `/login?redirect=/projects/{slug}`
3. If logged in → call `POST /api/donations/initiate` with `{ project_id, amount }`
4. Response: `{ redirect_url: "https://sandbox.sslcommerz.com/..." }`
5. `window.location.href = redirect_url`
6. After payment, SSLCommerz redirects to success/cancel/fail URL
7. Success page (`/donations/success?tran_id=xxx`): shows thank you with transaction details
8. User can click "View Dashboard" to see full history

**New pages:**
- `resources/js/pages/donations/success.vue` — thank you page
- `resources/js/pages/donations/cancel.vue` — payment cancelled
- `resources/js/pages/donations/fail.vue` — payment failed

---

## Phase 3: Configuration & Seeders

### Database Seeders
- Update `DatabaseSeeder.php` to call `RolePermissionSeeder`
- `RolePermissionSeeder`: creates 4 roles, all permissions, assigns all permissions to admin

### .env additions
```
SSLCOMMERZ_SANDBOX=true
SSLCOMMERZ_STORE_ID=testbox
SSLCOMMERZ_STORE_PASSWORD=testbox
```

### Permission Middleware Registration
In `bootstrap/app.php`:
```php
$middleware->alias([
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
    'permission' => \App\Http\Middleware\PermissionMiddleware::class,
]);
```

---

## Files Summary

### New Files (18)
| File | Purpose |
|------|---------|
| `config/sslcommerz.php` | SSLCommerz config |
| `app/Services/SSLCommerzService.php` | SSLCommerz API calls |
| `app/Http/Controllers/SSLCommerzController.php` | Payment callbacks |
| `app/Models/Role.php` | Role model |
| `app/Models/Permission.php` | Permission model |
| `app/Http/Middleware/PermissionMiddleware.php` | Permission check middleware |
| `app/Http/Controllers/Admin/RoleController.php` | Admin role CRUD |
| `database/migrations/*_create_roles_table.php` | Roles schema |
| `database/migrations/*_create_permissions_table.php` | Permissions schema |
| `database/migrations/*_create_permission_role_table.php` | Pivot table |
| `database/migrations/*_create_role_user_table.php` | Pivot table |
| `database/migrations/*_add_sslcommerz_fields_to_donations.php` | Donation fields |
| `database/seeders/RolePermissionSeeder.php` | Seed roles+perms |
| `resources/js/components/admin/AdminHeader.vue` | Header component |
| `resources/js/components/admin/AdminSidebar.vue` | Sidebar component |
| `resources/js/pages/admin/roles.vue` | Role management page |
| `resources/js/pages/donations/success.vue` | Success page |
| `resources/js/pages/donations/cancel.vue` | Cancel page |

### Modified Files (21)
| File | Changes |
|------|---------|
| `app/Models/User.php` | Role/permission relationships |
| `app/Models/Donation.php` | SSLCommerz fields, new statuses |
| `app/Http/Controllers/DonationController.php` | SSLCommerz initiate flow, yearlyStats endpoint |
| `app/Http/Controllers/Admin/ProjectController.php` | Filter/search/paginate |
| `app/Http/Controllers/Admin/DonationController.php` | Filter/search/paginate |
| `app/Http/Controllers/Admin/GalleryController.php` | Filter/search/paginate |
| `app/Http/Controllers/Admin/VideoController.php` | Filter/search/paginate |
| `app/Http/Controllers/Admin/CmsPageController.php` | Filter/search/paginate |
| `app/Http/Controllers/Admin/UserController.php` | CRUD + filter/search/paginate |
| `bootstrap/app.php` | Register permission middleware |
| `routes/api.php` | New routes |
| `resources/js/layouts/AdminLayout.vue` | Header + sidebar |
| `resources/js/pages/admin/dashboard.vue` | Redesign |
| `resources/js/pages/admin/projects.vue` | Filter/paginate/search |
| `resources/js/pages/admin/donations.vue` | Filter/paginate/search + SSL info |
| `resources/js/pages/admin/gallery.vue` | Filter/paginate/search |
| `resources/js/pages/admin/videos.vue` | Filter/paginate/search |
| `resources/js/pages/admin/cms-pages.vue` | Filter/paginate/search |
| `resources/js/pages/admin/users.vue` | CRUD + role assignment |
| `resources/js/pages/dashboard.vue` | Full history table + yearly chart |
| `resources/js/pages/projects/[slug].vue` | SSLCommerz payment redirect |
| `resources/css/app.css` | Admin layout styles |
| `resources/js/stores/admin.js` | New API methods for roles/filters |

---

## Execution Order
1. Migrations (roles, permissions, pivots, donation fields)
2. Models (Role, Permission, update User, update Donation)
3. Middleware (PermissionMiddleware)
4. SSLCommerz (config, service, controller)
5. Enhanced admin controllers (filter/paginate)
6. RoleController + routes
7. Seeders
8. Admin layout (Header, Sidebar, AdminLayout)
9. Admin pages (roles, enhanced CRUD pages, dashboard)
10. User dashboard (history table, yearly chart)
11. Donation flow frontend (slug page, success/cancel/fail pages)
12. Build + test
