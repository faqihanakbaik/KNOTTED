# 🚂 Railway Deployment Setup Guide - KNOTTED

## Prerequisites

✅ GitHub repository pushed  
✅ Railway account created  
✅ PostgreSQL extension added to composer.json

---
   
## Step 1: Add PostgreSQL Database to Railway Project

### Option A: Using Railway Template (Recommended)

1. Go to your Railway project dashboard
2. Click **"+ New"** button
3. Select **"Database"** → **"Add PostgreSQL"**
4. Railway will provision a new PostgreSQL database
5. Note: Database credentials akan otomatis tersedia sebagai environment variables

### Option B: Using Supabase (Already Setup)

If you already have Supabase PostgreSQL:
1. Keep your existing Supabase database
2. You'll manually set environment variables (see Step 3)

---

## Step 2: Connect GitHub Repository

1. In Railway project, click **"+ New"** → **"GitHub Repo"**
2. Select your repository: **`KNOTTED`**
3. Railway will auto-detect Laravel and start building

---

## Step 3: Set Environment Variables

### Required Variables (Railway Service Settings)

Go to your service → **Variables** tab and add:

#### Laravel App Configuration
```env
APP_NAME=KNOTTED
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=${{RAILWAY_PUBLIC_DOMAIN}}
```

#### Database Configuration

**If using Railway PostgreSQL (Template):**
```env
DB_CONNECTION=pgsql
DB_URL=${{Postgres.DATABASE_URL}}
```

**If using Supabase:**
```env
DB_CONNECTION=pgsql
DB_HOST=db.your-project-ref.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres.your-project-ref
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

#### Mail (Optional)
```env
MAIL_MAILER=log
MAIL_FROM_ADDRESS=hello@knotted.com
MAIL_FROM_NAME=KNOTTED
```

#### Storage (Optional - Supabase)
```env
AWS_ACCESS_KEY_ID=your-supabase-access-key
AWS_SECRET_ACCESS_KEY=your-supabase-secret-key
AWS_DEFAULT_REGION=ap-southeast-1
AWS_BUCKET=your-bucket-name
AWS_ENDPOINT=https://your-project.supabase.co/storage/v1/s3
AWS_USE_PATH_STYLE_ENDPOINT=false
```

---

## Step 4: Generate APP_KEY

### Method 1: Using Railway Console

1. Go to your service → **Deploy Logs**
2. Wait for build to complete
3. Click **"Open Terminal"** (shell icon)
4. Run:
   ```bash
   php artisan key:generate --show
   ```
5. Copy output (starts with `base64:`)
6. Add to Railway environment variables as `APP_KEY`

### Method 2: Generate Locally

```bash
php artisan key:generate --show
```

Copy the output dan add ke Railway variables.

---

## Step 5: Run Database Migrations

### After First Successful Build:

1. Go to Railway service → **"Deploy Logs"**
2. Wait for deployment to finish
3. Click **"Open Terminal"**
4. Run migrations:
   ```bash
   php artisan migrate --force
   ```

5. (Optional) Seed data:
   ```bash
   php artisan db:seed --force
   ```

### Or Import SQL Directly:

Use Supabase SQL Editor atau Railway Database tab:
- Run `database/KNOTTED_COMPLETE_SETUP.sql`

---

## Step 6: Create Storage Link

In Railway terminal:
```bash
php artisan storage:link
```

---

## Step 7: Optimize for Production

In Railway terminal:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

---

## Verification Checklist

After deployment, verify:

- [ ] ✅ Build completed successfully
- [ ] ✅ PostgreSQL extension installed (check build logs for `pdo_pgsql`)
- [ ] ✅ Database connection successful (no `could not find driver` error)
- [ ] ✅ Migrations ran successfully
- [ ] ✅ Public domain accessible (https://your-app.railway.app)
- [ ] ✅ Homepage loads correctly
- [ ] ✅ Login/Register working
- [ ] ✅ Products displayed
- [ ] ✅ Images showing (if using storage)

---

## Troubleshooting

### Issue 1: "could not find driver"

**Cause:** PostgreSQL extension not installed

**Solution:**
1. Check `composer.json` has:
   ```json
   "ext-pdo": "*",
   "ext-pdo_pgsql": "*",
   "ext-pgsql": "*"
   ```
2. Commit and push changes
3. Railway will rebuild and install extensions

### Issue 2: "Connection refused (Host: 127.0.0.1)"

**Cause:** Database environment variables not set

**Solution:**
1. Set `DB_CONNECTION=pgsql`
2. Set `DB_URL=${{Postgres.DATABASE_URL}}` (if using Railway Postgres)
3. Or set individual DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD

### Issue 3: "Base table or view not found"

**Cause:** Migrations haven't run

**Solution:**
1. Open Railway terminal
2. Run `php artisan migrate --force`

### Issue 4: "No application encryption key"

**Cause:** APP_KEY not set

**Solution:**
1. Generate key: `php artisan key:generate --show`
2. Add to Railway variables as `APP_KEY=base64:...`
3. Redeploy

### Issue 5: Images not showing

**Cause:** Storage link not created

**Solution:**
1. Open Railway terminal
2. Run `php artisan storage:link`
3. Clear cache: `php artisan cache:clear`

---

## Post-Deployment Tasks

### 1. Create Admin Account

Option A: Using Tinker
```bash
php artisan tinker
```
```php
User::create([
    'name' => 'Admin KNOTTED',
    'email' => 'admin@knotted.com',
    'password' => bcrypt('your-secure-password'),
    'role' => 'admin'
]);
```

Option B: Using Seeder (already in SQL file)
- Default admin: `admin@knotted.com` / `password`
- **⚠️ IMPORTANT:** Change password immediately!

### 2. Configure Store Settings

Login as admin → Settings tab:
- Bank account details
- Shipping costs
- Store contact info
- WhatsApp number

### 3. Upload Products

If not using SQL seed:
- Add categories
- Upload products with images
- Set product variations (colors, sizes)

---

## Railway Configuration Files

### composer.json (Updated)
```json
{
  "require": {
    "php": "^8.2",
    "ext-pdo": "*",
    "ext-pdo_pgsql": "*",
    "ext-pgsql": "*",
    "laravel/framework": "^12.0",
    ...
  }
}
```

### nixpacks.toml (Auto-detected)
Railway will use this for PHP 8.4 packages.

### Procfile (Auto-generated)
```
web: php artisan serve --host=0.0.0.0 --port=$PORT
```

---

## Database URL Format

Railway PostgreSQL URL format:
```
postgresql://user:password@host:5432/database
```

Laravel auto-parses this when using `DB_URL`.

---

## Monitoring & Logs

### View Logs:
1. Railway dashboard → Your service
2. Click **"Deploy Logs"** tab
3. Real-time logs displayed

### Check Metrics:
1. Railway dashboard → Your service
2. Click **"Metrics"** tab
3. View CPU, Memory, Network usage

---

## Scaling (Optional)

### Vertical Scaling:
Railway auto-scales resources based on usage.

### Horizontal Scaling:
Edit `railway.json`:
```json
{
  "deploy": {
    "numReplicas": 2
  }
}
```

---

## Backup Strategy

### Database Backup:
1. Railway Postgres has auto-backups
2. Or export via `pg_dump`:
   ```bash
   pg_dump $DATABASE_URL > backup.sql
   ```

### Code Backup:
- Always pushed to GitHub
- Railway keeps deployment history

---

## Custom Domain (Optional)

1. Railway dashboard → Your service
2. **Settings** → **Domains**
3. Click **"Custom Domain"**
4. Add your domain (e.g., `knotted.com`)
5. Update DNS records as instructed
6. Update `APP_URL` environment variable

---

## Performance Optimization

### Enable OPcache:
Railway PHP includes OPcache by default.

### Queue Workers:
For background jobs, add worker service:
```bash
php artisan queue:work --tries=3
```

### Cache Configuration:
Consider using Redis for cache (add Redis database to Railway).

---

## Security Checklist

- [ ] ✅ APP_DEBUG=false in production
- [ ] ✅ APP_KEY set and secure
- [ ] ✅ Database password strong
- [ ] ✅ HTTPS enforced (Railway default)
- [ ] ✅ .env not in repository
- [ ] ✅ Regular backups enabled
- [ ] ✅ Dependencies updated
- [ ] ✅ SQL injection protected (Eloquent ORM)
- [ ] ✅ CSRF protection enabled (Laravel default)

---

## Support

### Railway Support:
- Documentation: https://docs.railway.app
- Discord: https://discord.gg/railway
- Status: https://status.railway.app

### KNOTTED Support:
- Email: faqihmuqoddam3007@gmail.com
- Phone: 085892992913
- GitHub: github.com/faqihanakbaik

---

## Quick Reference Commands

```bash
# Generate app key
php artisan key:generate --show

# Run migrations
php artisan migrate --force

# Seed database
php artisan db:seed --force

# Create storage link
php artisan storage:link

# Optimize for production
php artisan optimize

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# View routes
php artisan route:list

# Open tinker console
php artisan tinker
```

---

**Last Updated:** November 7, 2026  
**Version:** 1.0  
**Status:** ✅ Production Ready

---

Happy Deploying! 🚀🎉
