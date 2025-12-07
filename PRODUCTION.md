# Production Deployment Guide

This guide covers everything needed to deploy WatchTheFlix to production.

## Pre-Deployment Checklist

### Required Environment Variables

```env
APP_NAME=WatchTheFlix
APP_ENV=production
APP_KEY=base64:XXXXXXXXXXXXXXXXXXXXX  # Generated via: php artisan key:generate
APP_DEBUG=false
APP_URL=https://yoursite.com

# Database (recommended: MySQL or PostgreSQL for production)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=watchtheflix
DB_USERNAME=your_username
DB_PASSWORD=your_secure_password

# Cache & Sessions (recommended: Redis for production)
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Mail Configuration (for email verification and notifications)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@watchtheflix.com"
MAIL_FROM_NAME="${APP_NAME}"

# Optional: TMDB API (for rich metadata)
TMDB_API_KEY=your_tmdb_api_key

# Security
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true
```

## Server Requirements

- PHP 8.2 or higher
- Composer 2.x
- Node.js 18+ and npm
- MySQL 8.0+ or PostgreSQL 13+ (SQLite not recommended for production)
- Redis (recommended for caching and sessions)
- Nginx or Apache with mod_rewrite
- SSL Certificate (Let's Encrypt recommended)
- 2GB RAM minimum (4GB+ recommended)
- 20GB+ disk space

## Installation Steps

### 1. Server Setup

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.2 and extensions
sudo apt install php8.2-fpm php8.2-cli php8.2-mysql php8.2-pgsql \
  php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip php8.2-gd \
  php8.2-redis php8.2-intl -y

# Install MySQL
sudo apt install mysql-server -y

# Install Redis
sudo apt install redis-server -y

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install nodejs -y

# Install Nginx
sudo apt install nginx -y
```

### 2. Deploy Application

```bash
# Clone repository
cd /var/www
sudo git clone https://github.com/Git-Cosmo/WatchTheFlix.git
cd WatchTheFlix

# Set permissions
sudo chown -R www-data:www-data /var/www/WatchTheFlix
sudo chmod -R 755 /var/www/WatchTheFlix
sudo chmod -R 775 storage bootstrap/cache

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Environment setup
cp .env.example .env
php artisan key:generate

# Edit .env with your production settings
nano .env

# Run migrations
php artisan migrate --force

# Seed initial data
php artisan db:seed --force

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Generate sitemap
php artisan sitemap:generate

# Link storage
php artisan storage:link
```

### 3. Nginx Configuration

Create `/etc/nginx/sites-available/watchtheflix`:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yoursite.com www.yoursite.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name yoursite.com www.yoursite.com;
    root /var/www/WatchTheFlix/public;

    # SSL Configuration
    ssl_certificate /etc/letsencrypt/live/yoursite.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yoursite.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' https: data: 'unsafe-inline' 'unsafe-eval';" always;

    index index.php;
    charset utf-8;

    # Logging
    access_log /var/log/nginx/watchtheflix_access.log;
    error_log /var/log/nginx/watchtheflix_error.log;

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
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Asset caching
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

Enable the site:

```bash
sudo ln -s /etc/nginx/sites-available/watchtheflix /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### 4. SSL Certificate (Let's Encrypt)

```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d yoursite.com -d www.yoursite.com
```

### 5. Set Up Supervisor (for Queue Workers)

Create `/etc/supervisor/conf.d/watchtheflix-worker.conf`:

```ini
[program:watchtheflix-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/WatchTheFlix/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/WatchTheFlix/storage/logs/worker.log
stopwaitsecs=3600
```

Start supervisor:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start watchtheflix-worker:*
```

### 6. Set Up Cron Jobs

Add to crontab (`sudo crontab -e -u www-data`):

```cron
* * * * * cd /var/www/WatchTheFlix && php artisan schedule:run >> /dev/null 2>&1
0 2 * * * cd /var/www/WatchTheFlix && php artisan sitemap:generate >> /dev/null 2>&1
0 3 * * * cd /var/www/WatchTheFlix && php artisan db:backup >> /dev/null 2>&1
```

## Security Best Practices

### 1. Firewall Configuration

```bash
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
```

### 2. Fail2Ban (optional but recommended)

```bash
sudo apt install fail2ban -y
sudo systemctl enable fail2ban
sudo systemctl start fail2ban
```

### 3. Database Security

```bash
# Secure MySQL installation
sudo mysql_secure_installation

# Create dedicated database user
mysql -u root -p
CREATE DATABASE watchtheflix CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'watchtheflix'@'localhost' IDENTIFIED BY 'strong_password_here';
GRANT ALL PRIVILEGES ON watchtheflix.* TO 'watchtheflix'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 4. File Permissions

```bash
# Set correct ownership
sudo chown -R www-data:www-data /var/www/WatchTheFlix

# Set correct permissions
find /var/www/WatchTheFlix -type f -exec chmod 644 {} \;
find /var/www/WatchTheFlix -type d -exec chmod 755 {} \;
chmod -R 775 /var/www/WatchTheFlix/storage
chmod -R 775 /var/www/WatchTheFlix/bootstrap/cache
```

## Performance Optimization

### 1. OpCache Configuration

Edit `/etc/php/8.2/fpm/conf.d/10-opcache.ini`:

```ini
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
opcache.fast_shutdown=1
```

### 2. Redis Configuration

Edit `/etc/redis/redis.conf`:

```ini
maxmemory 256mb
maxmemory-policy allkeys-lru
```

### 3. PHP-FPM Tuning

Edit `/etc/php/8.2/fpm/pool.d/www.conf`:

```ini
pm = dynamic
pm.max_children = 50
pm.start_servers = 10
pm.min_spare_servers = 5
pm.max_spare_servers = 20
pm.max_requests = 500
```

## Monitoring & Maintenance

### Log Files

- Application logs: `/var/www/WatchTheFlix/storage/logs/laravel.log`
- Nginx access: `/var/log/nginx/watchtheflix_access.log`
- Nginx error: `/var/log/nginx/watchtheflix_error.log`
- PHP-FPM: `/var/log/php8.2-fpm.log`

### Automated Backups

The system includes automated daily database backups via the `db:backup` command. Backups are stored in `storage/app/backups/`.

Configure external backup storage (S3, etc.) in `config/backup.php`.

### Health Checks

- Health endpoint: `https://yoursite.com/up`
- Monitor this endpoint for uptime

### Updates

```bash
cd /var/www/WatchTheFlix
sudo -u www-data git pull
sudo -u www-data composer install --optimize-autoloader --no-dev
sudo -u www-data npm install
sudo -u www-data npm run build
sudo -u www-data php artisan migrate --force
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache
sudo systemctl reload php8.2-fpm
sudo systemctl reload nginx
```

## Troubleshooting

### Clear All Caches

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
```

### Permission Issues

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### Database Connection Issues

- Verify credentials in `.env`
- Check MySQL is running: `sudo systemctl status mysql`
- Test connection: `php artisan tinker` then `DB::connection()->getPdo();`

## Support

For issues and support:
- GitHub Issues: https://github.com/Git-Cosmo/WatchTheFlix/issues
- Documentation: See README.md

## License

This project is open-source software licensed under the MIT license.
