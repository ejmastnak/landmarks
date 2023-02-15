About: process used to create website.

```bash
# Basic install of Laravel with Vue using Laravel Breeze:
composer create-project laravel/laravel landmarks
cd landmarks
composer require laravel/breeze --dev
php artisan breeze:install vue
```

- Locally create a PostgreSQL user `landmarker` and database `landmarks` with a `landmarks` and `categories` table.
- Follow hello world database notes to set up `.env` file connecting PostgreSQL user `landmarker` to database `landmarks`.

- Create models:
  
  ```bash
  art make:model -msfrc Landmark
  art make:model -msf LandmarkType
  ```

- Create migration, seeder, and factory as in TLDR notes

- Create resource routes to LandmarkController

  ```php
  <?php
  // In `routes/web.php`
  Route::resource('/', LandmarkController::class);
  Route::resource('landmarks', LandmarkController::class);
  ```

- Turn attention to LandmarkController resource controller:

  - Index
  - Show
