# 🗄️ KNOTTED Database Setup

## 📁 File SQL Tersedia

### ⭐ RECOMMENDED: `KNOTTED_COMPLETE_SETUP.sql`

**File lengkap ONE-FILE-TO-RULE-THEM-ALL!** 

```sql
✅ Complete Schema (15 tables)
✅ All Relationships & Foreign Keys
✅ Indexes for Performance
✅ Auto-updating Timestamps
✅ Sample Data (ready to use)
✅ Store Configuration
✅ Can be run multiple times safely
```

**How to Use:**
1. Open Supabase SQL Editor
2. Copy & paste seluruh file `KNOTTED_COMPLETE_SETUP.sql`
3. Click **Run**
4. Done! ✅

---

### 📂 File SQL Lainnya (Optional)

File-file berikut **TIDAK PERLU** jika sudah pakai `KNOTTED_COMPLETE_SETUP.sql`:

- `knotted_complete_schema.sql` - Schema only (no data)
- `knotted_sample_data.sql` - Data only (needs schema first)
- `knotted_complete_database.sql` - Older version
- `fix_column_names.sql` - Column rename utility

---

## 🗃️ Database Structure

### 15 Tables Created:

#### Core Tables
1. **users** - Admin & customer accounts
2. **categories** - Product categories (6 headwear types)
3. **products** - Main product catalog (12 items)
4. **product_variations** - Colors, sizes, stock (41 variations)
5. **product_images** - Product gallery images

#### Shopping & Orders
6. **addresses** - Customer shipping addresses
7. **carts** - Shopping cart (one per user)
8. **cart_items** - Items in cart
9. **orders** - Customer orders with tracking
10. **order_items** - Line items in orders
11. **payments** - Payment tracking & verification

#### Custom Orders
12. **custom_orders** - Custom product requests
13. **custom_offers** - Price negotiation
14. **custom_order_images** - Reference images

#### Configuration
15. **store_settings** - Store configuration

---

## 📊 Sample Data Included

### Users (3)
- **Admin:** admin@knotted.com (password: password)
- **Customer 1:** customer@example.com (password: password)
- **Customer 2:** budi@example.com (password: password)

### Categories (6)
- Bucket Hats
- Beanies
- Berets
- Balaclavas
- Bonnets
- Headbands

### Products (12)
All headwear items with complete descriptions, pricing, and SKUs.

### Product Variations (41)
Color and size combinations with individual stock tracking:
- Classic Crochet Bucket Hat: 5 variations
- Summer Wave Bucket Hat: 4 variations
- Textured Crochet Bucket: 2 variations
- Cozy Ribbed Beanie: 3 variations
- Pom-Pom Beanie: 3 variations
- Slouchy Knit Beanie: 2 variations
- Classic French Beret: 3 variations
- Textured Beret: 2 variations
- Winter Balaclava: 3 variations
- Vintage Crochet Bonnet: 5 variations
- Flower Crochet Headband: 3 variations
- Twisted Knit Headband: 3 variations

### Store Settings (11)
All essential store configurations including payment methods, shipping, etc.

---

## 🔧 Features

### Automatic Timestamps
All tables auto-update `updated_at` column menggunakan triggers.

### Foreign Key Constraints
Full relational integrity dengan CASCADE deletes where appropriate.

### Performance Indexes
Optimized indexes pada frequently queried columns.

### Safe to Re-run
Script includes `DROP TABLE IF EXISTS` so you can run it multiple times.

---

## 🚀 Railway Deployment

### Environment Variables Needed:

```env
DB_CONNECTION=pgsql
DB_HOST=your-supabase-host.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-supabase-password
DB_SSLMODE=require
```

### After Running SQL:

1. Update `.env` file with Supabase credentials
2. Test connection: `php artisan migrate:status`
3. Application ready to use!

---

## 📝 Schema Details

### Key Relationships

```
users
  ├─ addresses (1:n)
  ├─ carts (1:1)
  ├─ orders (1:n)
  └─ custom_orders (1:n)

categories
  └─ products (1:n)

products
  ├─ product_variations (1:n)
  ├─ product_images (1:n)
  ├─ cart_items (1:n)
  └─ order_items (1:n)

carts
  └─ cart_items (1:n)

orders
  ├─ order_items (1:n)
  └─ payments (1:n)

custom_orders
  ├─ custom_offers (1:n)
  └─ custom_order_images (1:n)
```

### Payment Flow

```
1. Customer creates order
   status: 'pending_payment'
   payment_status: 'pending'

2. Customer uploads payment proof
   payment_status: 'under_review'

3. Admin verifies payment
   payment_status: 'verified'
   status: 'processing'

4. Order fulfilled
   status: 'completed'
```

---

## 🛠️ Maintenance

### View All Tables
```sql
SELECT table_name 
FROM information_schema.tables 
WHERE table_schema = 'public' 
ORDER BY table_name;
```

### Check Record Counts
```sql
SELECT 
    'users' as table_name, COUNT(*) as records FROM users
UNION ALL
SELECT 'categories', COUNT(*) FROM categories
UNION ALL
SELECT 'products', COUNT(*) FROM products
UNION ALL
SELECT 'product_variations', COUNT(*) FROM product_variations;
```

### Reset Auto-increment
```sql
-- If needed after truncating tables
ALTER SEQUENCE users_id_seq RESTART WITH 1;
ALTER SEQUENCE products_id_seq RESTART WITH 1;
-- etc...
```

---

## 🐛 Troubleshooting

### Issue: Foreign key constraint violation
**Solution:** Run complete setup file to drop and recreate all tables.

### Issue: Permission denied
**Solution:** Check Supabase user has CREATE TABLE permissions.

### Issue: Relation already exists
**Solution:** Script includes DROP IF EXISTS - safe to re-run.

### Issue: Column doesn't exist
**Solution:** Ensure you're using `KNOTTED_COMPLETE_SETUP.sql` (most recent).

---

## 📖 Documentation

- **Deployment Guide:** `../DEPLOYMENT.md`
- **Deployment Checklist:** `../DEPLOYMENT_CHECKLIST.md`
- **Deployment Summary:** `../DEPLOYMENT_SUMMARY.md`
- **Main README:** `../README.md`

---

## ✅ Verification

After running the SQL file, verify with:

```sql
-- Should return 15
SELECT COUNT(*) FROM information_schema.tables 
WHERE table_schema = 'public';

-- Should return 3 users
SELECT COUNT(*) FROM users;

-- Should return 12 products
SELECT COUNT(*) FROM products;

-- Should return 41 variations
SELECT COUNT(*) FROM product_variations;
```

---

## 🎯 Quick Start

### For New Setup:
```bash
1. Run KNOTTED_COMPLETE_SETUP.sql in Supabase
2. Update .env with database credentials
3. php artisan serve
4. Login: admin@knotted.com / password
```

### For Existing Setup:
```bash
1. Backup your data first!
2. Run KNOTTED_COMPLETE_SETUP.sql (will drop & recreate)
3. Data will be reset to sample data
4. Or use migrations: php artisan migrate:fresh --seed
```

---

**Version:** 2.0 Railway Ready  
**Updated:** November 7, 2026  
**Status:** ✅ Production Ready

---

Built with ❤️ by KNOTTED Team
