# Complete Installation Guide

This guide covers installing WatchTheFlix with all optional infrastructure packages for a production-ready deployment.

## Prerequisites

### Required
- PHP 8.2 or higher
- Composer 2.x
- Node.js 18+ and npm
- SQLite extension enabled (or MySQL/PostgreSQL)

### Optional (Recommended for Production)
- Redis Server 6.x or higher
- Meilisearch 1.x (for advanced search)
- FFmpeg (for video processing)
- Supervisor (for queue workers)

## Basic Installation

### 1. Clone and Install Dependencies

```bash
# Clone the repository
git clone https://github.com/Git-Cosmo/WatchTheFlix.git
cd WatchTheFlix

# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 2. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Edit .env file with your settings
nano .env
```

### 3. Database Setup

**For SQLite (Default):**
```bash
# Create database file
touch database/database.sqlite

# Run migrations
php artisan migrate
```

**For MySQL/PostgreSQL:**
```env
# Update .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=watchtheflix
DB_USERNAME=root
DB_PASSWORD=your_password
```

```bash
# Create database
mysql -u root -p -e "CREATE DATABASE watchtheflix CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Run migrations
php artisan migrate
```

### 4. Seed Initial Data

```bash
# Seed all data (admin user, sample content, forums)
php artisan db:seed

# Or seed specific data:
php artisan db:seed --class=PlatformSeeder        # Streaming platforms
php artisan db:seed --class=TvChannelSeeder       # TV channels
php artisan db:seed --class=TvProgramSeeder       # 7 days of EPG data
php artisan db:seed --class=TmdbMediaSeeder       # Top 50 movies/shows (requires TMDB API key)
```

**Default Admin Credentials:**
- Email: `admin@watchtheflix.com`
- Password: `password`

### 5. Build Frontend Assets

```bash
# For development (with hot reload)
npm run dev

# For production
npm run build
```

### 6. Start Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000`

## Advanced Configuration

### Laravel Sanctum (API Authentication)

```bash
# Publish Sanctum migrations
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

# Run migrations
php artisan migrate

# Update .env
SANCTUM_STATEFUL_DOMAINS=localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1
```

### Redis Setup (Recommended for Production)

#### Install Redis

**Ubuntu/Debian:**
```bash
sudo apt update
sudo apt install redis-server
sudo systemctl enable redis-server
sudo systemctl start redis-server
```

**macOS:**
```bash
brew install redis
brew services start redis
```

#### Configure Laravel for Redis

Update `.env`:
```env
REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Use Redis for caching
CACHE_STORE=redis

# Use Redis for sessions
SESSION_DRIVER=redis

# Use Redis for queues
QUEUE_CONNECTION=redis
```

### Meilisearch Setup (Advanced Search)

#### Install Meilisearch

**Ubuntu/Debian:**
```bash
curl -L https://install.meilisearch.com | sh
sudo mv ./meilisearch /usr/local/bin/
```

**macOS:**
```bash
brew install meilisearch
```

**Docker:**
```bash
docker run -d -p 7700:7700 -v $(pwd)/meili_data:/meili_data getmeili/meilisearch:v1.5
```

#### Configure Laravel Scout

Update `.env`:
```env
SCOUT_DRIVER=meilisearch
MEILISEARCH_HOST=http://127.0.0.1:7700
MEILISEARCH_KEY=your_master_key
```

Index your models:
```bash
php artisan scout:import "App\Models\Media"
```

### Queue Workers (Background Jobs)

#### Using Supervisor (Production)

Create `/etc/supervisor/conf.d/watchtheflix-worker.conf`:
```ini
[program:watchtheflix-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/watchtheflix/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasec=10
user=www-data
numprocs=4
redirect_stderr=true
stdout_logfile=/path/to/watchtheflix/storage/logs/worker.log
stopwaitsecs=3600
```

Start supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start watchtheflix-worker:*
```

#### Development

```bash
php artisan queue:work
```

### Email Configuration

#### Using SMTP

Update `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@watchtheflix.com"
MAIL_FROM_NAME="${APP_NAME}"
```

#### Using Mailgun

```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your_domain.com
MAILGUN_SECRET=your_secret_key
MAILGUN_ENDPOINT=api.mailgun.net
```

### Scheduled Tasks (Cron)

Add to crontab (`crontab -e`):
```bash
* * * * * cd /path/to/watchtheflix && php artisan schedule:run >> /dev/null 2>&1
```

This enables:
- Automated EPG updates (daily at 3 AM)
- Database backups
- Sitemap generation
- Other scheduled tasks

### Storage and Permissions

```bash
# Create symbolic link for storage
php artisan storage:link

# Set proper permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

## Optional Integrations

### TMDB API

1. Get API key from https://www.themoviedb.org/settings/api
2. Login as admin
3. Go to `/admin/settings`
4. Enter TMDB API key
5. Save settings

Now you can:
- Use `php artisan media:scrape` to fetch latest content
- Bulk import via admin UI at `/admin/tmdb-import`
- Automatic metadata enrichment

### EPG Provider

Update `.env`:
```env
EPG_PROVIDER_URL=http://example.com/epg.xml
```

Update EPG data:
```bash
php artisan epg:update
```

### Video.js Player

Video.js is included via npm. To use in your views:

```blade
@push('styles')
<link href="https://vjs.zencdn.net/8.10.0/video-js.css" rel="stylesheet" />
@endpush

<video id="my-video" class="video-js vjs-default-skin" controls preload="auto" width="640" height="360">
    <source src="{{ $media->stream_url }}" type="application/x-mpegURL">
    @foreach($media->subtitles ?? [] as $lang => $subtitle)
    <track kind="subtitles" src="{{ asset('storage/subtitles/' . $subtitle) }}" srclang="{{ $lang }}" label="{{ strtoupper($lang) }}">
    @endforeach
</video>

@push('scripts')
<script src="https://vjs.zencdn.net/8.10.0/video.min.js"></script>
<script>
    var player = videojs('my-video');
</script>
@endpush
```

## Production Deployment

### Optimization

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev
```

### Environment Variables

Ensure `.env` is configured for production:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Use production-ready drivers
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
SCOUT_DRIVER=meilisearch
```

### Web Server Configuration

#### Nginx

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/watchtheflix/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

#### Apache

Ensure `.htaccess` is present in the `public` directory and `mod_rewrite` is enabled.

### SSL Certificate

```bash
# Using Certbot
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d your-domain.com
```

## Troubleshooting

### Common Issues

**"Class 'Redis' not found"**
```bash
sudo apt install php8.2-redis
sudo systemctl restart php8.2-fpm
```

**"Connection refused (Redis)"**
```bash
sudo systemctl status redis-server
sudo systemctl start redis-server
```

**Queue jobs not processing**
```bash
# Check queue worker is running
sudo supervisorctl status

# Restart workers
php artisan queue:restart
```

**Search not working**
```bash
# Re-import search indexes
php artisan scout:import "App\Models\Media"
```

**EPG update fails**
```bash
# Check EPG provider URL is accessible
curl -I $EPG_PROVIDER_URL

# Run with verbose output
php artisan epg:update -v
```

## Security Checklist

- [ ] Change default admin password
- [ ] Set `APP_DEBUG=false` in production
- [ ] Configure firewall rules
- [ ] Enable SSL/TLS
- [ ] Set up regular backups
- [ ] Configure rate limiting
- [ ] Enable CSRF protection (default)
- [ ] Keep dependencies updated
- [ ] Monitor logs regularly
- [ ] Use strong database passwords
- [ ] Enable 2FA for admin accounts

## Performance Tuning

### PHP Configuration

Edit `php.ini`:
```ini
memory_limit = 512M
max_execution_time = 300
post_max_size = 100M
upload_max_filesize = 100M
opcache.enable = 1
opcache.memory_consumption = 256
```

### Database Optimization

```sql
-- Add indexes for frequently queried columns
CREATE INDEX idx_media_type ON media(type);
CREATE INDEX idx_media_rating ON media(tmdb_rating);
CREATE INDEX idx_tv_programs_dates ON tv_programs(start_time, end_time);
```

### Caching Strategy

```bash
# Cache everything
php artisan optimize

# Clear all caches
php artisan optimize:clear
```

## Support

- Documentation: This file
- Laravel Docs: https://laravel.com/docs
- Issues: GitHub Issues
- Community: Laravel Forums

---

**Built with ❤️ using Laravel 12**
