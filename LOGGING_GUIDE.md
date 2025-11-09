# Logging & Debugging Guide for Mumaapix

This project now includes comprehensive logging and debugging tools to help you monitor, debug, and improve your application.

## 📦 Installed Packages

### 1. Laravel Telescope
**Purpose**: Application monitoring and debugging
**Access**: `/telescope` (admin only)
**Features**:
- Request tracking
- Query monitoring
- Job tracking
- Exception tracking
- Log monitoring
- Mail tracking
- Cache operations
- Redis operations

### 2. Laravel Debugbar
**Purpose**: Development debugging toolbar
**Enabled**: Automatically in local environment
**Features**:
- Request information
- SQL queries with timing
- View data
- Route information
- Session data
- Memory usage
- Performance metrics

### 3. Laravel Log Viewer
**Purpose**: Browse logs through a web interface
**Access**: `/logs` (admin only)
**Features**:
- View all log files
- Filter by log level
- Search through logs
- Download logs
- Real-time log viewing

### 4. Spatie Activity Log
**Purpose**: Track model changes and user activities
**Documentation**: https://spatie.be/docs/laravel-activitylog
**Features**:
- Automatic model change tracking
- User activity logging
- Searchable activity log

## 🎯 Custom Logging Channels

The project includes custom logging channels for different concerns:

### Available Channels:
1. **default** (daily logs): General application logs
2. **mpesa**: M-Pesa payment transactions
3. **pipeline**: Pipeline operations (bookings, edits, shoots)
4. **user_activity**: User actions and HTTP requests

## 🔧 Using the Loggable Trait

The `App\Traits\Loggable` trait provides convenient logging methods. Use it in any class:

```php
use App\Traits\Loggable;

class YourClass
{
    use Loggable;
    
    public function yourMethod()
    {
        // Log basic messages
        $this->logInfo('User created successfully', ['user_id' => 1]);
        $this->logDebug('Debug information', ['data' => $data]);
        $this->logWarning('Something might be wrong', ['value' => $value]);
        $this->logError('An error occurred', ['error' => $error]);
        $this->logCritical('Critical failure!', ['details' => $details]);
        
        // Log user activity
        $this->logUserActivity('profile_updated', [
            'changes' => $changes
        ]);
        
        // Log M-Pesa transactions
        $this->logMpesaTransaction('payment_initiated', [
            'amount' => 1000,
            'phone' => '254712345678',
            'reference' => 'REF123'
        ]);
        
        // Log pipeline actions
        $this->logPipelineAction('booking_created', $pipelineId, [
            'client_name' => 'John Doe',
            'package' => 'Premium'
        ]);
    }
}
```

## 📊 Using Standard Laravel Logging

You can also use Laravel's built-in logging:

```php
use Illuminate\Support\Facades\Log;

// Default channel
Log::info('User logged in', ['user_id' => auth()->id()]);

// Specific channel
Log::channel('mpesa')->info('Payment received', [
    'amount' => 1000,
    'transaction_id' => 'ABC123'
]);

Log::channel('pipeline')->warning('Pipeline delayed', [
    'pipeline_id' => 5,
    'delay_reason' => 'Weather conditions'
]);

// Multiple log levels
Log::debug('Debug information');
Log::info('Informational message');
Log::notice('Normal but significant');
Log::warning('Warning message');
Log::error('Error message');
Log::critical('Critical conditions');
Log::alert('Action must be taken immediately');
Log::emergency('System is unusable');
```

## 🎬 Activity Logging with Spatie

### Setup for Models
To track changes to your models:

```php
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Pipeline extends Model
{
    use LogsActivity;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']) // Log all attributes
            ->logOnlyDirty() // Only log changed attributes
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Pipeline {$eventName}");
    }
}
```

### Manual Activity Logging

```php
use Spatie\Activitylog\Models\Activity;

// Log an activity
activity()
    ->performedOn($pipeline)
    ->causedBy(auth()->user())
    ->withProperties(['custom' => 'data'])
    ->log('Pipeline status changed');

// Retrieve activities
$activities = Activity::all();
$lastActivity = Activity::all()->last();

// Get activities for a specific model
$pipeline = Pipeline::find(1);
$activities = $pipeline->activities;
```

## 🔍 Accessing Logs

### 1. Web Interface (Log Viewer)
- Visit `/logs` (requires admin role)
- Browse all log files
- Filter by level (info, warning, error, etc.)
- Search through logs
- Download logs for offline analysis

### 2. Telescope Dashboard
- Visit `/telescope` (requires admin role)
- Real-time monitoring
- Detailed request information
- Query performance
- Exception tracking

### 3. File System
Logs are stored in `storage/logs/`:
- `laravel.log` - General application logs
- `mpesa.log` - M-Pesa payment logs
- `pipeline.log` - Pipeline operation logs
- `user-activity.log` - User action logs

### 4. Terminal
```bash
# Watch logs in real-time
tail -f storage/logs/laravel.log

# View specific log
cat storage/logs/mpesa-2025-11-09.log

# Search logs
grep "error" storage/logs/laravel.log

# View last 100 lines
tail -n 100 storage/logs/laravel.log
```

## 🛠️ Development Tools

### Laravel Debugbar
When `APP_DEBUG=true`, the Debugbar appears at the bottom of every page showing:
- Executed SQL queries
- Page load time
- Memory usage
- View data
- Session information
- Request details

### Disabling Debugbar Temporarily
Add to `.env`:
```
DEBUGBAR_ENABLED=false
```

## 🔐 Security Considerations

1. **Production Logs**: Never log sensitive data (passwords, API keys, credit cards)
2. **Access Control**: Telescope and Log Viewer are restricted to admin users only
3. **Log Rotation**: Logs are automatically rotated and old logs are deleted based on configuration
4. **Sensitive Data**: The logging middleware doesn't log request body to avoid capturing passwords

## 📈 Best Practices

1. **Use Appropriate Log Levels**:
   - `debug`: Detailed debugging information
   - `info`: Interesting events (user login, SQL logs)
   - `notice`: Normal but significant events
   - `warning`: Exceptional occurrences that are not errors
   - `error`: Runtime errors that don't require immediate action
   - `critical`: Critical conditions
   - `alert`: Action must be taken immediately
   - `emergency`: System is unusable

2. **Use Specific Channels**: Log related events to specific channels for easier filtering
   ```php
   Log::channel('mpesa')->info('Payment processed');
   Log::channel('pipeline')->info('Booking created');
   ```

3. **Include Context**: Always include relevant data
   ```php
   Log::info('User action', [
       'user_id' => auth()->id(),
       'action' => 'profile_update',
       'ip' => request()->ip()
   ]);
   ```

4. **Don't Log in Loops**: Avoid logging inside loops; aggregate data first
   ```php
   // Bad
   foreach ($items as $item) {
       Log::info('Processing item', ['item' => $item]);
   }
   
   // Good
   Log::info('Processing batch', [
       'count' => count($items),
       'items' => $items->pluck('id')
   ]);
   ```

5. **Use Try-Catch with Logging**:
   ```php
   try {
       // Risky operation
   } catch (\Exception $e) {
       Log::error('Operation failed', [
           'error' => $e->getMessage(),
           'file' => $e->getFile(),
           'line' => $e->getLine(),
           'trace' => $e->getTraceAsString()
       ]);
       
       throw $e;
   }
   ```

## 🚀 Example Usage in Your Application

### In Livewire Components
```php
use App\Traits\Loggable;
use Livewire\Component;

class ClientBooking extends Component
{
    use Loggable;
    
    public function submitBooking()
    {
        $this->logInfo('Booking submission started', [
            'client_email' => $this->email
        ]);
        
        try {
            // Create booking
            $pipeline = Pipeline::create($this->data);
            
            $this->logPipelineAction('booking_created', $pipeline->id, [
                'client_name' => $this->name,
                'package' => $this->package
            ]);
            
            return redirect()->route('success');
        } catch (\Exception $e) {
            $this->logError('Booking creation failed', [
                'error' => $e->getMessage(),
                'client_email' => $this->email
            ]);
            
            throw $e;
        }
    }
}
```

### In Controllers
```php
use App\Traits\Loggable;

class PaymentController extends Controller
{
    use Loggable;
    
    public function stkPush()
    {
        $this->logMpesaTransaction('stk_push_initiated', [
            'amount' => request('amount'),
            'phone' => request('phone')
        ]);
        
        // Process payment
    }
}
```

## 🐛 Debugging Tips

1. **Check Telescope** first for request/response details
2. **Use Debugbar** to see SQL queries and performance
3. **View Logs** in the Log Viewer for historical data
4. **Search Activity Log** for user-specific actions
5. **Monitor Channels** separately for different concerns

## 📝 Log File Management

- Logs are automatically rotated daily
- Old logs are kept for the configured number of days:
  - General logs: 14 days
  - M-Pesa logs: 30 days
  - Pipeline logs: 30 days
  - User activity logs: 60 days

To manually clear logs:
```bash
php artisan telescope:clear
php artisan activitylog:clean
rm storage/logs/*.log
```

## 🔄 Configuration Files

- Logging: `config/logging.php`
- Telescope: `config/telescope.php`
- Activity Log: `config/activitylog.php`
- Debugbar: Auto-configured, publish with `php artisan vendor:publish --provider="Barryvdh\Debugbar\ServiceProvider"`

## 📞 Support

For issues or questions about logging:
1. Check the log files first
2. Review Telescope dashboard
3. Consult this guide
4. Check Laravel documentation: https://laravel.com/docs/10.x/logging

---

**Remember**: Good logging is essential for maintaining and debugging your application. Log generously but thoughtfully!

