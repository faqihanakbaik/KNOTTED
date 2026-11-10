# Railway Deployment Guide for KNOTTED

## 🚀 Environment Variables Required

Masuk ke **Railway Dashboard** → **Your Project** → **Variables** tab, dan set variable berikut:

### Required Variables:

```bash
# Application
APP_NAME=KNOTTED
APP_ENV=production
APP_DEBUG=false
APP_URL=https://knotted-production.up.railway.app
APP_KEY=base64:QRaftgExc67BqSodEMo8jGOfnOUn7PRRPcc79d9kcF0=

# Locale
APP_LOCALE=id
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=id_ID

# Database (Supabase PostgreSQL)
DB_CONNECTION=pgsql
DB_HOST=aws-1-eu-central-1.pooler.supabase.com
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres.zelmsbvcqevbxcdfkulr
DB_PASSWORD=faqihganteng
DB_SSLMODE=require

# Session Configuration (IMPORTANT!)
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_SECURE_COOKIE=false
SESSION_SAME_SITE=lax
SESSION_HTTP_ONLY=true
SESSION_DOMAIN=null

# Cache
CACHE_STORE=database
QUEUE_CONNECTION=sync

# Filesystem
FILESYSTEM_DISK=supabase

# Supabase
SUPABASE_URL=https://zelmsbvcqevbxcdfkulr.supabase.co
SUPABASE_PROJECT_ID=zelmsbvcqevbxcdfkulr
SUPABASE_ANON_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InplbG1zYnZjcWV2YnhjZm1rdWxyIiwicm9sZSI6ImFub24iLCJpYXQiOjE3MzA0NTk2MTgsImV4cCI6MjA0NjAzNTYxOH0.UaBpNUzBB7gptXjsZbHpwHcWBj5YJk-mnQSHxRp4P2Y
SUPABASE_SERVICE_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InplbG1zYnZjcWV2YnhjZGZrdWxyIiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc5MzUzMDcxOSwiZXhwIjoyMTA5MTA2NzE5fQ.UOz-IB1KPgFVDOWen4rnucJPwZUfxutvw9vIt0o--9U

# Supabase Storage
SUPABASE_STORAGE_KEY=007bbda84b1df4e3a5e346a3c85329eb
SUPABASE_STORAGE_SECRET=93688425896dfbb060b0ca34e4696b31ec3c167159c4691afaba8e08c369e31e
SUPABASE_STORAGE_REGION=eu-central-1
SUPABASE_STORAGE_ENDPOINT=https://zelmsbvcqevbxcdfkulr.storage.supabase.co/storage/v1/s3

# Storage Buckets
SUPABASE_PRODUCT_BUCKET=product-images
SUPABASE_PAYMENT_BUCKET=payment-proofs
SUPABASE_CUSTOM_ORDER_BUCKET=custom-order-references

# Mail (Log driver for now)
MAIL_MAILER=log
MAIL_FROM_ADDRESS=hello@knotted.com
MAIL_FROM_NAME=KNOTTED

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=error
```

## 📋 Deployment Checklist

### 1. Before First Deploy:

- [ ] Push code ke GitHub repository
- [ ] Connect Railway ke GitHub repo
- [ ] Set semua environment variables di Railway dashboard
- [ ] Check Dockerfile exists di root project

### 2. Database Migration (First Time Only):

Setelah deployment pertama sukses, run migration via Railway CLI:

```bash
# Install Railway CLI (jika belum)
npm install -g @railway/cli

# Login ke Railway
railway login

# Link ke project
railway link

# Run migrations
railway run php artisan migrate --force

# Seed database (optional)
railway run php artisan db:seed --force
```

### 3. Check Deployment Status:

- Railway otomatis build dan deploy setiap kali ada push ke GitHub
- Monitor logs di Railway Dashboard → Deployments → Logs
- Check health endpoint: https://knotted-production.up.railway.app/up

### 4. Troubleshooting:

**Error 419 Page Expired:**
- Pastikan `SESSION_SECURE_COOKIE=false` (Railway handle SSL di proxy)
- Pastikan `SESSION_SAME_SITE=lax` (bukan 'strict')
- Pastikan `SESSION_DOMAIN=null` (bukan .up.railway.app)

**Error 500:**
- Check Railway logs: `railway logs`
- Verify APP_KEY sudah di-set
- Verify database credentials benar

**Redirect Loop:**
- TrustProxies middleware sudah handle ini
- Pastikan tidak ada custom HTTPS redirect

**Session tidak persist:**
- Verify `SESSION_DRIVER=database`
- Run migration untuk create sessions table:
  ```bash
  railway run php artisan migrate --force
  ```

## 🔧 Post-Deployment Tasks:

### Create Admin User:

```bash
railway run php artisan tinker

# Di tinker console:
$admin = new App\Models\User();
$admin->name = 'Admin KNOTTED';
$admin->email = 'admin@knotted.com';
$admin->password = bcrypt('admin123');
$admin->is_admin = true;
$admin->save();
exit
```

### Clear Cache:

```bash
railway run php artisan cache:clear
railway run php artisan config:clear
railway run php artisan route:clear
railway run php artisan view:clear
```

### Optimize for Production:

```bash
railway run php artisan config:cache
railway run php artisan route:cache
railway run php artisan view:cache
```

## 🔐 Security Checklist:

- [x] APP_DEBUG=false in production
- [x] APP_ENV=production
- [x] Secure session cookies configuration
- [x] Trust Railway proxies
- [x] CSRF protection enabled
- [x] Database SSL mode required
- [x] Password hashing with bcrypt
- [x] Admin tab-scoped security

## 📊 Monitoring:

- Railway Dashboard: https://railway.app/dashboard
- Application Logs: Railway Dashboard → Logs
- Database: Supabase Dashboard
- Storage: Supabase Storage Dashboard

## 🆘 Support:

Jika ada masalah:
1. Check Railway logs
2. Check Laravel logs di Storage → Logs (via Railway CLI)
3. Verify environment variables
4. Check database connection
5. Test health endpoint: /up

---

**Last Updated:** November 1, 2026
**Project:** KNOTTED E-Commerce
**Platform:** Railway + Supabase
