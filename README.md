# Techie Course Module

`techie/course-module` is a Laravel package that adds Course, Form, and FormAttribute management. Your **CMS application code stays the same** across projects; you turn the course module on by adding this package with Composer (path repo while developing, or a Git URL for production).

Compatible with Laravel `11`, `12`, and `13`.

## How it fits your CMS

- **Core CMS**: one shared codebase; no course-specific forks.
- **Course feature**: pulled in as a dependency (`techie/course-module`) from Git or a local path.
- **Wiring**: publish config/migrations, run install, add **one** `@include` in your admin sidebar (or let `course-module:install` inject it if your layout matches the defaults).

## Features

- Course CRUD with soft-deletes, status enum, thumbnail upload
- Form CRUD with course linkage
- Form attribute builder with sortable order
- Routes, views, and migrations loaded by the service provider
- Optional `enabled` config / `COURSE_MODULE_ENABLED` to disable HTTP routes without removing the package
- Facade: `CourseModule::allPublished()` and `CourseModule::manifest()` for CMS/plugin dashboards
- `php artisan course-module:install` — publish, migrate, seed permissions (Spatie), optional sidebar injection
- `CmsModule` contract: metadata, permission list, and sidebar view name for integrations

## Installation in a CMS project

### 1) Path repository (local / monorepo dev)

In the CMS `composer.json`:

```json
{
  "repositories": [
    {
      "type": "path",
      "url": "packages/techie/course-module",
      "options": { "symlink": true }
    }
  ]
}
```

### 2) Require the package

```bash
composer require techie/course-module:*
```

### 3) One-step install (recommended)

```bash
php artisan course-module:install
```

Or publish manually, then migrate — see `course-module:install` source for tags.

### 4) Wire the admin menu (recommended)

**Option A — manual (most stable):** in your CMS admin sidebar Blade file, add:

```blade
@include('course-module::cms.sidebar')
```

**Option B — automatic:** run `course-module:install` without `--skip-menu`. The command tries to inject that include before a configurable anchor in `config/course-module.php` (`cms.install.*`). Adjust `sidebar_file` and `insert_before_needle` if your paths differ.

### 5) Verify routes

```bash
php artisan route:list --name=course-module
```

## Route map

Route **names** use the `course-module.` prefix (e.g. `course-module.courses.index`).  
URLs use your `route_prefix` (default `admin`), for example:

- `/admin/courses`
- `/admin/forms`
- `/admin/form-attributes`

## Config

Published file: `config/course-module.php`

- `enabled` / `COURSE_MODULE_ENABLED`: disable package routes without uninstalling
- `version`: optional fallback if Composer’s installed version is unavailable
- `table_prefix`, `thumbnail_disk`, `thumbnail_path`
- `middleware`, `route_prefix`
- `use_cms_layout`, `layout` — render package views inside your CMS layout
- `cms.sidebar_include` — view passed to `@include(...)` in your sidebar
- `cms.install.*` — paths and anchor for optional sidebar injection during install

## Facade

```php
use CourseModule;

$courses = CourseModule::allPublished();
$module = CourseModule::manifest(); // Techie\CourseModule\Contracts\CmsModule
```

## Contracts

**Plugin metadata** — `Techie\CourseModule\Contracts\PluginInfo`

**Full CMS integration surface** — `Techie\CourseModule\Contracts\CmsModule` (extends `PluginInfo`)

Default implementation: `Techie\CourseModule\Support\CourseModulePluginInfo`

```php
$module = app(\Techie\CourseModule\Contracts\CmsModule::class);

$module->name();
$module->version(); // from Composer when installed as a package
$module->sidebarBlade();   // 'course-module::cms.sidebar'
$module->permissionNames();
```

## Git workflow for the package

Keep this repository as its own Git project. In each client or project CMS, add it as a Composer **VCS** repository:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/<org-or-user>/course-module.git"
    }
  ]
}
```

```bash
composer require techie/course-module:^1.0
```

Tag releases (e.g. `v1.0.0`) so projects can pin versions while the CMS core stays unchanged.

## Development notes

- Models use `config('course-module.table_prefix')`
- `Course` and `Form` use soft deletes
- Form attribute reorder: `POST` route `course-module.form-attributes.reorder`
- Permission seeding expects `spatie/laravel-permission` (see `composer.json` `suggest`)

## License

MIT
