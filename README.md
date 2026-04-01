# Techie Course Module

`techie/course-module` is a reusable Laravel package that adds Course, Form, and FormAttribute management to any Laravel CMS.

Compatible with Laravel `11`, `12`, and `13`.

## Features

- Course CRUD with soft-deletes, status enum, thumbnail upload
- Form CRUD with course linkage
- Form attribute builder with sortable order
- Route + view + migration auto-loading through package service provider
- Publishable config/views/migrations
- Facade API: `CourseModule::allPublished()`
- Install command: `php artisan course-module:install`
- Plugin metadata contract for plugin dashboard integration

## Package Structure

```text
packages/techie/course-module
```

Main namespace:

```php
Techie\CourseModule\
```

## Installation in CMS

From your CMS project (`C:\laragon\www\CMS`):

### 1) Add path repository in `composer.json`

```json
{
  "repositories": [
    {
      "type": "path",
      "url": "packages/techie/course-module",
      "options": {
        "symlink": true
      }
    }
  ]
}
```

### 2) Require package

```bash
composer require techie/course-module:*
```

### 3) Publish package files

```bash
php artisan vendor:publish --tag=course-module-config
php artisan vendor:publish --tag=course-module-views
php artisan vendor:publish --tag=course-module-migrations
```

### 4) Migrate

```bash
php artisan migrate
```

### 5) Optional: one-step install

```bash
php artisan course-module:install
```

### 6) Verify routes

```bash
php artisan route:list --path=courses
```

## Route Map

Routes are loaded by the provider with package prefix `course-module`, plus the internal admin prefix from config (`admin` by default), so effective paths look like:

- `/course-module/admin/courses`
- `/course-module/admin/forms`
- `/course-module/admin/form-attributes`

Route names are prefixed with `course-module.`:

- `course-module.courses.index`
- `course-module.forms.index`
- `course-module.form-attributes.index`

## Config

Published file: `config/course-module.php`

- `table_prefix`: Prefix for package tables
- `thumbnail_disk`: Filesystem disk for course thumbnails
- `thumbnail_path`: Folder path for thumbnails
- `middleware`: Middleware stack for package routes
- `route_prefix`: Inner route prefix (default `admin`)
- `use_cms_layout`: Use host app layout or package fallback
- `layout`: Host layout when `use_cms_layout=true`

## Facade

```php
use CourseModule;

$courses = CourseModule::allPublished();
```

## Plugin Info Contract

Interface:

```php
Techie\CourseModule\Contracts\PluginInfo
```

Default implementation:

```php
Techie\CourseModule\Support\CourseModulePluginInfo
```

Example usage:

```php
$info = app(\Techie\CourseModule\Contracts\PluginInfo::class);

$info->name();
$info->version();
$info->author();
$info->description();
```

## Git Setup for Standalone Package

Inside package folder:

```bash
cd C:\laragon\www\CMS\packages\techie\course-module
git init
git add .
git commit -m "Initial commit: techie course module package"
```

Create private GitHub repo and push:

```bash
git branch -M main
git remote add origin https://github.com/<org-or-user>/course-module.git
git push -u origin main
```

Tag first release:

```bash
git tag v1.0.0
git push origin v1.0.0
```

## Using via VCS in Future Projects

Add to target project `composer.json`:

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

Install specific version:

```bash
composer require techie/course-module:^1.0
```

## Development Notes

- Package models use configurable table prefix via `config('course-module.table_prefix')`
- `Course` and `Form` support soft deletes
- Form attribute reorder endpoint:
  `POST course-module.form-attributes.reorder`

## License

MIT
