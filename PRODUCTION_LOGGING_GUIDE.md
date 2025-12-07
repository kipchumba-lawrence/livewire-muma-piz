# 📊 Production Logging Guide

## Using Logging Tools in Production

This guide explains how to safely use the logging and monitoring tools in your production environment.

---

## 🔐 Security First

### Important Security Notes

1. **Telescope** - Should be **disabled** or **strictly filtered** in production
2. **Debugbar** - Must be **disabled** in production
3. **Log Viewer** - Only accessible to **admin users**
4. **Logs** - May contain sensitive data, protect them

---

## 🎯 Recommended Production Setup

### 1. Telescope Configuration

#### Option A: Disable Completely (Recommended)
```env
# In .env
TELESCOPE_ENABLED=false
```

#### Option B: Enable with Strict Filtering
```env
# In .env
TELESCOPE_ENABLED=true
```

Then edit `app/Providers/TelescopeServiceProvider.php`:

```php
public function register(): void
{
    // Only record at night to reduce load
    Telescope::night();
    
    // Strict filtering - only record errors
    Telescope::filter(function (IncomingEntry $entry) {
        return $entry->isReportableException() ||
               $entry->isFailedRequest() ||
               $entry->isFailedJob() ||
               $entry->hasMonitoredTag();
    });
    
    // Hide sensitive data
    Telescope::hideRequestParameters(['password', 'password_confirmation', '_token']);
    Telescope::hideRequestHeaders(['cookie', 'authorization']);
}
```

### 2. Debugbar Configuration

**MUST be disabled in production:**

```env
# In .env
APP_DEBUG=false
DEBUGBAR_ENABLED=false
```

### 3. Log Viewer

Log Viewer is safe to use in production as it's:
- ✅ Protected by admin middleware
- ✅ Read-only (doesn't modify data)
- ✅ Only shows log files

Access: `https://your-domain.com/logs` (admin only)

---

## 📋 Logging Configuration

### Production .env Settings

```env
# Logging
LOG_CHANNEL=daily
LOG_LEVEL=error
LOG_DEPRECATIONS_CHANNEL=null

# Telescope (disable or enable with filtering)
TELESCOPE_ENABLED=false

# Debugbar (must be false)
DEBUGBAR_ENABLED=false
```

### Log Channels

Your application uses these log channels:

1. **daily** - General application logs (14 days retention)
2. **mpesa** - Payment transaction logs (30 days retention)
3. **pipeline** - Booking/operation logs (30 days retention)
4. **user_activity** - User action logs (60 days retention)

---

## 🔍 Accessing Logs in Production

### Method 1: Log Viewer (Recommended)

1. Login as admin user
2. Visit: `https://your-domain.com/logs`
3. Browse, search, and filter logs
4. Download logs if needed

**Advantages:**
- ✅ Web-based interface
- ✅ No SSH access needed
- ✅ Secure (admin only)
- ✅ Easy to use

### Method 2: SSH/Terminal

```bash
# View latest logs
tail -f storage/logs/laravel.log

# View specific log
tail -f storage/logs/mpesa-2025-11-09.log

# Search logs
grep "error" storage/logs/laravel.log

# View last 100 lines
tail -n 100 storage/logs/laravel.log

# View logs from today
cat storage/logs/laravel-$(date +%Y-%m-%d).log
```

### Method 3: Telescope (If Enabled)

1. Login as admin
2. Visit: `https://your-domain.com/telescope`
3. Navigate to "Logs" tab
4. Filter and search

---

## 📊 Monitoring Best Practices

### Daily Checks

1. **Check Error Logs**
   ```bash
   tail -n 50 storage/logs/laravel.log | grep -i error
   ```

2. **Check Log File Sizes**
   ```bash
   ls -lh storage/logs/
   ```

3. **Monitor Disk Space**
   ```bash
   df -h
   ```

### Weekly Tasks

1. **Review Error Patterns**
   - Look for recurring errors
   - Identify common issues
   - Fix root causes

2. **Clean Old Logs**
   ```bash
   php artisan logs:clear --days=7
   ```

3. **Review Activity Logs**
   - Check user activity patterns
   - Monitor booking trends
   - Review payment logs

### Monthly Tasks

1. **Archive Old Logs**
   ```bash
   # Compress old logs
   tar -czf logs_archive_$(date +%Y%m).tar.gz storage/logs/*.log
   
   # Move to archive location
   mv logs_archive_*.tar.gz /path/to/archive/
   ```

2. **Review Log Retention Settings**
   - Adjust retention periods if needed
   - Update in `config/logging.php`

3. **Performance Review**
   - Check log file sizes
   - Review database size (Telescope, Activity Log)
   - Optimize if needed

---

## 🛠️ Using Logging in Your Code

### In Production, Log Appropriately

```php
use App\Traits\Loggable;

class YourComponent extends Component
{
    use Loggable;
    
    public function yourMethod()
    {
        // ✅ Good: Log important events
        $this->logInfo('Important action performed', [
            'user_id' => auth()->id(),
            'action' => 'booking_created'
        ]);
        
        // ✅ Good: Log errors
        try {
            // Risky operation
        } catch (\Exception $e) {
            $this->logError('Operation failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
        }
        
        // ❌ Bad: Don't log sensitive data
        // $this->logInfo('User login', ['password' => $password]); // NEVER!
        
        // ❌ Bad: Don't log in loops
        // foreach ($items as $item) {
        //     $this->logInfo('Processing', ['item' => $item]); // Too many logs!
        // }
    }
}
```

### Log Levels for Production

- **error** - Only log errors and critical issues
- **warning** - Log warnings and important events
- **info** - Log informational messages (use sparingly)
- **debug** - Don't use in production

---

## 🔄 Log Rotation

### Automatic Rotation

Laravel automatically rotates logs daily when using `daily` channel.

### Manual Rotation

```bash
# Clear logs older than 30 days
php artisan logs:clear --days=30

# Clear logs older than 7 days (more aggressive)
php artisan logs:clear --days=7

# Clear without confirmation
php artisan logs:clear --days=30 --force
```

### Telescope Data Cleanup

```bash
# Clear old Telescope entries
php artisan telescope:clear

# Prune old entries (keeps last 24 hours)
php artisan telescope:prune --hours=24
```

### Activity Log Cleanup

```bash
# Clean old activity logs
php artisan activitylog:clean

# Clean logs older than 90 days
php artisan activitylog:clean --days=90
```

---

## 📈 Monitoring Dashboard

### What to Monitor

1. **Error Rate**
   - Count errors per day
   - Track error trends
   - Identify spikes

2. **Booking Activity**
   - Monitor booking creation
   - Track payment success/failure
   - Review pipeline status changes

3. **User Activity**
   - Track user logins
   - Monitor admin actions
   - Review profile updates

4. **System Performance**
   - Check log file sizes
   - Monitor database growth
   - Review memory usage

---

## 🚨 Alerting (Optional)

### Set Up Email Alerts for Critical Errors

Create a custom log handler in `config/logging.php`:

```php
'channels' => [
    'stack' => [
        'driver' => 'stack',
        'channels' => ['daily', 'slack'], // Add slack for alerts
        'ignore_exceptions' => false,
    ],
    
    'slack' => [
        'driver' => 'slack',
        'url' => env('LOG_SLACK_WEBHOOK_URL'),
        'username' => 'Mumaapix Alerts',
        'emoji' => ':warning:',
        'level' => 'critical', // Only critical errors
    ],
],
```

Or use Laravel's built-in notification system for critical errors.

---

## 🔒 Security Best Practices

### 1. Protect Log Files

```bash
# Set proper permissions
chmod 640 storage/logs/*.log
chown www-data:www-data storage/logs/*.log
```

### 2. Don't Log Sensitive Data

**Never log:**
- Passwords
- Credit card numbers
- API keys
- Tokens
- Personal identification numbers

**Safe to log:**
- User IDs
- Action types
- Timestamps
- Error messages (sanitized)
- Request URLs (without parameters)

### 3. Restrict Access

- ✅ Log Viewer: Admin only
- ✅ Telescope: Admin only (if enabled)
- ✅ Log files: Server access only
- ✅ Database logs: Admin only

---

## 📝 Logging Checklist for Production

- [ ] `APP_DEBUG=false` in `.env`
- [ ] `DEBUGBAR_ENABLED=false` in `.env`
- [ ] `LOG_LEVEL=error` in `.env`
- [ ] `LOG_CHANNEL=daily` in `.env`
- [ ] Telescope disabled or strictly filtered
- [ ] Log directories have correct permissions
- [ ] Log rotation configured
- [ ] Old logs cleanup scheduled
- [ ] Sensitive data not logged
- [ ] Admin access restricted
- [ ] Log files backed up regularly

---

## 🎯 Quick Reference

### View Logs
```bash
# Latest errors
tail -f storage/logs/laravel.log | grep -i error

# Today's logs
cat storage/logs/laravel-$(date +%Y-%m-%d).log

# Web interface
https://your-domain.com/logs
```

### Clear Logs
```bash
# Clear old logs
php artisan logs:clear --days=30

# Clear Telescope
php artisan telescope:clear

# Clear Activity Log
php artisan activitylog:clean
```

### Check Status
```bash
# Log file sizes
ls -lh storage/logs/

# Disk space
df -h

# Recent errors
tail -n 100 storage/logs/laravel.log | grep -i error
```

---

## 💡 Pro Tips

1. **Monitor Daily**: Check logs every day for the first week after deployment
2. **Set Alerts**: Configure alerts for critical errors
3. **Archive Logs**: Keep archived logs for compliance/auditing
4. **Review Patterns**: Look for patterns in errors to fix root causes
5. **Document Issues**: Document common issues and their solutions
6. **Test Logging**: Test your logging setup before going live
7. **Backup Logs**: Include logs in your backup strategy

---

## 🆘 Troubleshooting

### Logs Not Writing
```bash
# Check permissions
ls -la storage/logs

# Fix permissions
chmod -R 775 storage/logs
chown -R www-data:www-data storage/logs
```

### Too Many Logs
```bash
# Increase log level
# In .env: LOG_LEVEL=error

# Clear old logs
php artisan logs:clear --days=7
```

### Disk Space Full
```bash
# Check disk usage
df -h

# Clear old logs
php artisan logs:clear --days=7 --force

# Clear Telescope data
php artisan telescope:clear
```

### Can't Access Log Viewer
- Verify you're logged in as admin
- Check route is registered
- Verify middleware is applied
- Check server logs for errors

---

**Remember:** Good logging helps you maintain and improve your application. Use it wisely! 🚀

