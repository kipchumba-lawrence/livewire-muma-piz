# 🚀 Deployment Checklist for Mumaapix

## Pre-Deployment Checklist

### 1. Code Preparation
- [ ] All code changes committed to version control
- [ ] Code reviewed and tested locally
- [ ] All migrations tested locally
- [ ] No debug code or `dd()`, `dump()`, `var_dump()` in codebase
- [ ] `.env.example` updated with new variables (if any)
- [ ] `composer.json` and `composer.lock` committed
- [ ] `package.json` and `package-lock.json` committed (if using npm)

### 2. Database Preparation
- [ ] **BACKUP YOUR PRODUCTION DATABASE FIRST!**
  ```bash
  # MySQL
  mysqldump -u user -p database_name > backup_$(date +%Y%m%d_%H%M%S).sql
  
  # Or use Laravel backup
  php artisan backup:run
  ```
- [ ] Test all migrations on staging/local first
- [ ] Verify migration rollback works
- [ ] Check migration status: `php artisan migrate:status`
- [ ] Document any manual database changes needed

### 3. Environment Configuration
- [ ] `.env` file configured for production
- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false` ⚠️ **CRITICAL**
- [ ] `APP_URL` set correctly
- [ ] Database credentials verified
- [ ] Mail configuration set up
- [ ] Cache driver configured (Redis/Memcached recommended)
- [ ] Queue driver configured (if using queues)
- [ ] Log channel set: `LOG_CHANNEL=daily`
- [ ] Log level set: `LOG_LEVEL=error` (or `warning` for production)

### 4. Security Checks
- [ ] `APP_KEY` is set and secure
- [ ] All sensitive data in `.env`, not in code
- [ ] `.env` file is in `.gitignore`
- [ ] File permissions set correctly:
  ```bash
  chmod -R 755 storage bootstrap/cache
  chown -R www-data:www-data storage bootstrap/cache
  ```
- [ ] Telescope access restricted (admin only)
- [ ] Log Viewer access restricted (admin only)
- [ ] Debugbar disabled in production
- [ ] HTTPS enabled
- [ ] CSRF protection enabled

### 5. Dependencies
- [ ] Run `composer install --no-dev --optimize-autoloader`
- [ ] Run `npm install --production` (if using npm)
- [ ] Run `npm run production` (if using Laravel Mix)
- [ ] Verify all required PHP extensions installed
- [ ] Check PHP version compatibility (PHP 8.1+)

### 6. Logging & Monitoring Setup
- [ ] Log directories exist and are writable:
  ```bash
  mkdir -p storage/logs
  chmod -R 775 storage/logs
  ```
- [ ] Telescope configured for production (filtering enabled)
- [ ] Log rotation configured
- [ ] Disk space available for logs
- [ ] Monitoring alerts set up (if applicable)

---

## Deployment Steps

### Step 1: Pre-Deployment Backup
```bash
# 1. Backup database
mysqldump -u user -p database_name > backup_$(date +%Y%m%d_%H%M%S).sql

# 2. Backup current code (optional but recommended)
tar -czf backup_code_$(date +%Y%m%d_%H%M%S).tar.gz /path/to/current/app

# 3. Backup .env file
cp .env .env.backup
```

### Step 2: Pull Latest Code
```bash
# If using Git
git pull origin main  # or your production branch

# Or upload files via FTP/SFTP
```

### Step 3: Install Dependencies
```bash
# Install production dependencies only
composer install --no-dev --optimize-autoloader

# If using npm
npm install --production
npm run production
```

### Step 4: Run Migrations
```bash
# ⚠️ CRITICAL: Backup database first!

# Check what migrations will run
php artisan migrate:status

# Run migrations
php artisan migrate --force

# Verify migrations ran successfully
php artisan migrate:status
```

### Step 5: Clear and Cache
```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Cache for production (performance)
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

### Step 6: Set Permissions
```bash
# Set correct permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Ensure log directory is writable
chmod -R 775 storage/logs
```

### Step 7: Verify Application
- [ ] Test homepage loads
- [ ] Test login functionality
- [ ] Test booking creation
- [ ] Test admin dashboard
- [ ] Check error logs: `tail -f storage/logs/laravel.log`
- [ ] Verify Telescope is accessible (if enabled)
- [ ] Verify Log Viewer is accessible (if enabled)

### Step 8: Post-Deployment
- [ ] Monitor error logs for first 30 minutes
- [ ] Check application performance
- [ ] Verify all features working
- [ ] Test critical user flows
- [ ] Monitor server resources (CPU, memory, disk)

---

## Production Configuration

### Environment Variables (.env)
```env
# Application
APP_NAME="Mumaapix"
APP_ENV=production
APP_KEY=base64:your-generated-key
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Logging
LOG_CHANNEL=daily
LOG_LEVEL=error
LOG_DEPRECATIONS_CHANNEL=null

# Telescope (optional - disable in production or use strict filtering)
TELESCOPE_ENABLED=false

# Debugbar (must be disabled)
DEBUGBAR_ENABLED=false

# Cache
CACHE_DRIVER=redis  # or file
SESSION_DRIVER=redis  # or file
QUEUE_CONNECTION=redis  # or database

# Mail
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Telescope Production Settings

Edit `app/Providers/TelescopeServiceProvider.php`:

```php
public function register(): void
{
    // Disable Telescope in production or use strict filtering
    if ($this->app->environment('production')) {
        Telescope::night(); // Only record at night (reduces load)
        
        // Or disable completely:
        // return;
    }

    // Strict filtering for production
    Telescope::filter(function (IncomingEntry $entry) {
        if ($this->app->environment('production')) {
            // Only record errors and exceptions
            return $entry->isReportableException() ||
                   $entry->isFailedRequest() ||
                   $entry->isFailedJob();
        }
        
        return true;
    });
}
```

---

## Rollback Plan

### If Something Goes Wrong

#### 1. Rollback Migrations
```bash
# Rollback last migration
php artisan migrate:rollback --step=1

# Rollback last 3 migrations
php artisan migrate:rollback --step=3

# ⚠️ WARNING: Only rollback if you have a backup!
```

#### 2. Restore Database
```bash
# Restore from backup
mysql -u user -p database_name < backup_YYYYMMDD_HHMMSS.sql
```

#### 3. Restore Code
```bash
# If using Git
git reset --hard HEAD~1
git pull

# Or restore from backup
tar -xzf backup_code_YYYYMMDD_HHMMSS.tar.gz
```

#### 4. Clear Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

---

## Post-Deployment Monitoring

### First 24 Hours
- [ ] Monitor error logs every hour
- [ ] Check application performance
- [ ] Monitor database performance
- [ ] Check disk space (logs can grow quickly)
- [ ] Monitor memory usage
- [ ] Check for any user-reported issues

### Weekly Checks
- [ ] Review error logs
- [ ] Check log file sizes
- [ ] Review Telescope data (if enabled)
- [ ] Clean old logs: `php artisan logs:clear`
- [ ] Check database size
- [ ] Review performance metrics

---

## Quick Deployment Script

Create a `deploy.sh` script:

```bash
#!/bin/bash

set -e  # Exit on error

echo "🚀 Starting deployment..."

# Backup database
echo "📦 Backing up database..."
mysqldump -u user -p database_name > backup_$(date +%Y%m%d_%H%M%S).sql

# Pull latest code
echo "📥 Pulling latest code..."
git pull origin main

# Install dependencies
echo "📚 Installing dependencies..."
composer install --no-dev --optimize-autoloader

# Run migrations
echo "🗄️ Running migrations..."
php artisan migrate --force

# Clear caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Cache for production
echo "⚡ Caching for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
echo "🔐 Setting permissions..."
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

echo "✅ Deployment complete!"
echo "🔍 Check logs: tail -f storage/logs/laravel.log"
```

Make it executable:
```bash
chmod +x deploy.sh
```

---

## Troubleshooting

### Common Issues

#### Migration Fails
```bash
# Check migration status
php artisan migrate:status

# Check for errors
php artisan migrate --pretend

# Rollback if needed
php artisan migrate:rollback --step=1
```

#### Permission Errors
```bash
# Fix permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

#### Logs Not Writing
```bash
# Check permissions
ls -la storage/logs

# Fix permissions
chmod -R 775 storage/logs
chown -R www-data:www-data storage/logs
```

#### Telescope Not Accessible
- Check `TELESCOPE_ENABLED` in `.env`
- Verify admin user has `role = 'admin'`
- Check `app/Providers/TelescopeServiceProvider.php` gate

#### High Memory Usage
- Disable Telescope in production
- Reduce log retention days
- Clear old Telescope data: `php artisan telescope:clear`
- Clear old logs: `php artisan logs:clear`

---

## Security Reminders

- ✅ **NEVER** commit `.env` file
- ✅ **ALWAYS** set `APP_DEBUG=false` in production
- ✅ **ALWAYS** backup database before migrations
- ✅ **RESTRICT** Telescope and Log Viewer to admins only
- ✅ **DISABLE** Debugbar in production
- ✅ **USE** HTTPS in production
- ✅ **KEEP** dependencies updated
- ✅ **MONITOR** error logs regularly

---

## Success Criteria

Deployment is successful when:
- ✅ All migrations ran without errors
- ✅ Application loads without errors
- ✅ Users can log in
- ✅ Bookings can be created
- ✅ Admin dashboard accessible
- ✅ No errors in logs
- ✅ Performance is acceptable
- ✅ All features working as expected

---

**Last Updated:** November 9, 2025  
**Version:** 1.0

