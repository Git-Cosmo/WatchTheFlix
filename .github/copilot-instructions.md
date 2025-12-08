# GitHub Copilot Coding Agent Instructions

## Project Overview

**WatchTheFlix** is a full-featured Laravel 12 streaming platform inspired by Stremio, with Real-Debrid integration, TMDB API, TV Guide, and a sleek dark theme. This is a production-ready application with comprehensive features including user authentication, admin panel, forum system, and IPTV capabilities via Xtream Codes API.

### Technology Stack
- **Framework**: Laravel 12 (PHP 8.2+)
- **Frontend**: TailwindCSS 3.4 + Vite + Blade templates
- **Database**: SQLite (default) / MySQL / PostgreSQL
- **Authentication**: Laravel Breeze-style with 2FA support
- **Video Player**: Video.js with HLS/DASH streaming
- **Search**: Laravel Scout (database/Meilisearch/Algolia)
- **Caching**: File cache (dev) / Redis (production)
- **Node.js**: 18+

### Key Features
- Media catalog with watchlists, favorites, ratings, comments, and reactions
- TV Guide for UK and US channels with EPG automation
- Platform availability tracking (Netflix, Prime, Hulu, etc.)
- Custom forum system with categories, threads, and subscriptions
- User profiles with 2FA and parental controls
- Invite-only registration system
- Comprehensive admin panel with analytics
- Xtream Codes API for IPTV applications
- Playlist creation and management
- Optional TMDB API and Real-Debrid integrations

## Repository Structure

### Core Directories
- `/app` - Application code (Controllers, Models, Services, Notifications, Middleware)
  - `/app/Console/Commands` - Artisan commands (EPG updates, backups, sitemap)
  - `/app/Http/Controllers` - Request handlers
  - `/app/Models` - Eloquent models
  - `/app/Services` - Business logic (TmdbService, RealDebridService, EpgService, MediaScraperService)
  - `/app/Notifications` - Email and database notifications
- `/database/migrations` - Database schema definitions
- `/database/seeders` - Data seeding classes
- `/resources/views` - Blade templates (layouts, components, pages)
- `/routes` - Route definitions (web.php, api.php, console.php)
- `/tests` - PHPUnit tests (Feature, Unit)
- `/config` - Laravel configuration files
- `/public` - Publicly accessible files (assets, images)
- `/storage` - Logs, cache, sessions, uploaded files

### Important Files
- `composer.json` - PHP dependencies
- `package.json` - Node.js dependencies
- `phpunit.xml` - PHPUnit test configuration
- `.env.example` - Environment variables template
- `README.md` - Project documentation
- `CONTRIBUTING.md` - Contribution guidelines
- `INSTALLATION.md` - Complete installation guide

## Build & Test Commands

### Installation & Setup
```bash
# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup (SQLite)
touch database/database.sqlite
php artisan migrate
php artisan db:seed

# Build assets
npm run dev           # Development with hot reload
npm run build         # Production build
```

### Testing
```bash
# Run all tests
php artisan test

# Run specific test suites
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# Run specific test file
php artisan test tests/Feature/ExampleTest.php

# Run with coverage
php artisan test --coverage
```

### Code Quality
```bash
# Run Laravel Pint (code formatter)
./vendor/bin/pint

# Check code style without fixing
./vendor/bin/pint --test
```

### Development Server
```bash
# Start Laravel server
php artisan serve

# Start Vite dev server (separate terminal)
npm run dev

# Run queue worker (if using queues)
php artisan queue:work

# Run scheduled commands (in dev)
php artisan schedule:work
```

### Maintenance Commands
```bash
# Clear all caches
php artisan optimize:clear

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Database commands
php artisan migrate:fresh --seed  # Fresh database with seed data
php artisan db:seed --class=TvProgramSeeder  # Specific seeder

# Custom commands
php artisan epg:update           # Update EPG data
php artisan backup:database      # Backup database
php artisan sitemap:generate     # Generate sitemap
php artisan media:scrape         # Scrape TMDB content
```

## Coding Conventions & Best Practices

### Laravel Standards
- Follow Laravel's coding standards and conventions
- Use PSR-12 coding style (enforced by Laravel Pint)
- Use type hints for method parameters and return types
- Use strict types declaration (`declare(strict_types=1);`) in new files
- Follow Laravel's naming conventions for methods, variables, and classes

### Database & Models
- Use Eloquent models for database interactions
- Define relationships in models (hasMany, belongsTo, belongsToMany)
- Use database migrations for all schema changes
- Never modify existing migrations that have been deployed
- Use pivot tables with position columns for ordered many-to-many relationships (see Playlist system)
- Use `Model::insert()` for bulk inserts with timestamps
- Use proper indexing on frequently queried columns (country, start_time, end_time)

### Controllers & Routes
- Keep controllers thin, move business logic to services
- Use resource controllers for CRUD operations
- Use route model binding when possible
- Use `->parameters(['media' => 'media'])` on resource routes when model name conflicts with Laravel pluralization
- Use role middleware for role-based access control (defined in bootstrap/app.php)

### Services & Business Logic
- Create service classes for complex business logic (see `/app/Services`)
- Services should be injected via dependency injection
- Optional integrations (TMDB, Real-Debrid) must check if configured before use via `isConfigured()` method
- Use `whereHas` for relationship filtering in queries
- Use `withQueryString()` for maintaining filter state in pagination

### Authentication & Security
- Always use `Hash::check()` for password verification instead of `password_verify()`
- Never commit secrets or sensitive data (use .env)
- Validate all user input
- Sanitize output to prevent XSS (use `html_escape` for user content)
- Use Laravel's CSRF protection
- Use strict comparison (===) for array equality checks
- Sort arrays before comparing for accurate equality checks

### Notifications
- Use both 'database' and 'mail' channels in notification `via()` method
- Implement `ShouldQueue` interface for queued processing
- Use 'type' field in notification data array to identify notification types
- Notifications are stored in database table for in-app display

### Frontend & Blade
- Use TailwindCSS utility classes (no custom CSS unless necessary)
- No inline JavaScript in Blade views
- Keep components reusable
- Ensure mobile responsiveness
- Use Blade components for reusable UI elements
- Test on multiple browsers

### Testing
- Write tests for new features
- Tests use SQLite in-memory database (configured in phpunit.xml)
- Use Feature tests for HTTP requests and integrations
- Use Unit tests for isolated logic
- Mock external services (TMDB, Real-Debrid) in tests

### Comments & Documentation
- Write clear, self-documenting code
- Add comments for complex logic only
- Keep comments up-to-date with code changes
- Document public APIs and service methods
- Update README.md for new features

## Contribution Workflow

### Making Changes
1. Create a feature branch: `git checkout -b feature/your-feature-name`
2. Make minimal, focused changes
3. Run tests: `php artisan test`
4. Run code formatter: `./vendor/bin/pint`
5. Commit with clear messages (present tense)
6. Create pull request with clear description

### Pull Request Requirements
- All tests must pass
- Code must pass Pint style checks
- Update documentation for new features
- Link related issues in PR description
- Add tests for new functionality

### Commit Message Format
- Use present tense: "Add feature" not "Added feature"
- Reference issues: "Fixes #123"
- Be descriptive but concise

## Database Seeding

### Available Seeders
- `DatabaseSeeder` - Runs all seeders (admin user, platforms, forums)
- `PlatformSeeder` - Streaming platforms (Netflix, Prime, Hulu, etc.)
- `TvChannelSeeder` - TV channels (UK, US)
- `TvProgramSeeder` - 7 days of EPG data
- `TmdbMediaSeeder` - Top 50 movies/shows (requires TMDB API key)
- `ForumSeeder` - Forum categories and sample threads

### Default Admin Credentials
- Email: `admin@watchtheflix.com`
- Password: `password`
- **Change these immediately in production!**

## Optional Integrations

### TMDB API (Optional)
- Configure via admin panel or .env: `TMDB_API_KEY=your_key`
- Service checks if configured via `TmdbService::isConfigured()`
- Used for metadata, posters, backdrops, cast info
- Platform works fully without it

### Real-Debrid (Optional)
- User-level setting (not global)
- Service checks if configured via `RealDebridService::isConfigured()`
- Provides premium content access
- Platform works fully without it

### EPG Updates (Automated)
- Scheduled command runs daily at 3:00 AM
- Command: `php artisan epg:update`
- Fetches XMLTV data from external providers
- See EPG_SETUP.md for configuration details

## Special Considerations

### Spatie Packages
- All Spatie packages are properly configured and integrated
- Activity logging tracks important actions
- Permissions system uses roles (admin, moderator, user)
- Backup and sitemap generation use custom artisan commands

### Two-Factor Authentication
- Uses pragmarx/google2fa-laravel package
- Encrypted secret storage in database
- Recovery codes system implemented
- QR code generation for Google Authenticator

### Forum System
- Custom-built for Laravel 12 compatibility
- Not using riari/laravel-forum (Laravel 11 only)
- Full CRUD operations with admin moderation
- Thread subscriptions with notifications

### Xtream Codes API
- Complete IPTV backend implementation
- Admin UI for managing streams and channels
- Laravel Sanctum for API authentication
- See XTREAM_API.md for details

### TV Guide Data
- Manual database seeding via TvChannelSeeder and TvProgramSeeder
- Automated EPG updates via scheduled command
- Supports UK and US channels

### Video Player
- Video.js with HLS/HTTP streaming support
- Subtitle support (SRT/VTT formats)
- Multi-language support

## Security Guidelines

### Critical Rules
- Never commit secrets to version control
- Always validate user input
- Sanitize output to prevent XSS attacks
- Use parameterized queries (Eloquent handles this)
- Implement proper authorization checks
- Use HTTPS in production
- Keep dependencies updated

### Password Security
- Use `Hash::check()` for password verification
- Never store passwords in plain text
- Use Laravel's password validation rules

### API Security
- Use Laravel Sanctum for API authentication
- Rate limit API endpoints
- Validate and sanitize all API inputs

## Performance Optimization

### Production Configuration
- Use Redis for cache, sessions, and queues
- Use Meilisearch or Algolia for search
- Use MySQL or PostgreSQL instead of SQLite
- Enable OPcache for PHP
- Use queue workers for background jobs
- Cache configuration, routes, and views

### Database Optimization
- Use database indexes appropriately
- Use eager loading to prevent N+1 queries
- Use bulk inserts for seeding
- Use database transactions for multi-step operations

## Common Pitfalls

### Array Comparison
- Always use strict comparison (===) for arrays
- Sort arrays before comparing for accurate results

### Notification System
- Remember to use 'type' field in data array
- Both database and mail channels should be returned in via() method

### Route Model Binding
- Use ->parameters() to fix pluralization issues with resource routes

### EPG Data
- EPG updates are automated via scheduled command
- Don't confuse manual seeding with automated updates

## Questions & Support

- Check existing issues and PRs on GitHub
- Review Laravel 12 documentation
- See AUDIT.md, GAP_ANALYSIS.md, and ROADMAP.md for implementation status
- See BACKEND_FRONTEND_VERIFICATION.md for feature verification
- Open a discussion on GitHub for clarification

## Quick Reference

### File Locations
- Controllers: `/app/Http/Controllers`
- Models: `/app/Models`
- Views: `/resources/views`
- Routes: `/routes/web.php`, `/routes/api.php`
- Migrations: `/database/migrations`
- Seeders: `/database/seeders`
- Tests: `/tests/Feature`, `/tests/Unit`
- Services: `/app/Services`

### Common Tasks
- Add new model: Create model, migration, seeder, factory
- Add new page: Create route, controller method, view file
- Add new feature: Controller, model, migration, tests, views
- Fix bug: Identify issue, write test, fix code, verify test passes
- Update dependencies: `composer update`, `npm update`

### Testing Shortcuts
- Run tests: `php artisan test`
- Format code: `./vendor/bin/pint`
- Clear cache: `php artisan optimize:clear`

---

**Remember**: This is a production-ready application. Make surgical, minimal changes. Always test your changes. Keep the codebase clean and maintainable.
