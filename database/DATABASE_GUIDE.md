# 🗄️ KNOTTED Database - Complete Guide

## 📦 Available Database Files

### 1. **knotted_complete_database.sql** ⭐ RECOMMENDED
**Complete schema + sample data for shopping flow**

- ✅ Full database schema (21 tables)
- ✅ Sample data: 3 users, 8 products, 3 orders
- ✅ Ready for shopping flow testing
- ✅ Payment upload & verification testing
- ✅ Order tracking testing
- 🎯 **Use this for complete e-commerce testing**

**Size:** ~1MB  
**Best for:** Production-ready testing with shopping flow

---

### 2. **knotted_sample_data.sql**
**Sample data only (requires existing schema)**

- ✅ Insert data into existing tables
- ✅ Same sample data as complete database
- ✅ Use after `php artisan migrate`
- 🎯 **Use this if you already ran migrations**

**Size:** ~200KB  
**Best for:** Laravel developers using migrations

---

### 3. **knotted_complete_schema.sql**
**Schema only (headwear-focused, older version)**

- ✅ Complete table structures
- ✅ Headwear products
- ✅ Product variations system
- ⚠️ Missing some shopping flow fields
- 🎯 **Use this for reference only**

**Size:** ~800KB  
**Best for:** Schema reference and comparison

---

## 🚀 Quick Start

### Option A: Complete Fresh Installation (Recommended)
```bash
# Import complete database
psql -U postgres -d knotted < database/knotted_complete_database.sql

# Or via Supabase SQL Editor:
# Copy-paste knotted_complete_database.sql content
```

### Option B: Laravel Migrations + Sample Data
```bash
# Run migrations
php artisan migrate:fresh

# Import sample data only
psql -U postgres -d knotted < database/knotted_sample_data.sql

# Or seed via Laravel
php artisan db:seed
```

---

## 📊 Database Overview

### Core E-Commerce Tables

| Table | Description | Records |
|-------|-------------|---------|
| `users` | Admin & Customer accounts | 3 |
| `categories` | Product categories | 4 |
| `products` | Product catalog | 8 |
| `product_variations` | Color/size variants | 5 |
| `carts` | Shopping carts | - |
| `cart_items` | Cart line items | - |
| `orders` | Customer orders | 3 |
| `order_items` | Order line items | 5 |
| `payments` | Payment records | 2 |
| `addresses` | Shipping addresses | 2 |

### Additional Tables

| Table | Description |
|-------|-------------|
| `product_images` | Product gallery |
| `custom_orders` | Custom order requests |
| `custom_offers` | Price negotiations |
| `custom_order_images` | Custom order references |
| `store_settings` | Store configuration |

### System Tables (Laravel)

| Table | Description |
|-------|-------------|
| `migrations` | Migration tracking |
| `cache` & `cache_locks` | Cache system |
| `jobs` & `job_batches` | Queue system |
| `failed_jobs` | Failed queue jobs |
| `password_reset_tokens` | Password resets |
| `sessions` | User sessions |

---

## 🔑 Login Credentials

### Admin Account
```
Email:    admin@knotted.com
Password: password
Role:     admin
```

### Customer Accounts
```
Email:    customer@test.com
Password: password
Role:     customer

Email:    faqihmuqoddam3007@gmail.com
Password: password
Role:     customer
Phone:    085892992913
```

---

## 🛒 Sample Products

| ID | Product | Category | Price | Stock |
|----|---------|----------|-------|-------|
| 1 | Bunny Amigurumi | Crochet Toys | Rp 95,000 | 10 |
| 2 | Bear Plushie | Crochet Toys | Rp 125,000 | 8 |
| 3 | Crochet Tote Bag | Accessories | Rp 150,000 | 15 |
| 4 | Mini Pouch | Accessories | Rp 45,000 | 20 |
| 5 | Crochet Beanie | Apparel | Rp 85,000 | 12 |
| 6 | Infinity Scarf | Apparel | Rp 135,000 | 10 |
| 7 | Crochet Coaster Set | Home Decor | Rp 55,000 | 25 |
| 8 | Wall Hanging Decor | Home Decor | Rp 175,000 | 5 |

---

## 📦 Sample Orders

### Order 1: ORD-20261106-0001
- **Customer:** customer@test.com
- **Status:** `pending_payment` ⏳
- **Payment:** `pending` (belum upload)
- **Items:**
  - Bunny Amigurumi (White) - Rp 95,000
  - Crochet Beanie (Cream) - Rp 85,000
  - Crochet Coaster Set - Rp 55,000
- **Shipping:** Rp 15,000
- **Total:** Rp 250,000

### Order 2: ORD-20261106-0002
- **Customer:** faqihmuqoddam3007@gmail.com
- **Status:** `processing` 🔄
- **Payment:** `paid` ✅ (verified 1 day ago)
- **Method:** QRIS
- **Items:**
  - Bunny Amigurumi (Pink) - Rp 95,000
- **Total:** Rp 110,000

### Order 3: ORD-20261106-0003
- **Customer:** customer@test.com
- **Status:** `completed` ✅
- **Payment:** `paid` ✅ (verified 6 days ago)
- **Method:** Bank Transfer
- **Items:**
  - Crochet Tote Bag - Rp 150,000
- **Total:** Rp 165,000

---

## 💳 Payment Settings

### Bank Transfer
```
Bank Name:     BCA
Account:       1234567890
Holder:        KNOTTED Store
```

### QRIS
```
Image:         qris-knotted.jpg
Location:      public/images/qris/
```

### Shipping
```
Flat Rate:     Rp 15,000
All Indonesia
```

---

## 📋 Status Definitions

### Order Status
1. `pending_payment` - Menunggu pembayaran
2. `processing` - Sedang diproses (after payment)
3. `shipped` - Dikirim
4. `completed` - Selesai
5. `cancelled` - Dibatalkan

### Payment Status
1. `pending` - Menunggu upload bukti
2. `under_review` - Sedang diverifikasi admin
3. `paid` - Pembayaran diverifikasi ✅
4. `rejected` - Bukti ditolak ❌

---

## 🧪 Testing Scenarios

### Scenario 1: Customer Shopping (Order 1)
```sql
-- Login as customer@test.com
-- Order status: pending_payment
-- Action needed: Upload payment proof

-- Test upload payment:
SELECT * FROM orders WHERE order_number = 'ORD-20261106-0001';
-- Status should be: pending_payment, payment: pending
```

### Scenario 2: Admin Verification (Order 2)
```sql
-- Login as admin@knotted.com
-- Payment status: paid (already verified)

-- View payment:
SELECT * FROM payments WHERE order_id = 2;
-- Status should be: paid, verified_by: 1 (admin)
```

### Scenario 3: Completed Order (Order 3)
```sql
-- Order already completed
SELECT * FROM orders WHERE order_number = 'ORD-20261106-0003';
-- Status: completed, payment_status: paid
```

---

## 🔍 Useful Queries

### Check All Orders
```sql
SELECT 
    order_number,
    customer_name,
    status,
    payment_status,
    total_amount,
    created_at
FROM orders
ORDER BY created_at DESC;
```

### Check Payments Needing Verification
```sql
SELECT 
    p.id,
    o.order_number,
    p.amount,
    p.payment_method,
    p.status,
    p.created_at
FROM payments p
JOIN orders o ON p.order_id = o.id
WHERE p.status = 'under_review'
ORDER BY p.created_at DESC;
```

### Check Product Stock
```sql
SELECT 
    p.name,
    p.sku,
    p.stock,
    c.name as category
FROM products p
JOIN categories c ON p.category_id = c.id
WHERE p.is_active = true
ORDER BY p.stock ASC;
```

### Check User Orders
```sql
SELECT 
    u.name,
    u.email,
    COUNT(o.id) as total_orders,
    SUM(o.total_amount) as total_spent
FROM users u
LEFT JOIN orders o ON u.id = o.user_id
WHERE u.role = 'customer'
GROUP BY u.id, u.name, u.email
ORDER BY total_spent DESC;
```

---

## ⚙️ Environment Setup

### .env Configuration
```env
# Database
DB_CONNECTION=pgsql
DB_HOST=your-supabase-host.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-password

# Supabase Storage (Optional)
SUPABASE_URL=https://your-project.supabase.co
SUPABASE_KEY=your-anon-key
SUPABASE_BUCKET=knotted-storage

# App
APP_NAME=KNOTTED
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

# Mail (for notifications)
MAIL_MAILER=smtp
MAIL_FROM_ADDRESS=faqihmuqoddam3007@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

---

## 🛠️ Maintenance Commands

### Clear All Cache
```bash
php artisan optimize:clear
```

### Check Database Connection
```bash
php artisan db:show
```

### View Migrations Status
```bash
php artisan migrate:status
```

### Reset Database
```bash
php artisan migrate:fresh --seed
```

---

## 📱 Contact Information

**Owner:**
- **Name:** Muhammad Faqih Muqoddam
- **Email:** faqihmuqoddam3007@gmail.com
- **Phone:** 085892992913 (WhatsApp)
- **Instagram:** @faqiiihhhhhh
- **GitHub:** faqihanakbaik

**Store:**
- **Name:** KNOTTED
- **Tagline:** Handcrafted with Love
- **Category:** E-commerce (Crochet & Handmade)

---

## ✅ Verification Checklist

After importing database:

- [ ] Can login as admin@knotted.com
- [ ] Can login as customer@test.com
- [ ] Can view all 8 products
- [ ] Can view all 3 orders
- [ ] Order 1 shows pending payment
- [ ] Order 2 shows processing (paid)
- [ ] Order 3 shows completed
- [ ] Can view 2 verified payments
- [ ] Store settings populated
- [ ] Product variations working

---

## 🆘 Troubleshooting

### Error: Database does not exist
```bash
# Create database first
createdb knotted

# Or in psql:
CREATE DATABASE knotted;
```

### Error: Permission denied
```bash
# Grant permissions
GRANT ALL PRIVILEGES ON DATABASE knotted TO your_username;
```

### Error: Column does not exist
```bash
# Make sure you're using the correct SQL file
# Use knotted_complete_database.sql for fresh install
```

### Error: Unique constraint violation
```bash
# Database already has data
# Drop database and recreate:
DROP DATABASE knotted;
CREATE DATABASE knotted;
# Then import again
```

---

## 📝 File Sizes

| File | Size | Load Time |
|------|------|-----------|
| knotted_complete_database.sql | ~1.2 MB | ~10s |
| knotted_sample_data.sql | ~200 KB | ~2s |
| knotted_complete_schema.sql | ~800 KB | ~7s |

---

## 🎯 Recommended Workflow

### For New Projects:
1. ✅ Use `knotted_complete_database.sql`
2. ✅ Import via Supabase SQL Editor
3. ✅ Configure `.env` file
4. ✅ Test login & shopping flow
5. ✅ Verify payment system

### For Existing Laravel Projects:
1. ✅ Run `php artisan migrate:fresh`
2. ✅ Import `knotted_sample_data.sql`
3. ✅ Or run `php artisan db:seed`
4. ✅ Test application

---

**Last Updated:** November 6, 2026  
**Database Version:** 2.0  
**Laravel Version:** 11.x  
**PostgreSQL Version:** 14+  

🎉 **Happy coding!**
