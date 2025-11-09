# 🚀 Laravel Migration Commands Cheat Sheet

## ⚠️ **DESTRUCTIVE COMMANDS** (Can Cause Data Loss)

These commands **WILL DELETE DATA** or make irreversible changes:

| Command | Destructive? | What It Does | Risk Level |
|---------|-------------|--------------|------------|
| `php artisan migrate:fresh` | 🔴 **YES** | Drops all tables & re-runs migrations | ⚠️⚠️⚠️ **VERY HIGH** |
| `php artisan migrate:refresh` | 🔴 **YES** | Rolls back all migrations & re-runs them | ⚠️⚠️⚠️ **VERY HIGH** |
| `php artisan migrate:reset` | 🔴 **YES** | Rolls back all migrations | ⚠️⚠️⚠️ **VERY HIGH** |
| `php artisan migrate:rollback` | 🔴 **YES** | Rolls back last batch of migrations | ⚠️⚠️ **HIGH** |
| `php artisan migrate:rollback --step=5` | 🔴 **YES** | Rolls back last 5 migrations | ⚠️⚠️ **HIGH** |
| `php artisan db:wipe` | 🔴 **YES** | Drops all tables (no migrations) | ⚠️⚠️⚠️ **VERY HIGH** |
| `php artisan schema:dump` | 🟡 **Maybe** | Dumps schema (can overwrite files) | ⚠️ **LOW** |

---

## ✅ **SAFE COMMANDS** (No Data Loss)

These commands are **SAFE** to run:

| Command | What It Does | When to Use |
|---------|--------------|-------------|
| `php artisan migrate` | Runs pending migrations | ✅ Normal workflow |
| `php artisan migrate:status` | Shows migration status | ✅ Check what's pending |
| `php artisan migrate:install` | Creates migrations table | ✅ First time setup |
| `php artisan make:migration create_users_table` | Creates new migration file | ✅ Creating new migrations |
| `php artisan make:migration add_email_to_users_table` | Creates migration file | ✅ Adding columns |
| `php artisan make:migration modify_users_table` | Creates migration file | ✅ Modifying structure |

---

## 📋 **Common Migration Commands**

### **Check Status**
```bash
# See which migrations have run
php artisan migrate:status

# See pending migrations
php artisan migrate:status | grep "Pending"
```

### **Run Migrations**
```bash
# Run all pending migrations
php artisan migrate

# Run migrations with output
php artisan migrate --verbose

# Run migrations in production (no confirmation)
php artisan migrate --force

# Run specific migration
php artisan migrate --path=/database/migrations/2025_11_09_023641_harmonize_pipelines_table.php
```

### **Rollback (DESTRUCTIVE!)**
```bash
# Rollback last batch
php artisan migrate:rollback

# Rollback last 3 migrations
php artisan migrate:rollback --step=3

# Rollback all migrations
php artisan migrate:reset

# Rollback and re-run (DESTRUCTIVE!)
php artisan migrate:refresh
```

### **Fresh Start (VERY DESTRUCTIVE!)**
```bash
# Drop all tables and re-run migrations
php artisan migrate:fresh

# Fresh + seed database
php artisan migrate:fresh --seed

# Drop all tables (no migrations)
php artisan db:wipe
```

---

## 🎯 **Migration File Operations**

### **Create Migration**
```bash
# Create table
php artisan make:migration create_users_table

# Add column
php artisan make:migration add_email_to_users_table --table=users

# Modify table
php artisan make:migration modify_users_table --table=users

# Drop table
php artisan make:migration drop_old_table --table=old_table
```

### **Migration File Naming Conventions**
```bash
# ✅ Good naming
create_users_table
add_email_to_users_table
add_timestamps_to_posts_table
modify_pipelines_table
drop_old_table

# ❌ Bad naming
migration1
update_table
fix_stuff
```

---

## 🔧 **Common Migration Patterns**

### **Create Table**
```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamps();
});
```

### **Add Column**
```php
Schema::table('users', function (Blueprint $table) {
    $table->string('phone')->nullable()->after('email');
});
```

### **Modify Column**
```php
// Requires doctrine/dbal package
Schema::table('users', function (Blueprint $table) {
    $table->string('name', 100)->change();
});
```

### **Drop Column**
```php
Schema::table('users', function (Blueprint $table) {
    $table->dropColumn('old_field');
});
```

### **Rename Column**
```php
// Requires doctrine/dbal package
Schema::table('users', function (Blueprint $table) {
    $table->renameColumn('old_name', 'new_name');
});
```

### **Add Index**
```php
Schema::table('users', function (Blueprint $table) {
    $table->index('email');
    $table->unique('phone');
});
```

### **Drop Table**
```php
Schema::dropIfExists('old_table');
```

---

## ⚠️ **Safety Checklist Before Running Destructive Commands**

### **Before `migrate:fresh` or `migrate:refresh`:**
- [ ] ✅ **Backup your database!**
- [ ] ✅ Check you're on the right environment
- [ ] ✅ Verify no one else is using the database
- [ ] ✅ Confirm you have a backup
- [ ] ✅ Test on local/staging first

### **Before `migrate:rollback`:**
- [ ] ✅ Check which migrations will be rolled back
- [ ] ✅ Backup data that will be affected
- [ ] ✅ Verify no critical data will be lost
- [ ] ✅ Test on staging first

### **Before Dropping Columns:**
- [ ] ✅ Check if any code uses that column
- [ ] ✅ Backup data if needed
- [ ] ✅ Remove column from models
- [ ] ✅ Update all queries using that column

---

## 🛡️ **Best Practices**

### **1. Always Backup First**
```bash
# MySQL
mysqldump -u user -p database_name > backup.sql

# PostgreSQL
pg_dump database_name > backup.sql

# SQLite
cp database.sqlite database.sqlite.backup
```

### **2. Test on Local First**
```bash
# Always test migrations locally before production
php artisan migrate

# Check for errors
php artisan migrate:status
```

### **3. Use Transactions (When Possible)**
```php
DB::transaction(function () {
    Schema::table('users', function (Blueprint $table) {
        $table->string('new_field');
    });
});
```

### **4. Check Environment**
```bash
# Always check which environment you're on
php artisan env

# Or check .env file
cat .env | grep APP_ENV
```

### **5. Use --pretend for Testing**
```bash
# See what would happen without running
php artisan migrate --pretend
```

---

## 📊 **Migration Status Reference**

### **Status Meanings:**
- ✅ **Ran** - Migration has been executed
- ⏳ **Pending** - Migration hasn't run yet
- ❌ **Error** - Migration failed

### **Check Status:**
```bash
php artisan migrate:status

# Output example:
# +------+----------------------------------------+-------+
# | Ran? | Migration                              | Batch |
# +------+----------------------------------------+-------+
# | Yes  | 2023_12_29_165902_create_pipelines... | 1     |
# | Yes  | 2024_01_06_202438_add_email_to_...    | 1     |
# | No   | 2025_11_09_023641_harmonize_pipelines |       |
# +------+----------------------------------------+-------+
```

---

## 🔄 **Rollback Strategies**

### **Rollback Last Migration**
```bash
php artisan migrate:rollback
```

### **Rollback Multiple Steps**
```bash
php artisan migrate:rollback --step=3
```

### **Rollback to Specific Batch**
```bash
# First, check batch numbers
php artisan migrate:status

# Then rollback to specific batch
php artisan migrate:rollback --batch=2
```

### **Rollback All**
```bash
php artisan migrate:reset
```

---

## 🚨 **Emergency Recovery**

### **If Migration Fails:**
```bash
# 1. Check the error message
php artisan migrate

# 2. Fix the migration file
# Edit the migration file

# 3. Rollback if needed
php artisan migrate:rollback

# 4. Fix and re-run
php artisan migrate
```

### **If You Accidentally Ran Fresh:**
```bash
# 1. Stop! Don't run any more commands
# 2. Restore from backup immediately
mysql -u user -p database_name < backup.sql

# 3. Check migration status
php artisan migrate:status
```

---

## 📝 **Quick Reference Table**

| Need to... | Command | Destructive? |
|------------|---------|--------------|
| Run new migrations | `php artisan migrate` | ✅ Safe |
| Check what's pending | `php artisan migrate:status` | ✅ Safe |
| Create new migration | `php artisan make:migration name` | ✅ Safe |
| Undo last migration | `php artisan migrate:rollback` | 🔴 Destructive |
| Start completely fresh | `php artisan migrate:fresh` | 🔴 Very Destructive |
| See what would happen | `php artisan migrate --pretend` | ✅ Safe |
| Force in production | `php artisan migrate --force` | ⚠️ Use carefully |

---

## 💡 **Pro Tips**

### **1. Always Use Descriptive Names**
```bash
# ✅ Good
php artisan make:migration add_email_verified_at_to_users_table

# ❌ Bad
php artisan make:migration update1
```

### **2. Test Rollback in Development**
```bash
# Test that rollback works
php artisan migrate:rollback
php artisan migrate
```

### **3. Use Migrations for All Schema Changes**
- ✅ Always use migrations for schema changes
- ❌ Never modify database directly in production
- ✅ Keep migrations in version control

### **4. Review Before Running**
```bash
# Always review migration files before running
cat database/migrations/2025_11_09_023641_harmonize_pipelines_table.php
```

### **5. Use --pretend First**
```bash
# See SQL that would be executed
php artisan migrate --pretend
```

---

## 🎯 **Environment-Specific Commands**

### **Local Development**
```bash
# Safe to experiment
php artisan migrate:fresh --seed
php artisan migrate:refresh
```

### **Staging**
```bash
# Test before production
php artisan migrate
php artisan migrate:status
```

### **Production**
```bash
# Always backup first!
php artisan migrate --force
# Never use migrate:fresh or migrate:refresh in production!
```

---

## 📚 **Additional Resources**

- Laravel Migrations Docs: https://laravel.com/docs/10.x/migrations
- Database: Schema Builder: https://laravel.com/docs/10.x/queries#database-transactions

---

## ⚡ **Quick Decision Tree**

```
Need to run migrations?
│
├─ Is it a NEW migration?
│  └─ ✅ php artisan migrate (SAFE)
│
├─ Need to UNDO changes?
│  ├─ Last migration only?
│  │  └─ 🔴 php artisan migrate:rollback (DESTRUCTIVE)
│  │
│  └─ Multiple migrations?
│     └─ 🔴 php artisan migrate:rollback --step=N (DESTRUCTIVE)
│
├─ Starting fresh in development?
│  └─ 🔴 php artisan migrate:fresh --seed (VERY DESTRUCTIVE)
│
└─ Just checking status?
   └─ ✅ php artisan migrate:status (SAFE)
```

---

## 🎓 **Remember**

1. **Always backup before destructive commands**
2. **Test migrations locally first**
3. **Never use `migrate:fresh` in production**
4. **Review migration files before running**
5. **Use `--pretend` to preview changes**
6. **Keep migrations in version control**
7. **Use descriptive migration names**

---

**Last Updated:** November 9, 2025  
**Laravel Version:** 10.x

