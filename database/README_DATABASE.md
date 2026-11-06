# KNOTTED Database - Complete Schema & Data

## 📦 File Database

### **knotted_complete_database.sql**
SQL dump lengkap dengan:
- ✅ Semua tabel schema (16 tabel)
- ✅ Sample data untuk testing
- ✅ Indexes untuk performance
- ✅ Foreign keys & constraints
- ✅ 3 user accounts (Admin + 2 Customers)
- ✅ 8 produk dengan kategori
- ✅ 3 sample orders dengan berbagai status
- ✅ Store settings lengkap

---

## 🗃️ Struktur Database

### **Core Tables (E-Commerce)**
1. **users** - User accounts (Admin & Customer)
2. **categories** - Product categories
3. **products** - Product catalog
4. **product_variations** - Product variants (colors, sizes)
5. **product_images** - Product gallery
6. **carts** - Shopping carts
7. **cart_items** - Items in cart
8. **orders** - Customer orders
9. **order_items** - Order line items
10. **payments** - Payment records

### **Additional Tables**
11. **addresses** - Customer addresses
12. **custom_orders** - Custom order requests
13. **custom_offers** - Price quotes for custom orders
14. **custom_order_images** - Custom order reference images
15. **store_settings** - Store configuration

### **System Tables (Laravel)**
16. **migrations** - Laravel migration tracking
17. **cache** & **cache_locks** - Cache system
18. **jobs** & **job_batches** - Queue system
19. **failed_jobs** - Failed queue jobs
20. **password_reset_tokens** - Password resets
21. **sessions** - User sessions

---

## 🔑 Login Credentials

### **Admin Account**
```
Email: admin@knotted.com
Password: password
Role: admin
```

### **Customer Test Account**
```
Email: customer@test.com
Password: password
Role: customer
```

### **Faqih Account (Owner)**
```
Email: faqihmuqoddam3007@gmail.com
Password: password
Role: customer
Phone: 085892992913
```

---

## 📊 Sample Data

### **Categories (4)**
- Crochet Toys
- Crochet Accessories
- Crochet Apparel
- Home Decor

### **Products (8)**
1. Bunny Amigurumi - Rp 95,000
2. Bear Plushie - Rp 125,000
3. Crochet Tote Bag - Rp 150,000
4. Mini Pouch - Rp 45,000
5. Crochet Beanie - Rp 85,000
6. Infinity Scarf - Rp 135,000
7. Crochet Coaster Set - Rp 55,000
8. Wall Hanging Decor - Rp 175,000

### **Orders (3)**

**Order 1: ORD-20261106-0001**
- Status: `pending_payment` (Menunggu pembayaran)
- Payment: `pending` (Belum upload bukti)
- Customer: customer@test.com
- Items: Bunny (White), Beanie (Cream), Coaster Set
- Total: Rp 235,000

**Order 2: ORD-20261106-0002**
- Status: `processing` (Sedang diproses)
- Payment: `paid` (Sudah diverifikasi)
- Customer: faqihmuqoddam3007@gmail.com
- Items: Bunny (Pink)
- Total: Rp 110,000
- Paid 1 day ago via QRIS

**Order 3: ORD-20261106-0003**
- Status: `completed` (Selesai)
- Payment: `paid` (Sudah diverifikasi)
- Customer: customer@test.com
- Items: Crochet Tote Bag
- Total: Rp 165,000
- Completed 7 days ago via Bank Transfer

---

## 💳 Payment Information

### **Bank Transfer**
```
Bank: BCA
Account Number: 1234567890
Account Name: KNOTTED Store
```

### **QRIS**
```
Image: qris-knotted.jpg
(Stored in public/images/qris/)
```

### **Shipping**
```
Flat Rate: Rp 15,000
```

---

## 📋 Order Status Flow

### **Order Status**
1. `pending_payment` → Menunggu pembayaran
2. `processing` → Sedang diproses (after payment verified)
3. `shipped` → Dikirim
4. `completed` → Selesai
5. `cancelled` → Dibatalkan

### **Payment Status**
1. `pending` → Menunggu upload bukti
2. `under_review` → Sedang diverifikasi admin
3. `paid` → Pembayaran diverifikasi
4. `rejected` → Bukti ditolak

---

## 🚀 Cara Import Database

### **Method 1: PostgreSQL Command Line**
```bash
psql -U your_username -d your_database < knotted_complete_database.sql
```

### **Method 2: pgAdmin**
1. Buka pgAdmin
2. Create new database "knotted"
3. Right-click database → Restore
4. Select file: knotted_complete_database.sql
5. Click Restore

### **Method 3: Laravel Artisan (Recommended)**
```bash
# Drop existing tables and re-migrate
php artisan migrate:fresh

# Run seeders
php artisan db:seed

# Or import SQL directly
psql -U postgres -d knotted < database/knotted_complete_database.sql
```

### **Method 4: Supabase Dashboard**
1. Login ke Supabase Dashboard
2. Go to SQL Editor
3. Copy-paste isi file knotted_complete_database.sql
4. Run query

---

## 🔍 Testing Scenarios

### **Scenario 1: Customer Shopping Flow**
1. Login as `customer@test.com`
2. Browse products
3. Add items to cart
4. Checkout dengan informasi lengkap
5. Upload payment proof (Bank Transfer/QRIS)
6. Wait for admin verification

### **Scenario 2: Admin Payment Verification**
1. Login as `admin@knotted.com`
2. Go to Admin → Payments
3. View pending payments
4. Click "Review Payment"
5. View payment proof
6. Approve or Reject with notes

### **Scenario 3: Order Tracking**
1. Login as customer
2. Go to "My Orders"
3. Click order number
4. View order status timeline
5. Check payment status

---

## 📱 Contact Information

**Store Email:** faqihmuqoddam3007@gmail.com  
**Phone/WhatsApp:** 085892992913  
**Instagram:** @faqiiihhhhhh  
**GitHub:** faqihanakbaik

---

## ⚙️ Database Configuration

### **.env Settings**
```env
DB_CONNECTION=pgsql
DB_HOST=your-supabase-host
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-password

# Supabase Storage (Optional)
SUPABASE_URL=your-supabase-url
SUPABASE_KEY=your-supabase-key
SUPABASE_BUCKET=knotted-storage
```

---

## 🛠️ Maintenance Commands

```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Optimize
php artisan optimize

# Check database connection
php artisan db:show

# List all tables
php artisan db:table --show
```

---

## ✅ Verification Checklist

After importing database, verify:

- [ ] 3 users created (1 admin, 2 customers)
- [ ] 4 categories with products
- [ ] 8 products with stock
- [ ] 3 orders with different statuses
- [ ] 2 payments (1 paid, 1 pending)
- [ ] Store settings populated
- [ ] All foreign keys working
- [ ] Login works for all accounts
- [ ] Product images loading (if uploaded)

---

## 🆘 Troubleshooting

### **Error: relation already exists**
```bash
# Drop all tables first
DROP SCHEMA public CASCADE;
CREATE SCHEMA public;
# Then re-import
```

### **Error: permission denied**
```bash
# Grant permissions
GRANT ALL PRIVILEGES ON DATABASE knotted TO your_username;
```

### **Error: column does not exist**
```bash
# Check migration status
php artisan migrate:status
# Run missing migrations
php artisan migrate
```

---

## 📝 Notes

- Password untuk semua user: `password` (hashed dengan bcrypt)
- Semua timestamps menggunakan `NOW()` atau relative time
- SKU generated otomatis dengan format `KNT-XXX-###`
- Order number format: `ORD-YYYYMMDD-####`
- Custom order request format: `REQ-YYYYMMDD-####`

---

**Created:** November 6, 2026  
**Version:** 1.0  
**Database Engine:** PostgreSQL 14+  
**Laravel Version:** 11.x
