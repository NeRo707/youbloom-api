# youbloom Laravel Task Manager Application

[![Laravel 10](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP 8.2](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Bootstrap 4](https://img.shields.io/badge/Bootstrap-4.6-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com/docs/4.6/)
[![Vite](https://img.shields.io/badge/Vite-Bundler-646CFF?style=for-the-badge&logo=vite&logoColor=white)](https://vitejs.dev/)
[![Tests](https://img.shields.io/badge/Tests-21%20Passed-success?style=for-the-badge&logo=phpunit&logoColor=white)](#automated-testing)

A modern, responsive, and maintainable **Task Manager Web Application** built for **youbloom** using **Laravel 10**, **Bootstrap 4**, and **Vite**.

---

## Table of Contents
1. [Overview of Approach](#overview-of-approach)
2. [Assumptions Made](#assumptions-made)
3. [Setup & Installation Instructions](#setup--installation-instructions)
4. [Details of Bonus Features Implemented](#details-of-bonus-features-implemented)
5. [Automated Testing](#automated-testing)
6. [Routes & Architecture](#routes--architecture)
7. [Evaluation Criteria Checklist](#evaluation-criteria-checklist)

---

## Overview of Approach

The application was built following clean architecture and senior-level Laravel design patterns to ensure maximum maintainability, scalability, and code quality:

1. **Separation of Concerns (Thin Controllers, Form Requests)**:
   - Controller actions are lean and focused strictly on request/response orchestration.
   - Validation rules and customized user-facing error messages are encapsulated inside dedicated Form Request classes (`StoreTaskRequest`, `UpdateTaskRequest`), preventing bloated controllers.

2. **Domain Logic & Scopes Encapsulated in the Model**:
   - The `Task` model encapsulates all status constants (`STATUS_PENDING`, `STATUS_COMPLETED`) to eliminate hardcoded "magic strings".
   - Common queries are written as reusable Eloquent query scopes (`scopeSearch`, `scopeStatus`, `scopeOrdered`), keeping SQL queries DRY and testable.

3. **Bootstrap 4 UI & UX Distinction**:
   - Designed cleanly using native **Bootstrap 4.6** components (cards, badges, modals, tooltips, responsive grid).
   - Clear visual distinction between states:
     - **Completed tasks**: subtle green accent border, `badge-success`, strikethrough title, and muted text.
     - **Pending tasks**: yellow accent border, `badge-warning`, and bold title.
   - Delete confirmation is protected by a native **Bootstrap 4 Modal** showing the exact title of the task being deleted.

4. **Modern Asset Management with Vite**:
   - Custom stylesheets and scripts are modularized into `resources/css/app.css` and `resources/js/app.js` and loaded cleanly via `@vite(['resources/css/app.css', 'resources/js/app.js'])`.

---

## Assumptions Made

1. **Single-Workspace (No Authentication Required)**:
   - The specification did not request multi-user authentication (registration/login). The task dashboard is accessible directly as an open workspace.
2. **Zero-Configuration SQLite by Default**:
   - To make evaluating this submission as seamless as possible, SQLite is configured as the default database. No local MySQL service configuration is needed to run the app out of the box (MySQL is also fully supported via `.env`).
3. **Pre-Bundled Production Assets**:
   - Production assets (`public/build`) can be compiled via `npm run build` so evaluators running `php artisan serve` get full styling and interactivity immediately.

---

## Setup & Installation Instructions

Follow these step-by-step instructions to get the application running locally:

### 1. Prerequisites
- **PHP >= 8.1** (with `pdo`, `sqlite3` or `pdo_mysql`, `mbstring`, `curl`, and `openssl` enabled).
- **Composer** (v2.x).
- **Node.js** (v18+) & **npm** (for asset building).

### 2. Clone the Repository
```bash
git clone https://github.com/NeRo707/youbloom-api
cd youbloom-api
```

### 3. Install PHP Dependencies
```bash
composer install
```

### 4. Configure Environment
Copy the `.env.example` file to create your `.env` and generate the application encryption key:
```bash
# Linux / macOS / Git Bash:
cp .env.example .env

# Windows PowerShell:
Copy-Item .env.example .env

# Generate application key
php artisan key:generate
```

### 5. Setup Database & Seed Initial Tasks
By default, the application is pre-configured to use **SQLite** (`DB_CONNECTION=sqlite`).

Create the SQLite database file:
```bash
# Windows PowerShell:
New-Item -ItemType File -Path "database/database.sqlite" -Force

# Linux / macOS / Git Bash:
touch database/database.sqlite
```

Run migrations and populate the database with realistic sample tasks:
```bash
php artisan migrate --seed
```

*(Optional: To use MySQL instead, update `DB_CONNECTION=mysql` and your MySQL credentials in `.env`, then run `php artisan migrate --seed`)*.

### 6. Build Frontend Assets (Vite)
Install npm dependencies and compile the production bundle:
```bash
npm install
```
```bash
npm run build
```
or
```bash
npm run dev
```

### 7. Start the Local Server
```bash
php artisan serve
```

Navigate to: http://127.0.0.1:8000 or http://localhost:8000

*(The root `/` automatically redirects to the tasks dashboard at `/tasks`)*.

---

## Details of Bonus Features Implemented

All three optional bonus features outlined in the prompt have been fully implemented:

### 1. Search Bar & Status Filtering
- **Keyword Search**: Filter tasks by title or description in real time via an input field with clear/reset controls.
- **Status Filter**: Dropdown filter to display **All**, **Pending**, or **Completed** tasks.
- **Simultaneous Filtering**: Search keywords and status dropdown can be used together.
- **Query String Persistence**: Filter parameters are preserved during pagination navigation via Laravel's `->withQueryString()`.

### 2. Pagination for Long Task Lists
- Implemented with Laravel's `paginate(10)`.
- Styled natively using Bootstrap 4 pagination components through `Paginator::useBootstrapFour()` in `AppServiceProvider`.
- Shows clear pagination metadata (*e.g., "Showing 1 to 10 of 25 tasks"*).
- Graceful empty state shown when no tasks match current search/filter parameters.

### 3. Drag-and-Drop Task Reordering
- Powered by [SortableJS](https://sortablejs.github.io/Sortable/) on the task list container.
- An `order` column in the database stores the user's custom sort position.
- Users can drag items using the grip handle (`.drag-handle`).
- On drop, an asynchronous AJAX request sends the updated task sequence to `POST /tasks/reorder` with CSRF protection.
- Database updates are executed atomically within a database transaction (`DB::transaction`).
- A live, dismissible alert banner notifies the user that the new display order has been saved.

---

| HTTP Method | URI | Route Name | Action | Description |
| --- | --- | --- | --- | --- |
| `GET` | `/` | — | Redirect | Redirects to `/tasks` |
| `GET` | `/tasks` | `tasks.index` | `TaskController@index` | Task dashboard with search, filter, and pagination |
| `GET` | `/tasks/create` | `tasks.create` | `TaskController@create` | Show task creation form |
| `POST` | `/tasks` | `tasks.store` | `TaskController@store` | Store a new task with validation |
| `GET` | `/tasks/{task}` | `tasks.show` | `TaskController@show` | View specific task details |
| `GET` | `/tasks/{task}/edit` | `tasks.edit` | `TaskController@edit` | Show task edit form |
| `PUT/PATCH` | `/tasks/{task}` | `tasks.update` | `TaskController@update` | Update an existing task |
| `DELETE` | `/tasks/{task}` | `tasks.destroy` | `TaskController@destroy` | Delete a task (with modal confirmation) |
| `PATCH` | `/tasks/{task}/toggle-status` | `tasks.toggle-status` | `TaskController@toggleStatus` | Quick status toggle (Pending ⟷ Completed) |
| `POST` | `/tasks/reorder` | `tasks.reorder` | `TaskController@reorder` | Drag-and-drop reorder endpoint |
