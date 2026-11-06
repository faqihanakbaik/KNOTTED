# 🔧 Quick Fix - Column Name Issue

## Problem
Error: `column "stock_quantity" does not exist`

## Solution
Kolom di database masih bernama `stock`, tapi kode Laravel cari `stock_quantity`.

---

## ✅ Fix Step-by-Step:

### **Step 1: Login ke Supabase Dashboard**
1. Buka https://supabase.com
2. Login
3. Pilih project KNOTTED

### **Step 2: Buka SQL Editor**
1. Klik tab **SQL Editor** di sidebar kiri
2. Klik **New Query**

### **Step 3: Run Query Ini:**

Copy-paste query ini dan klik **RUN**:

```sql
-- Fix column name
ALTER TABLE product_variations 
RENAME COLUMN stock TO stock_quantity;

-- Verify
SELECT column_name 
FROM information_schema.columns 
WHERE table_name = 'product_variations' 
AND column_name = 'stock_quantity';
```

### **Step 4: Verify**
Kalau berhasil, akan muncul:
```
column_name
--------------
stock_quantity
```

---

## 🎯 Alternative: Re-import Database

Atau lebih simple, **re-import database yang sudah diupdate:**

### Via Supabase SQL Editor:

1. **Drop semua table dulu:**
```sql
DROP SCHEMA public CASCADE;
CREATE SCHEMA public;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO public;
```

2. **Import ulang:**
- Copy seluruh isi file `database/knotted_complete_database.sql`
- Paste ke SQL Editor
- Run

**File sudah diupdate!** Sekarang pakai `stock_quantity` yang benar.

---

## ✅ Setelah Fix:

Test dengan refresh halaman Laravel kamu. Error seharusnya hilang!

**Login & Test:**
```
Email: admin@knotted.com
Password: password
```

Selesai! 🎉
