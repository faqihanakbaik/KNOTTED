# ✅ Railway Deployment Checklist - KNOTTED

## Pre-Deployment Checklist

### 1. PHP Version Requirements
- [x] Updated composer.json: `"php": "^8.4"`
- [x] Tested on PHP 8.5.12
- [x] composer.lock updated with new content hash
- [x] All dependencies compatible with PHP 8.5

### 2. Security Audit
- [x] Ran `composer audit`
- [x] Updated Symfony packages to fix vulnerabilities:
  - [x] symfony/http-foundation: v7.4.22 → v7.4.23
  - [x] symfony/http-kernel: v7.4.22 → v7.4.23
  - [x] symfony/routing: v7.4.22 → v7.4.23
  - [x] symfony/yaml: v8.1.10 → v8.1.11
  - [x] symfony/event-dispatcher-contracts: v3.7.1 → v3.8.1
- [x] No security advisories remaining

### 3. Railway Configuration Files
- [x] Created `nixpacks.toml` with PHP 8.5 configuration
- [x] Created `Procfile` for web process
- [x] Verified PHP extensions in nixpacks.toml:
  - [x] mbstring, pdo, pdo_pgsql
  - [x] bcmath, gd, zip
  - [x] xml, curl, tokenizer
  - [x] fileinfo, dom, simplexml

### 4. Laravel Configuration
- [x] Verified Laravel 12.70.2 compatibility
- [x] Tested artisan commands:
  - [x] `php artisan about` - ✅ Working
  - [x] `php artisan optimize` - ✅ Working
  - [x] `php artisan config:cache` - ✅ Working
  - [x] `php artisan route:cache` - ✅ Working
  - [x] `php artisan view:cache` - ✅ Working

### 5. Composer Validation
- [x] `composer validate --strict` - ✅ Valid
- [x] `composer check-platform-reqs` - ✅ All requirements met
- [x] `composer install` - ✅ No errors

### 6. Git Commit
- [x] Added all changed files:
  - [x] composer.json
  - [x] composer.lock
  - [x] nixpacks.toml
  - [x] Procfile
- [x] Committed with descriptive message
- [x] Ready to push to GitHub

### 7. Application Integrity
- [x] No changes to application code
- [x] No changes to business logic
- [x] No changes to database configuration
- [x] Supabase PostgreSQL config preserved
- [x] All routes still functional
- [x] All controllers intact

## Deployment Steps

### Step 1: Push to GitHub
```bash
git push origin main
```

### Step 2: Railway Project Setup
1. [ ] Go to Railway.app
2. [ ] Create new project or select existing
3. [ ] Connect GitHub repository
4. [ ] Railway auto-detects Laravel via nixpacks.toml

### Step 3: Environment Variables
Set in Railway Dashboard:

#### Core Laravel
- [ ] `APP_NAME=KNOTTED`
- [ ] `APP_ENV=production`
- [ ] `APP_KEY=base64:...` (generate if needed)
- [ ] `APP_DEBUG=false`
- [ ] `APP_URL=https://your-app.railway.app`

#### Database (Supabase)
- [ ] `DB_CONNECTION=pgsql`
- [ ] `DB_HOST=your-host.supabase.co`
- [ ] `DB_PORT=5432`
- [ ] `DB_DATABASE=postgres`
- [ ] `DB_USERNAME=postgres`
- [ ] `DB_PASSWORD=your-password`
- [ ] `DB_SSLMODE=require`

#### Session & Cache
- [ ] `SESSION_DRIVER=file`
- [ ] `CACHE_DRIVER=file`

#### Queue
- [ ] `QUEUE_CONNECTION=sync`

### Step 4: First Deployment
Wait for Railway to:
- [ ] Detect nixpacks.toml
- [ ] Install PHP 8.5
- [ ] Run `composer install --optimize-autoloader --no-dev`
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Start web process via Procfile

### Step 5: Post-Deployment
Run in Railway console:
```bash
# If APP_KEY not set
php artisan key:generate --force

# Run migrations
php artisan migrate --force

# Create storage link
php artisan storage:link

# Optimize everything
php artisan optimize
```

### Step 6: Verification
- [ ] Check Railway logs - no errors
- [ ] Access application URL - loads correctly
- [ ] Test home page: `/`
- [ ] Test shop page: `/shop`
- [ ] Test login: `/login`
- [ ] Test admin panel: `/admin/dashboard`
- [ ] Verify database connection working
- [ ] Check image uploads/storage

## Post-Deployment Checklist

### Functional Testing
- [ ] User registration works
- [ ] User login works
- [ ] Product listing displays
- [ ] Product details show correctly
- [ ] Add to cart functions
- [ ] Checkout process works
- [ ] Order placement successful
- [ ] Payment upload works
- [ ] Admin panel accessible
- [ ] Admin can manage orders

### Performance Check
- [ ] Page load times acceptable
- [ ] Images load properly
- [ ] No console errors
- [ ] Database queries optimized

### Security Check
- [ ] `APP_DEBUG=false` in production
- [ ] HTTPS enforced
- [ ] Database credentials secure
- [ ] No sensitive data in logs

## Rollback Plan

If deployment fails:

### Option 1: Rollback Commit
```bash
git revert 0b0b0e3
git push origin main
```

### Option 2: Previous Commit
```bash
git reset --hard HEAD~1
git push --force origin main
```

### Option 3: Manual Revert
1. Edit composer.json: change `"php": "^8.4"` back to `"php": "^8.2"`
2. Run `composer update --lock`
3. Delete nixpacks.toml and Procfile
4. Commit and push

## Support & Troubleshooting

### Railway Logs
```bash
# View logs in Railway dashboard
# Or use Railway CLI
railway logs
```

### Common Issues

**Issue: Build fails at composer install**
- Check PHP version in nixpacks.toml
- Verify composer.json and composer.lock are in sync

**Issue: Application shows 500 error**
- Check `APP_KEY` is set
- Verify database credentials
- Check Railway logs for errors

**Issue: Database connection fails**
- Verify Supabase credentials
- Ensure `DB_SSLMODE=require`
- Check Supabase PostgreSQL is running

**Issue: Storage/images not working**
- Run `php artisan storage:link` in Railway console
- Check file permissions
- Verify Supabase storage config if using

## Success Criteria

- ✅ Application deploys successfully on Railway
- ✅ PHP 8.5 detected and used
- ✅ All dependencies installed without errors
- ✅ Laravel caches built successfully
- ✅ Application accessible via Railway URL
- ✅ Database connection established
- ✅ All core features functional
- ✅ No security vulnerabilities
- ✅ Performance acceptable

## Contact & Documentation

- **Deployment Guide:** DEPLOYMENT.md
- **Application:** KNOTTED E-commerce
- **Framework:** Laravel 12.70.2
- **PHP Version:** 8.5.12
- **Database:** Supabase PostgreSQL
- **Platform:** Railway.app

---

**Status:** ✅ READY FOR DEPLOYMENT
**Date:** November 7, 2026
**Prepared by:** Kiro AI
