# KNOTTED - Alur Sistem E-Commerce

## 📋 Daftar Isi
1. [Alur Sistem Lengkap (Customer)](#alur-sistem-lengkap-customer)
2. [Alur Sistem Admin](#alur-sistem-admin)
3. [Fitur Keamanan](#fitur-keamanan)

---

## 🛒 Alur Sistem Lengkap (Customer)

### 1. Membuka Website Pertama Kali

User mengakses website KNOTTED melalui browser dengan mengetik URL atau klik link. Website akan menampilkan **halaman utama (Homepage)** yang berisi:

- **Hero Section**: Banner utama dengan foto produk dan tulisan "Made by hand, tied with love"
- **Featured Products**: Produk-produk pilihan yang ditampilkan horizontal scroll di mobile, dan grid 4 kolom di desktop
- **Trust Badges**: Icon kualitas premium, pengiriman aman, pembayaran secure
- **Categories**: Kategori produk (Amigurumi, Flowers, Keychains, Bags)
- **Navigation Menu**: Home | Shop | About | Contact | FAQ
- **Button**: Login dan Register di navbar

### 2. Browsing Produk (Sebagai Guest/Pengunjung)

Pada tahap ini, user **belum login** dan hanya bisa melihat-lihat produk tanpa bisa membeli. Yang bisa dilakukan:

**✅ Yang Bisa Dilakukan:**
- Melihat semua produk di halaman Shop
- Klik produk untuk melihat detail lengkap (foto, harga, deskripsi, variations)
- Menggunakan fitur search untuk mencari produk tertentu
- Filter produk berdasarkan kategori
- Sort produk berdasarkan harga, nama, atau tanggal
- Membaca halaman About, Contact, FAQ

**❌ Yang Tidak Bisa Dilakukan:**
- Menambahkan produk ke cart (harus login dulu)
- Melakukan checkout (harus login dulu)
- Melihat dashboard pribadi (harus login dulu)

Ketika user mencoba klik tombol **"Add to Cart"**, sistem akan menampilkan popup atau redirect ke halaman login dengan pesan: **"Please login to continue shopping"**

### 3. Registrasi Account Baru

User yang belum memiliki account harus registrasi terlebih dahulu. Klik tombol **"Register"** di navbar, maka akan diarahkan ke halaman `/register` dengan form:

**Form Registrasi:**
- Nama Lengkap (required)
- Email (required, harus unik)
- Password (required, minimal 8 karakter)
- Konfirmasi Password (required, harus sama dengan password)
- No. Handphone (opsional)

**Proses Registrasi:**
1. User mengisi semua field yang required
2. Klik tombol "Register"
3. Sistem melakukan validasi:
   - Email harus unik (belum terdaftar)
   - Password harus minimal 8 karakter
   - Password dan konfirmasi harus sama
4. Jika validasi berhasil:
   - Sistem membuat account baru di database
   - Role: **customer** (is_admin: false)
   - Password di-hash dengan bcrypt untuk keamanan
5. **Auto login** setelah registrasi berhasil
6. Redirect ke halaman utama dengan pesan "Welcome!"

### 4. Login ke Sistem

User yang sudah memiliki account bisa langsung login. Klik tombol **"Login"** di navbar, maka akan diarahkan ke halaman `/login` dengan form:

**Form Login:**
- Email
- Password
- Remember Me (checkbox opsional untuk tetap login)

**Proses Login dan Validasi Penting:**
1. User memasukkan email dan password
2. Klik tombol "Login"
3. Sistem melakukan autentikasi ke database
4. **Validasi Kredensial:**
   - Cek apakah email terdaftar di database
   - Cek apakah password match (compare hash)
   - Jika salah: tampilkan error "Email atau password salah"
   - Jika benar: lanjut ke langkah 5

5. **Validasi Role User (PENTING!):**
   
   Sistem akan cek kolom `is_admin` di tabel `users`:
   
   **Jika is_admin = TRUE (1):**
   ```
   ├─ User adalah ADMIN
   ├─ Aktifkan AdminTabSecurity middleware
   ├─ Generate unique tab token (UUID)
   ├─ Store token di sessionStorage (khusus tab ini)
   ├─ Redirect ke: /admin/dashboard
   └─ Pesan: "Welcome back, Admin!"
   ```
   
   **Jika is_admin = FALSE (0):**
   ```
   ├─ User adalah CUSTOMER
   ├─ Aktifkan CustomerTabAuth system
   ├─ Generate session token
   ├─ Store token di localStorage (shared antar tab)
   ├─ Redirect ke: Halaman utama (/) atau halaman sebelumnya
   └─ Pesan: "Welcome back!"
   ```

6. Session dibuat dan user resmi login

**Perbedaan Redirect:**
- **Admin** → Langsung ke `/admin/dashboard` untuk manage produk, orders, dll
- **Customer** → Kembali ke halaman utama atau halaman yang dituju sebelumnya (intended URL)

### 5. Lupa Password (Forgot Password)

Jika user lupa password, tersedia fitur reset password:

**Langkah-langkah:**
1. Di halaman login, klik link "Forgot Password?"
2. Redirect ke `/forgot-password`
3. User memasukkan email yang terdaftar
4. Klik "Send Reset Link"
5. Sistem generate token reset unik (expired 60 menit)
6. Token disimpan ke database tabel `password_reset_tokens`
7. **DEMO MODE**: Link reset ditampilkan di halaman (production: kirim via email)
8. User copy link reset: `/reset-password/{token}`
9. Buka link reset, isi password baru dan konfirmasi
10. Submit form
11. Sistem validasi token (cek valid dan belum expired)
12. Update password di database (hash baru)
13. Delete token yang sudah dipakai
14. Redirect ke login dengan pesan "Password berhasil diubah"
15. User login dengan password baru

### 6. Shopping: Menambahkan Produk ke Cart

Setelah login sebagai customer, user sudah bisa berbelanja:

**Browse dan Pilih Produk:**
1. User browse produk di halaman `/shop`
2. Klik produk yang diinginkan → halaman detail `/product/{slug}`
3. Di halaman detail produk, user melihat:
   - Foto produk (gallery dengan zoom)
   - Nama produk dan harga
   - Deskripsi lengkap
   - Variations (jika ada): Size, Color, Material
   - Stock availability
   - Reviews dan rating
   - Tombol "Add to Cart"

**Memilih Variations (Jika Ada):**
1. User pilih variation yang diinginkan:
   - Size: S, M, L, XL
   - Color: Red, Blue, Green, dll
   - Material: Cotton, Wool, dll
2. Sistem update harga jika variation punya harga berbeda
3. Sistem cek stock availability untuk variation tersebut

**Add to Cart:**
1. User set quantity (min: 1, max: stock tersedia)
2. Klik tombol "Add to Cart"
3. Sistem kirim AJAX request ke `/cart/add`
4. Backend process:
   - Cek apakah user sudah punya cart (jika belum, create cart baru)
   - Cek apakah item sudah ada di cart (jika sudah, update quantity)
   - Jika belum ada, create CartItem baru
   - Save ke database
5. Frontend update:
   - Cart icon badge di navbar update (tampilkan jumlah item)
   - Tampilkan success notification
   - Optional: Mini cart popup

### 7. Melihat dan Mengelola Cart

User bisa melihat isi cart dengan klik icon cart di navbar:

**Halaman Cart (`/cart`):**

Menampilkan:
- Daftar semua item di cart
- Setiap item menampilkan:
  - Foto produk
  - Nama produk dan variation
  - Harga per unit
  - Quantity (bisa diubah dengan tombol +/-)
  - Subtotal
  - Tombol Remove
- Cart Summary di samping/bawah:
  - Subtotal: Total harga semua item
  - Estimasi Shipping: (akan dihitung di checkout)
  - Tax (11%): Pajak jika ada
  - **Total**: Grand total
- Tombol "Continue Shopping" → kembali ke shop
- Tombol "Proceed to Checkout" → lanjut ke checkout

**Mengelola Cart:**
- **Update Quantity**: Klik tombol +/- → AJAX request ke `/cart/update/{itemId}` → quantity diupdate di database
- **Remove Item**: Klik tombol Remove → AJAX request ke `/cart/remove/{itemId}` → item dihapus dari cart
- **Clear Cart**: Tombol "Clear Cart" → request ke `/cart/clear` → semua item dihapus

### 8. Checkout: Proses Pembelian

Ketika user sudah siap membeli, klik tombol "Proceed to Checkout":

#### Step 1: Shipping Address
1. Redirect ke `/checkout`
2. User pilih atau tambah alamat pengiriman:
   - Pilih dari alamat yang sudah disimpan, ATAU
   - Tambah alamat baru dengan form:
     - Nama penerima (Full Name)
     - No. Handphone
     - Alamat lengkap (Street Address)
     - Kota (City)
     - Provinsi (Province)
     - Kode Pos (Postal Code)
     - Label alamat: Home / Office
3. Validasi form (semua field harus diisi)
4. Klik "Continue" untuk lanjut

#### Step 2: Shipping Method
1. User memilih metode pengiriman:
   - **JNE**: Reguler, YES, OKE
   - **J&T**: Reguler, Express
   - **SiCepat**: Reguler, BEST, HALU
   - **Grab/Gojek**: Same Day Delivery
2. Sistem kalkulasi ongkir berdasarkan:
   - Origin: Alamat toko
   - Destination: Alamat user
   - Weight: Total berat cart items
3. Tampilkan list courier dengan:
   - Nama service
   - Estimasi waktu (2-3 hari, 1 hari, etc.)
   - Harga ongkir
4. User pilih courier yang diinginkan
5. Klik "Continue"

#### Step 3: Payment Method
1. User pilih metode pembayaran:
   
   **Bank Transfer (Manual):**
   - BCA: 1234567890 a.n. KNOTTED Store
   - Mandiri: 0987654321 a.n. KNOTTED Store
   - BNI: 1122334455 a.n. KNOTTED Store
   
   **E-Wallet:**
   - GoPay: 081234567890
   - OVO: 081234567890
   - Dana: 081234567890
   
   **Cash on Delivery (COD):**
   - Bayar saat barang diterima
   
2. Sistem tampilkan instruksi pembayaran
3. Klik "Continue"

#### Step 4: Review Order
1. Halaman review menampilkan ringkasan lengkap:
   
   **Order Items:**
   - List semua produk dengan foto, nama, variation, quantity, subtotal
   
   **Shipping Address:**
   - Nama penerima, phone, alamat lengkap
   
   **Shipping Method:**
   - Courier dan service, estimasi delivery, ongkir
   
   **Payment Method:**
   - Metode yang dipilih dan instruksi
   
   **Order Summary:**
   - Subtotal: Rp XXX.XXX
   - Shipping Cost: Rp XX.XXX
   - Tax (11%): Rp X.XXX
   - **TOTAL: Rp XXX.XXX**
   
2. User review semua detail
3. Klik tombol **"Place Order"**

#### Step 5: Place Order - Backend Process
Ketika user klik "Place Order", sistem melakukan:

**Validasi Penting (Sesuai Flowchart):**
- ✅ Validasi Produk: Cek semua produk masih tersedia dan aktif
- ✅ Validasi Stok: Cek stock masih cukup untuk semua items
- ✅ Validasi Data: Cek alamat lengkap, shipping method dipilih, payment method valid
- ❌ Jika TIDAK VALID: Kembali ke halaman checkout dengan error message
- ✅ Jika VALID: Lanjut proses order

**Jika Validasi Berhasil:**

1. **Create Order Record:**
   - Generate order number unik: **#KNT-YYYY-XXXX** (contoh: #KNT-2026-0001)
   - User ID
   - Total amount
   - Status: "pending_payment"
   - Payment method
   - Shipping method
   - Timestamps

2. **Create Order Items:**
   - Loop semua cart items
   - Create OrderItem untuk setiap item dengan:
     - Product ID, variation ID
     - Quantity
     - Unit price
     - Subtotal

3. **Create Order Address:**
   - Save shipping address ke tabel order_addresses
   - Linked dengan order ID

4. **Update Stock:**
   - Kurangi stock produk/variation sesuai quantity yang dibeli
   - Validasi stock masih cukup

5. **Clear Cart:**
   - Delete semua cart items user
   - Cart kosong kembali

6. **Redirect:**
   - Redirect ke `/customer/orders/{orderId}`
   - Tampilkan order details dan payment instructions
   - **Tombol "Contact Us via WhatsApp"** untuk tanya langsung ke admin
   - Link WhatsApp: `https://wa.me/62XXXXXXXXXXX?text=Halo, saya ingin bertanya tentang pesanan #KNT-2026-0001`

### 9. Upload Payment Proof

Setelah order dibuat, user harus upload bukti pembayaran:

**Halaman Order Detail (`/customer/orders/{orderId}`):**

Menampilkan:
- Order Number: **#KNT-2026-0001**
- Order Date: 01 November 2026, 10:00 WIB
- Status: **Pending Payment**
- Payment Deadline: 24 jam dari order dibuat
- Payment Method: Bank Transfer - BCA

**Payment Instructions:**
- Bank: BCA
- Account Number: 1234567890
- Account Name: KNOTTED Store
- Amount to Transfer: Rp XXX.XXX (exact amount)
- Notes: Include order number in transfer notes

**Upload Payment Proof:**
1. User transfer sesuai amount yang tertera
2. User klik "Upload Payment Proof"
3. Modal popup dengan form upload:
   - Select image file (JPEG/PNG, max 2MB)
   - Bisa crop/preview image
4. Klik "Upload"
5. Sistem process:
   - Validate file (type, size)
   - Upload image ke Supabase Storage
   - Get public URL
   - Update order record:
     - payment_proof: URL image
     - status: "payment_verification"
   - Trigger notification ke admin
6. Success notification: "Payment proof uploaded"
7. Status order berubah: **Payment Verification**

### 10. Menunggu Verifikasi Admin

Setelah upload bukti bayar, order dalam status **"Payment Verification"**. User menunggu admin untuk:
- Cek bukti transfer
- Verify amount dan bank account
- Approve atau reject payment

Timeline order yang terlihat di halaman:
- ✅ Order Placed: 01 Nov 2026, 10:00
- ✅ Payment Uploaded: 01 Nov 2026, 11:30
- 🔄 Payment Verification: In Progress...
- ⏳ Processing: Not Started
- ⏳ Shipped: Not Started
- ⏳ Delivered: Not Started

### 11. Order Diproses dan Dikirim

Setelah admin approve payment:

**Status: Processing**
- Admin sedang prepare items
- Pack produk dengan rapi
- Siapkan shipping label

**Status: Shipped**
- Admin input tracking number
- Order status update: **Shipped**
- User dapat notification:
  - "Your order has been shipped!"
  - Tracking Number: JNE1234567890
  - Estimated Delivery: 3-5 hari
- User bisa track shipment dengan tracking number

**Status: Delivered**
- Kurir deliver barang ke alamat user
- Admin update status: **Delivered**
- User dapat notification: "Your order has been delivered!"

### 12. Complete Order

Setelah barang diterima:

**Opsi 1: User Confirm**
1. User cek order di dashboard
2. Status: Delivered
3. Klik tombol "Mark as Complete"
4. Modal konfirmasi: "Apakah barang sudah diterima dengan baik?"
5. User klik "Yes, Complete Order"
6. Status berubah: **Completed**
7. Optional: User bisa kasih rating & review

**Opsi 2: Auto Complete**
- Jika user tidak confirm dalam 7 hari
- Sistem auto-complete order
- Status berubah: **Completed**

**Order Completed:**
- Transaksi selesai
- User bisa order lagi
- History order tersimpan di dashboard

### 13. Fitur Tambahan Customer

**Custom Order Request:**

User bisa request produk custom yang tidak ada di katalog:

1. **Buat Request Custom Order:**
   - Masuk ke halaman `/customer/custom-orders/create`
   - Isi form request:
     - Tipe Produk (dropdown: Amigurumi, Bag, Keychain, dll)
     - Deskripsi Detail (textarea, jelaskan keinginan)
     - Upload Reference Images (foto contoh, max 5 gambar)
     - Quantity yang diinginkan
     - Preferred Colors/Materials
     - Budget Range (Rp min - max)
     - Deadline (tanggal butuh jadi, opsional)
   - Submit request
   - Status: **Pending Review**

2. **Menunggu Penawaran Admin:**
   - Admin review request
   - Admin hitung estimasi harga dan waktu produksi
   - Admin kirim quotation:
     - Estimated Price: Rp XXX.XXX
     - Production Time: X hari
     - Additional Notes dari admin
   - Status berubah: **Quoted**
   - User dapat notifikasi: "Admin telah mengirim penawaran"

3. **Approve atau Reject Penawaran:**
   - User cek penawaran di `/customer/custom-orders/{id}`
   - Lihat detail quote dari admin
   - **Jika SETUJU:**
     - Klik tombol "Accept Quote"
     - Status → **Approved**
     - Admin create invoice
     - User bayar sesuai invoice
     - Upload bukti bayar
   - **Jika TIDAK SETUJU:**
     - Klik tombol "Reject Quote"
     - Bisa kirim counter-offer atau batal

4. **Proses Produksi:**
   - Setelah payment verified
   - Status → **In Production**
   - Admin mulai buat produk custom
   - Admin bisa kirim progress updates (foto WIP)
   - User bisa track progress

5. **Selesai:**
   - Admin upload foto final product
   - Status → **Completed**
   - Arrange shipment (sama seperti order biasa)
   - User terima barang custom

**Profile Management:**
- View dan edit profile info
- Ganti password (otomatis logout semua session)
- Manage alamat pengiriman
- View order history
- View custom order requests

**Address Management:**
- Add multiple addresses
- Edit existing addresses
- Set default address
- Delete addresses

---

## 👨‍💼 Alur Sistem Admin

### 1. Login Admin dengan Security Khusus

Admin login melalui halaman yang sama `/login`, tetapi dengan kredensial admin (is_admin = true):

**Proses Login Admin:**
1. Input email dan password admin
2. Submit form
3. Sistem validasi kredensial
4. Sistem detect: is_admin = TRUE
5. **AdminTabSecurity Activated:**
   - Generate unique tab token (UUID)
   - Store token di sessionStorage (tidak shared antar tab)
   - Store token di server session
   - Setup heartbeat 30 detik untuk verify token
6. Redirect ke `/admin/dashboard`
7. Pesan: "Welcome back, Admin!"

**Security Admin (Tab-Scoped):**
- Setiap tab admin punya token unik
- Token TIDAK bisa di-share antar tab
- Jika admin copy URL `/admin/dashboard` dan paste di tab baru:
  - Tab baru tidak punya token valid
  - Middleware detect token missing/invalid
  - Redirect ke login: "Please login to access admin panel"
- Admin harus login di setiap tab secara terpisah
- Logout di satu tab tidak affect tab lain (independent sessions)

### 2. Admin Dashboard

Setelah login, admin melihat dashboard dengan:

**Statistics Cards:**
- Total Revenue (bulan ini): Rp 10,000,000
- Total Orders (all time): 245 orders
- Pending Orders (butuh action): 8 orders
- Total Products: 52 products
- Total Customers: 187 customers
- Low Stock Products: 5 products (< 5 stock)

**Charts & Graphs:**
- Revenue Chart: Line chart 12 bulan terakhir
- Orders Chart: Bar chart 30 hari terakhir
- Top Selling Products: Pie chart top 5
- Category Distribution: Donut chart

**Recent Orders Table:**
- 10 order terbaru
- Columns: Order Number, Customer, Amount, Status, Date
- Quick actions: View Detail, Update Status

**Quick Links:**
- Manage Products
- Manage Orders
- Manage Categories
- Manage Customers
- Store Settings
- Reports

### 3. Product Management

**Halaman Product List (`/admin/products`):**

Menampilkan:
- Search bar (cari by name atau SKU)
- Filter: Category, Status (Active/Inactive)
- Sort: Name, Price, Stock, Date
- Tombol "Create New Product"
- Table products dengan columns:
  - Image thumbnail
  - Product Name & SKU
  - Category
  - Price
  - Stock
  - Status badge (Active/Inactive)
  - Actions: Edit, Delete

**Create New Product:**

Klik "Create New Product" → redirect ke `/admin/products/create`

**Step 1: Basic Information**
- Product Name
- Category (dropdown)
- SKU (auto-generate atau manual)
- Product Type:
  - Ready Stock (langsung kirim)
  - Made to Order (butuh lead time)
- Lead Time Days (jika Made to Order)

**Step 2: Pricing & Stock**
- Price (Rupiah)
- Has Variations? (checkbox)
  - Jika NO: Input stock quantity
  - Jika YES: Buat variations dengan form repeater:
    - Variation Name (e.g., "Small - Red")
    - SKU variation
    - Price (bisa override base price)
    - Stock Quantity
    - Is Active checkbox
    - Tombol "Add More Variation"

**Step 3: Images**
- Upload Main Image (required)
  - Max 5MB
  - Format: JPEG, PNG, WEBP
  - Auto resize max 1200x1200px
  - Upload ke Supabase Storage
- Upload Additional Images (max 5)
  - Drag to reorder
  - Delete unwanted images

**Step 4: Description**
- Short Description (max 200 chars)
- Full Description (rich text editor):
  - Bold, Italic, Underline
  - Bullet lists, Numbered lists
  - Hyperlinks
- Care Instructions
- Materials Used

**Step 5: Settings**
- Is Featured? (tampil di homepage)
- Is Active? (visible untuk customer)
- Weight (gram, untuk hitung ongkir)
- Dimensions: Length x Width x Height (cm)
- Meta Tags (untuk SEO)

**Submit & Process:**
1. Admin klik "Create Product"
2. Sistem validasi semua input
3. Generate slug dari product name
4. Upload semua images ke Supabase
5. Create Product record di database
6. Create ProductVariations (jika ada)
7. Clear product cache
8. Log activity
9. Redirect ke product list dengan success message

**Edit Product:**
- Klik "Edit" pada product
- Load data existing ke form
- Bisa update semua field
- Bisa add/edit/delete variations
- Bisa replace/add/remove images
- Submit update

**Delete Product:**
- Klik "Delete"
- Modal konfirmasi: "Are you sure? This will soft delete the product"
- Jika confirm:
  - Soft delete (set deleted_at timestamp)
  - Keep order history (data integrity)
  - Optional: Delete images dari storage
  - Clear cache
- Success message

### 4. Order Management (PALING PENTING!)

**Halaman Order List (`/admin/orders`):**

**Tabs by Status:**
- All Orders
- Pending Payment (butuh reminder)
- Payment Verification (butuh dicek)
- Processing (prepare shipment)
- Shipped (sudah dikirim)
- Delivered
- Completed
- Cancelled

**Filters & Search:**
- Search by order number or customer name
- Filter by date range
- Sort: Date, Amount, Status

**View Order Detail:**

Klik order → `/admin/orders/{id}` menampilkan:

**Order Information:**
- Order Number: **#KNT-2026-0001**
- Order Date: 01 Nov 2026, 10:00 WIB
- Status: Payment Verification
- Payment Method: Bank Transfer (BCA)

**Customer Information:**
- Name
- Email
- Phone
- Customer ID

**Shipping Address:**
- Recipient name & phone
- Full address
- City, Province
- Postal Code

**Order Items Table:**
- Product | Variation | Qty | Price | Subtotal
- Row per item
- Grand Total

**Order Summary:**
- Subtotal
- Shipping Cost
- Tax
- **TOTAL**

**Payment Proof:**
- View uploaded image (full screen modal)
- Uploaded timestamp
- Image dari Supabase Storage

**Order Timeline:**
- Visual timeline dengan checkmarks dan timestamps

**Admin Actions (berdasarkan status):**

**Jika Status = "Pending Payment":**
- Tombol "Send Payment Reminder" (kirim email)
- Tombol "Cancel Order" (dengan reason)
- Wait for customer upload bukti

**Jika Status = "Payment Verification":**
- View payment proof image (full screen)
- Verify details:
  - Amount correct?
  - Bank account correct?
  - Transfer date valid?
- Tombol "Approve Payment":
  - Status → "Processing"
  - Send email notification ke customer
  - Customer dapat notif "Payment verified!"
- Tombol "Reject Payment":
  - Modal: input rejection reason
  - Status → "Pending Payment"
  - Send rejection email dengan reason

**Jika Status = "Processing":**
- Admin prepare items:
  - Print packing slip
  - Print shipping label
  - Pack items dengan bubble wrap
  - Quality check
- Tombol "Mark Ready to Ship"
- Atau langsung "Add Tracking Number"

**Jika Status = "Ready to Ship":**
- Tombol "Ship Order" → Modal:
  - Select Courier (JNE/J&T/SiCepat)
  - Input Tracking Number
  - Estimated Delivery Date
  - Additional Notes
- Submit:
  - Status → "Shipped"
  - Send email dengan tracking info
  - Customer bisa track di dashboard

**Jika Status = "Shipped":**
- Display tracking number
- Tombol "Update Tracking" (jika salah)
- Tombol "Mark as Delivered":
  - Input delivery date & time
  - Received by (nama)
  - Photo proof (optional)
- Submit → Status "Delivered"

**Jika Status = "Delivered":**
- Wait customer confirm (auto complete after 7 days)
- Tombol "Force Complete" (jika perlu)

**Anytime Actions:**
- Tombol "Print Invoice" (generate PDF)
- Tombol "Print Packing Slip"
- Tombol "Send Message to Customer"
- Tombol "Add Order Note" (internal only, tidak terlihat customer)
- Tombol "Cancel Order" (jika belum shipped):
  - Input cancellation reason
  - Refund process (jika sudah bayar)
  - Restore product stock
  - Send cancellation email

### 5. Category Management

**Halaman Category List (`/admin/categories`):**
- Display categories dalam tree structure
- Support subcategories
- Tombol "Create Category"

**Create Category:**
- Modal form:
  - Category Name
  - Slug (auto-generate)
  - Parent Category (optional untuk subcategory)
  - Description
  - Image (optional)
  - Is Active checkbox
- Submit → Category created

**Edit Category:**
- Click edit icon
- Update info di modal
- Submit update

**Delete Category:**
- Click delete icon
- Konfirmasi:
  - If has products: Warning "Move products first"
  - If no products: Proceed delete
  - Cannot delete if has subcategories

### 6. Customer Management

**Halaman Customer List (`/admin/customers`):**
- Search by name/email
- Filter by status
- Sort by registration date, total spent
- Table customers dengan info:
  - Name, Email
  - Registration Date
  - Total Orders
  - Total Spent
  - Status badge
  - Actions: View, Ban, Delete

**View Customer Detail:**
- Profile information
- Statistics:
  - Total Orders
  - Total Spent
  - Average Order Value
  - Favorite Category
- Order History table
- Saved Addresses list

**Admin Actions:**
- Send Email
- Ban Customer (prevent new orders)
- Unban Customer
- Delete Account (GDPR compliance)

### 7. Custom Order Management

**Halaman Custom Orders (`/admin/custom-orders`):**
- List semua custom requests
- Filter by status:
  - Pending Review
  - Quoted
  - Approved
  - In Production
  - Completed
  - Cancelled

**View Custom Request:**
- Customer info
- Request details:
  - Product type
  - Description
  - Reference images
  - Quantity
  - Budget range
  - Deadline

**Admin Actions:**
- **Send Quote:**
  - Input estimated price
  - Production time estimate
  - Additional notes
  - Status → "Quoted"
- **Approve** (after customer agrees):
  - Create invoice
  - Status → "Approved"
- **Start Production:**
  - Status → "In Production"
  - Send progress updates
- **Complete:**
  - Upload final product photos
  - Status → "Completed"
  - Arrange shipment
- **Reject:**
  - Input rejection reason
  - Status → "Cancelled"

### 8. Store Settings

**Halaman Settings (`/admin/settings`):**

**General Settings:**
- Store Name
- Store Email, Phone
- Store Address
- Business Hours
- About Us text

**Shipping Settings:**
- Origin Address (untuk kalkulasi ongkir)
- Available Couriers dengan API Keys:
  - JNE API Key
  - J&T API Key
  - SiCepat API Key
- Free Shipping Threshold (e.g., > Rp 500,000)
- Default Packaging Weight

**Payment Settings:**
- Bank Accounts:
  - BCA: 1234567890 (Active/Inactive)
  - Mandiri: 0987654321
  - BNI: 1122334455
- E-Wallet Numbers:
  - GoPay, OVO, Dana
- Payment Deadline (hours)
- Tax Rate (%)

**Email Settings:**
- SMTP Configuration:
  - Server, Port
  - Username, Password
  - From Email & Name

**Notification Settings:**
- Email Notifications (toggle ON/OFF):
  - Order Placed
  - Payment Verified
  - Order Shipped
  - Order Delivered
- Admin Notifications:
  - New Order (email/SMS)
  - Payment Uploaded
  - Low Stock Alert

**Maintenance Mode:**
- Toggle ON/OFF
- Maintenance Message
- Allowed IP addresses (admin tetap bisa akses)

### 9. Reports & Analytics

**Halaman Reports (`/admin/reports`):**

**Sales Reports:**
- Date range selector
- Total Revenue
- Total Orders
- Average Order Value
- Revenue Chart (daily/weekly/monthly)
- Tombol "Export to Excel"

**Product Reports:**
- Best Selling Products (by quantity)
- Highest Revenue Products (by amount)
- Worst Selling Products
- Low Stock Products
- Out of Stock Products
- Tombol "Export to CSV"

**Customer Reports:**
- New Customers (by period)
- Top Customers (by spending)
- Customer Retention Rate
- Customer Lifetime Value
- Tombol "Export to Excel"

**Financial Reports:**
- Income Statement
- Profit & Loss
- Revenue by Category
- Revenue by Payment Method
- Tombol "Print PDF"

### 10. Admin Profile & Security

**Admin Profile (`/admin/profile`):**
- View admin info
- Update profile
- **Change Password:**
  - Input current password
  - Input new password
  - Confirm new password
  - Submit
  - Backend process:
    - Verify current password
    - Update password (hash baru)
    - **LOGOUT SEMUA ADMIN TABS** (security)
    - Logout current session
    - Invalidate session
    - Regenerate CSRF token
  - Redirect ke login
  - Admin harus login ulang di SEMUA tabs dengan password baru

---

## 🔒 Fitur Keamanan

### 1. Admin Tab-Scoped Security

**Konsep:**
- Setiap tab admin punya token unik (UUID)
- Token disimpan di sessionStorage (tidak shared antar tabs)
- Token diverifikasi di setiap request
- Heartbeat setiap 30 detik

**Implementasi:**
- Middleware: `AdminTabSecurity`
- Frontend JS: `admin-tab-auth.js`
- Storage: sessionStorage (per tab)

**Behavior:**
- Admin buka tab 1 → login → dapat token A → simpan di sessionStorage tab 1
- Admin copy URL admin → paste di tab 2 → tab 2 tidak punya token → redirect ke login
- Admin buka tab 2 → login → dapat token B → simpan di sessionStorage tab 2
- Tab 1 dan Tab 2 independent, masing-masing punya token sendiri
- Logout tab 1 tidak affect tab 2

**Security Benefits:**
- Prevent session hijacking via URL sharing
- Force re-authentication per tab
- Isolate admin sessions
- Detect unauthorized access attempts

### 2. Customer Multi-Tab with Global Logout

**Konsep:**
- Customer bisa buka multiple tabs dengan URL yang sama
- Token disimpan di localStorage (shared antar tabs)
- BroadcastChannel API untuk komunikasi antar tabs
- Logout dari satu tab → logout SEMUA tabs

**Implementasi:**
- Frontend JS: `customer-tab-auth.js`
- Storage: localStorage (shared)
- Communication: BroadcastChannel API

**Behavior:**
- Customer login di tab 1 → token disimpan di localStorage
- Customer buka tab 2 dengan URL yang sama → tab 2 baca token dari localStorage → auto authenticated
- Customer logout di tab 1 → broadcast message "logout" → tab 2 receive message → auto logout
- Semua tabs sync logout secara real-time

**Use Case:**
- Customer bisa share product URL ke tab baru (tetap logged in)
- Customer bisa buka cart di multiple tabs (sync)
- Logout lebih aman (all tabs logout together)

### 3. Password Security

**Hashing:**
- Algorithm: bcrypt
- Cost factor: 10 (default Laravel)
- Salt: auto-generated per password

**Validation:**
- Minimum length: 8 characters
- Recommended: combination of letters, numbers, symbols

**Password Reset:**
- Token expires in 60 minutes
- One-time use token
- Token deleted after successful reset

**Password Change:**
- Verify current password before change
- Auto logout ALL sessions (admin & customer)
- Force re-login with new password

### 4. CSRF Protection

**Laravel Built-in:**
- CSRF token in all POST/PUT/DELETE requests
- Token in hidden form field
- Token in X-CSRF-TOKEN header for AJAX
- Token validation on server side
- Token regeneration on login

### 5. Session Security

**Configuration:**
- Timeout: 120 minutes (configurable)
- Secure cookies: httpOnly, sameSite
- Session driver: database (for persistence)
- Session encryption: enabled

**Session Management:**
- Regenerate session ID on login (prevent fixation)
- Invalidate session on logout
- Clear session data on logout
- Auto cleanup expired sessions

### 6. Input Validation & Sanitization

**Server-Side Validation:**
- Laravel Validator rules
- Required fields, email format, unique checks
- Type validation (string, integer, boolean)
- Length constraints

**Client-Side Validation:**
- HTML5 validation attributes
- JavaScript validation for better UX
- Prevent form submission with errors

**XSS Protection:**
- Auto HTML escaping in Blade templates
- `{{ $var }}` auto-escaped
- `{!! $var !!}` for trusted HTML only

**SQL Injection Protection:**
- Eloquent ORM (parameterized queries)
- Query builder with bindings
- Never concatenate raw SQL with user input

**File Upload Validation:**
- File type whitelist (JPEG, PNG, WEBP)
- Max file size (2MB for payment proof, 5MB for product images)
- Extension validation
- MIME type check
- Store in secure location (Supabase Storage)

### 7. Rate Limiting

**Login Attempts:**
- Max 5 attempts per email per minute
- Lockout for 1 minute after max attempts
- Prevent brute force attacks

**API Endpoints:**
- Rate limit: 60 requests per minute per IP
- Configurable per route group

---

## 📊 Database Schema

**Core Tables:**
- `users` - Customer dan Admin accounts
- `categories` - Product categories (hierarchical)
- `products` - Main product table
- `product_variations` - Size, Color, Material variations
- `carts` - Shopping cart per user
- `cart_items` - Items in cart
- `orders` - Order records
- `order_items` - Products in order
- `order_addresses` - Shipping address per order
- `customer_addresses` - Saved addresses
- `custom_orders` - Custom product requests
- `password_reset_tokens` - Password reset tokens
- `sessions` - User sessions

---

## ⚡ Performance Optimization

### 1. Caching Strategy
- Homepage featured products: 5 minutes cache
- Homepage best sellers: 5 minutes cache
- Categories: 10 minutes cache
- Admin dashboard stats: 2 minutes cache
- Cache cleared on data update

### 2. Database Indexes
- Indexes on frequently queried columns:
  - products.slug, category_id, is_featured, is_active
  - orders.user_id, status, created_at
  - users.email
- Composite indexes for common queries
- Foreign key indexes

### 3. Image Optimization
- Lazy loading on product listings
- Responsive images (different sizes)
- Image compression on upload
- CDN delivery via Supabase Storage
- WebP format support

### 4. Query Optimization
- Eager loading for relationships (prevent N+1)
- Select only needed columns
- Pagination for large datasets
- Chunking for bulk operations

---

## 📱 Mobile Responsiveness

**Design Approach:**
- Mobile-first design
- Breakpoints: Mobile (<768px), Tablet (768-1024px), Desktop (>1024px)

**Mobile Features:**
- Horizontal scroll for products (swipe left/right)
- Touch-friendly buttons (minimum 44x44px)
- Hamburger menu with full-screen overlay
- Large touch targets
- Readable font sizes (16px minimum to prevent auto-zoom on iOS)
- Safe area support for notched devices

**Performance:**
- Minimal JavaScript bundle
- CSS transitions for smooth animations
- Optimized images for mobile bandwidth

---

## 🚀 Deployment

**Stack:**
- Framework: Laravel 12
- Database: PostgreSQL (Supabase)
- Storage: Supabase Storage (images)
- Hosting: Railway (atau platform lain)
- Domain: Custom domain dengan SSL

**Environment Variables Required:**
```
APP_URL=https://knotted.com
DB_CONNECTION=pgsql
DB_HOST=supabase-host
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres.xxx
DB_PASSWORD=xxx
SUPABASE_URL=https://xxx.supabase.co
SUPABASE_KEY=xxx
SUPABASE_BUCKET=knotted-products
```

---

**Last Updated:** November 1, 2026  
**Version:** 1.0.0  
**Project:** KNOTTED E-Commerce System
