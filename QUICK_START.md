# 🚀 Quick Start Guide - Logging & Debugging

## Your Mumaapix Application is Now Enhanced! 🎉

You now have professional-grade logging and debugging capabilities installed and configured.

## 📦 What's Installed

✅ **Laravel Telescope** - Full application monitoring  
✅ **Laravel Debugbar** - Development toolbar  
✅ **Laravel Log Viewer** - Web-based log browser  
✅ **Spatie Activity Log** - Track all changes  
✅ **Custom Logging Channels** - Organized logging  
✅ **Logging Middleware** - Automatic HTTP logging  
✅ **Loggable Trait** - Easy logging in any class  

## 🎯 Access Your Tools

### 1. Telescope Dashboard
```
URL: http://your-app.test/telescope
Requirements: Login as admin
```
**What you'll see:**
- All HTTP requests
- Database queries with timing
- Exceptions and errors
- Jobs and queues
- Cache operations
- Mail sent

### 2. Log Viewer
```
URL: http://your-app.test/logs
Requirements: Login as admin
```
**What you can do:**
- Browse all log files
- Filter by level (error, warning, info, etc.)
- Search through logs
- Download logs

### 3. Debugbar (Auto-enabled in dev)
```
Appears: Bottom of every page when APP_DEBUG=true
```
**Shows:**
- SQL queries
- Page load time
- Memory usage
- View data

## ⚡ Quick Usage Examples

### In Any Controller or Livewire Component

```php
use App\Traits\Loggable;

class YourClass extends Component
{
    use Loggable;
    
    public function yourMethod()
    {
        // Basic logging
        $this->logInfo('User performed action');
        $this->logWarning('Something unusual happened');
        $this->logError('An error occurred');
        
        // M-Pesa logging
        $this->logMpesaTransaction('payment_received', [
            'amount' => 1000,
            'phone' => '254712345678'
        ]);
        
        // Pipeline logging
        $this->logPipelineAction('booking_created', $pipelineId);
        
        // User activity logging
        $this->logUserActivity('profile_updated', [
            'field' => 'email',
            'old' => 'old@example.com',
            'new' => 'new@example.com'
        ]);
    }
}
```

### Using Standard Laravel Log

```php
use Illuminate\Support\Facades\Log;

// Default channel
Log::info('User logged in', ['user_id' => auth()->id()]);

// Specific channel
Log::channel('mpesa')->info('Payment received');
Log::channel('pipeline')->warning('Booking delayed');
Log::channel('user_activity')->info('Profile updated');
```

## 📂 Log Files Location

```
storage/logs/
├── laravel-2025-11-09.log      # Main app logs
├── mpesa-2025-11-09.log         # Payment logs
├── pipeline-2025-11-09.log      # Pipeline logs
└── user-activity-2025-11-09.log # User action logs
```

## 🛠️ Artisan Commands

```bash
# View logs in real-time
tail -f storage/logs/laravel.log

# Clear old logs (older than 30 days)
php artisan logs:clear

# Clear old logs (older than 7 days)
php artisan logs:clear --days=7

# Clear logs without confirmation
php artisan logs:clear --force

# Clear Telescope data
php artisan telescope:clear

# Clear Activity Log data
php artisan activitylog:clean
```

## 🔍 Debugging Workflow

### When Something Goes Wrong:

1. **Check Telescope** (`/telescope`)
   - Look at the failed request
   - Check the exception tab
   - Review the queries tab

2. **Check Log Viewer** (`/logs`)
   - Filter by "error" level
   - Search for error messages
   - Check the timestamp

3. **Check Specific Logs**
   ```bash
   # M-Pesa issues
   tail -f storage/logs/mpesa.log
   
   # Pipeline issues
   tail -f storage/logs/pipeline.log
   
   # General errors
   tail -f storage/logs/laravel.log
   ```

4. **Check Debugbar** (if in dev)
   - Look at SQL queries
   - Check for N+1 problems
   - Review execution time

## 💡 Pro Tips

### 1. Monitor Your Application
- Check Telescope daily for errors
- Review slow queries
- Monitor memory usage

### 2. Use Specific Channels
```php
// Group related logs
Log::channel('mpesa')->info('Transaction started');
Log::channel('mpesa')->info('Transaction completed');
```

### 3. Add Context
```php
// Always include relevant data
$this->logError('Payment failed', [
    'user_id' => auth()->id(),
    'amount' => $amount,
    'error' => $exception->getMessage()
]);
```

### 4. Track Model Changes
- All User changes are automatically logged
- All Pipeline changes are automatically logged
- View in activity_log table or Telescope

### 5. Performance Monitoring
```php
// Log slow operations
$start = microtime(true);
// ... operation ...
$time = round((microtime(true) - $start) * 1000, 2);

if ($time > 1000) {
    $this->logWarning('Slow operation detected', [
        'operation' => 'image_upload',
        'time_ms' => $time
    ]);
}
```

## 🎨 Example: Enhanced Booking Process

The `ClientBooking` component has been enhanced with logging:

```php
// Logs:
// 1. When booking starts
// 2. M-Pesa STK push initiated
// 3. M-Pesa response received
// 4. Pipeline created
// 5. Success or error

// You can now track:
// - How many bookings are attempted
// - How many payments succeed/fail
// - Where customers drop off
// - Common errors
```

## 📊 Activity Log Examples

```php
// See what changed
$user = User::find(1);
$activities = $user->activities;

foreach ($activities as $activity) {
    echo $activity->description; // "User updated"
    echo $activity->changes; // ["name" => ["old" => "John", "new" => "Jane"]]
}

// Log custom activities
activity()
    ->performedOn($pipeline)
    ->causedBy(auth()->user())
    ->log('Pipeline status manually changed');
```

## 🔐 Security

- ✅ Telescope: Admin only
- ✅ Log Viewer: Admin only
- ✅ Debugbar: Local environment only
- ✅ Passwords: Never logged
- ✅ Tokens: Never logged

## 🚨 Common Issues & Solutions

### Issue: "Can't access /telescope"
**Solution:** Login as admin user (role = 'admin')

### Issue: "Debugbar not showing"
**Solution:** 
```bash
# In .env
APP_DEBUG=true
DEBUGBAR_ENABLED=true

# Then
php artisan config:clear
```

### Issue: "Logs not being written"
**Solution:**
```bash
# Fix permissions
chmod -R 775 storage/logs
chown -R www-data:www-data storage/logs
```

### Issue: "Too many logs"
**Solution:**
```bash
# Clear old logs
php artisan logs:clear --days=7 --force
```

## 📚 Full Documentation

- **Complete Guide:** `LOGGING_GUIDE.md`
- **Setup Summary:** `SETUP_SUMMARY.md`
- **This Quick Start:** `QUICK_START.md`

## 🎓 Learning Resources

1. Start with simple logging in one component
2. Check Telescope to see the results
3. Add more logging gradually
4. Use specific channels for organization
5. Review logs regularly

## 🤝 Best Practices Recap

1. ✅ Log important events
2. ✅ Use appropriate log levels
3. ✅ Include context data
4. ✅ Use specific channels
5. ✅ Don't log sensitive data
6. ✅ Monitor Telescope regularly
7. ✅ Clear old logs periodically

---

## 🎉 You're All Set!

Your application now has enterprise-level logging and monitoring. Start by:

1. Opening Telescope: `/telescope`
2. Making a test booking
3. Watching the logs appear in real-time
4. Exploring the different tabs

**Need help?** Check the full documentation in `LOGGING_GUIDE.md`

Happy coding! 🚀

