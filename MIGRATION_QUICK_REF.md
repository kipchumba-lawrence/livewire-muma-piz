# ⚡ Migration Quick Reference Card

## 🔴 **NEVER RUN IN PRODUCTION** (Destructive Commands)

```bash
php artisan migrate:fresh          # Drops ALL tables!
php artisan migrate:refresh        # Rolls back ALL migrations
php artisan migrate:reset          # Rolls back ALL migrations
php artisan db:wipe                # Drops ALL tables
```

## ✅ **SAFE COMMANDS** (Use Anytime)

```bash
php artisan migrate                # Run pending migrations
php artisan migrate:status         # Check what's pending
php artisan make:migration name    # Create new migration
php artisan migrate --pretend       # Preview without running
```

## 🔄 **ROLLBACK** (Destructive - Use Carefully)

```bash
php artisan migrate:rollback              # Undo last batch
php artisan migrate:rollback --step=3     # Undo last 3 migrations
php artisan migrate:reset                 # Undo ALL migrations
```

## 📋 **Common Patterns**

```bash
# Create table migration
php artisan make:migration create_users_table

# Add column migration
php artisan make:migration add_email_to_users_table --table=users

# Modify table migration
php artisan make:migration modify_users_table --table=users
```

## ⚠️ **Before Running Destructive Commands:**

1. ✅ **BACKUP DATABASE FIRST!**
2. ✅ Check environment (`php artisan env`)
3. ✅ Test locally first
4. ✅ Review migration file
5. ✅ Use `--pretend` to preview

## 🎯 **Quick Decision Guide**

| What You Need | Command | Risk |
|---------------|---------|------|
| Run new migrations | `migrate` | ✅ Safe |
| Check status | `migrate:status` | ✅ Safe |
| Undo last migration | `migrate:rollback` | 🔴 Destructive |
| Start fresh (dev only) | `migrate:fresh` | 🔴 Very Destructive |
| Preview changes | `migrate --pretend` | ✅ Safe |

---

**💡 Remember:** When in doubt, backup first!

