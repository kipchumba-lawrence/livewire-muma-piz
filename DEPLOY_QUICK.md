# ⚡ Quick Deployment Reference

## 🚀 One-Command Deployment

```bash
# Make sure deploy.sh is executable
chmod +x deploy.sh

# Run deployment
./deploy.sh
```

## 📋 Manual Deployment Steps

```bash
# 1. Backup database
mysqldump -u user -p database_name > backup_$(date +%Y%m%d_%H%M%S).sql

# 2. Pull code
git pull origin main

# 3. Install dependencies
composer install --no-dev --optimize-autoloader

# 4. Run migrations
php artisan migrate --force

# 5. Clear & cache
php artisan config:clear && php artisan cache:clear && php artisan route:clear && php artisan view:clear
php artisan config:cache && php artisan route:cache && php artisan view:cache

# 6. Set permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## 🔐 Critical Production Settings

```env
APP_ENV=production
APP_DEBUG=false          # ⚠️ MUST be false
DEBUGBAR_ENABLED=false   # ⚠️ MUST be false
LOG_LEVEL=error
TELESCOPE_ENABLED=false  # Recommended: disable
```

## 🆘 Quick Rollback

```bash
# Rollback migration
php artisan migrate:rollback --step=1

# Restore database
mysql -u user -p database_name < backup_YYYYMMDD_HHMMSS.sql

# Restore code
git reset --hard HEAD~1
```

## 📊 Check Logs

```bash
# View errors
tail -f storage/logs/laravel.log | grep -i error

# Web interface
https://your-domain.com/logs
```

## ✅ Post-Deployment Check

- [ ] Homepage loads
- [ ] Login works
- [ ] Booking works
- [ ] No errors in logs
- [ ] Admin dashboard accessible

---

**Full guides:**
- `DEPLOYMENT_CHECKLIST.md` - Complete checklist
- `PRODUCTION_LOGGING_GUIDE.md` - Logging in production

