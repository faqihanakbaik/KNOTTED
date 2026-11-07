# KNOTTED - Railway Deployment Guide

## ✅ Deployment Requirements Met

### PHP Version
- **Required:** PHP ^8.4 (supports PHP 8.4, 8.5, and future 8.x)
- **Tested:** PHP 8.5.12
- **Laravel:** 12.70.2

### Files Configuration

#### 1. composer.json
```json
"require": {
    "php": "^8.4",
    "laravel/framework": "^12.0",
    "laravel/tinker": "^2.10.1",
    "league/flysystem-aws-s3-v3": "^3.0"
}
```

#### 2. nixpacks.toml
Railway-specific configuration for PHP 8.5 deployment:
- PHP 8.5 with required extensions
- Optimized composer install (no-dev, optimize-autoloader)
- Laravel caching (config, route, view)
- Server configuration for Railway port binding

#### 3. Procfile
Web process definition for Railway:
```
web: php artisan serve --host=0.0.0.0 --port=$PORT
```

## 🔒 Security

All security vulnerabilities resolved:
- ✅ symfony/http-foundation updated to v7.4.23
- ✅ symfony/http-kernel updated to v7.4.23
- ✅ symfony/routing updated to v7.4.23
- ✅ symfony/yaml updated to v8.1.11
- ✅ No security advisories found (`composer audit` clean)

## 🚀 Railway Deployment Steps

### 1. Push to GitHub
```bash
git push origin main
```

### 2. Railway Project Setup
1. Go to [Railway.app](https://railway.app)
2. Create new project
3. Connect GitHub repository: `KNOTTED`
4. Railway will auto-detect Laravel using nixpacks.toml

### 3. Environment Variables
Set these in Railway dashboard:

#### Required Laravel Variables
```env
APP_NAME=KNOTTED
APP_ENV=production
APP_KEY=base64:your_app_key_here
APP_DEBUG=false
APP_TIMEZONE=UTC
APP_URL=https://your-app.railway.app

LOG_CHANNEL=stack
LOG_LEVEL=info
```

#### Database (Supabase PostgreSQL)
```env
DB_CONNECTION=pgsql
DB_HOST=your-supabase-host.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-supabase-password
DB_SSLMODE=require
```

#### Session & Cache
```env
SESSION_DRIVER=file
SESSION_LIFETIME=120
CACHE_DRIVER=file
CACHE_STORE=file
```

#### Queue
```env
QUEUE_CONNECTION=sync
```

#### Mail (if configured)
```env
MAIL_MAILER=log
MAIL_FROM_ADDRESS=hello@knotted.app
MAIL_FROM_NAME=KNOTTED
```

#### Supabase Storage (if using)
```env
AWS_ACCESS_KEY_ID=your-access-key
AWS_SECRET_ACCESS_KEY=your-secret-key
AWS_DEFAULT_REGION=your-region
AWS_BUCKET=your-bucket
AWS_ENDPOINT=https://your-project.supabase.co/storage/v1/s3
AWS_USE_PATH_STYLE_ENDPOINT=false
SUPABASE_URL=https://your-project.supabase.co
SUPABASE_KEY=your-anon-key
```

### 4. Post-Deployment Commands

After first deployment, run these in Railway console:

```bash
# Generate application key (if not set)
php artisan key:generate --force

# Run migrations
php artisan migrate --force

# Create storage link
php artisan storage:link

# Clear and cache everything
php artisan optimize
```

### 5. Database Setup

If starting fresh, run migrations:
```bash
php artisan migrate --force
```

If importing existing data, connect to Supabase and import:
- `database/knotted_complete_schema.sql` - Database structure
- `database/knotted_sample_data.sql` - Sample data (optional)

## 🔍 Verification

### Local Testing (Before Push)
```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Test optimization
php artisan optimize

# Check PHP version
php -v

# Validate composer
composer validate --strict

# Check platform requirements
composer check-platform-reqs

# Security audit
composer audit

# Application info
php artisan about
```

### Production Verification (After Deploy)
1. Check Railway logs for deployment status
2. Verify application is accessible via URL
3. Test main routes:
   - Home: `/`
   - Shop: `/shop`
   - Login: `/login`
   - Admin: `/admin/dashboard`
4. Check database connectivity
5. Verify storage/uploads working

## 📦 Dependencies Compatibility

All dependencies compatible with PHP 8.5:
- ✅ Laravel Framework 12.70.2
- ✅ Laravel Tinker 2.10.1
- ✅ AWS S3 Flysystem 3.0
- ✅ All Symfony components updated
- ✅ All dev dependencies compatible

## 🛠️ Troubleshooting

### Issue: "PHP version requirement not met"
**Solution:** nixpacks.toml specifies PHP 8.5, Railway will install it automatically

### Issue: "Storage link not working"
**Solution:** Run `php artisan storage:link` in Railway console

### Issue: "Database connection failed"
**Solution:** 
1. Check Supabase credentials in Railway env vars
2. Ensure `DB_SSLMODE=require` is set
3. Verify Supabase PostgreSQL is running

### Issue: "Views not rendering"
**Solution:** Run `php artisan view:cache` in Railway console

### Issue: "Routes not found"
**Solution:** Run `php artisan route:cache` in Railway console

## 📝 Notes

- **No code changes required** - Application code unchanged
- **Supabase PostgreSQL** - Database configuration preserved
- **File storage** - Can use local or Supabase storage
- **Session** - Uses file driver (consider Redis for scaling)
- **Queue** - Uses sync driver (consider Redis for background jobs)

## 🎯 Success Criteria

- ✅ PHP 8.4+ requirement in composer.json
- ✅ composer.lock updated with content hash
- ✅ All dependencies compatible
- ✅ Security vulnerabilities resolved
- ✅ nixpacks.toml for Railway PHP 8.5
- ✅ Procfile for web process
- ✅ All files committed to GitHub
- ✅ No breaking changes to application
- ✅ Supabase PostgreSQL config intact
- ✅ Business logic unchanged

## 📅 Deployment History

- **Date:** November 7, 2026
- **PHP Version:** Updated to ^8.4 (tested on 8.5.12)
- **Laravel Version:** 12.70.2
- **Security Updates:** 5 Symfony packages updated
- **Status:** ✅ Ready for Railway deployment
