-- ============================================
-- KNOTTED E-Commerce Database - Complete SQL
-- Complete schema with sample data
-- Database: PostgreSQL
-- ============================================

-- Drop existing tables if needed
DROP TABLE IF EXISTS cart_items CASCADE;
DROP TABLE IF EXISTS carts CASCADE;
DROP TABLE IF EXISTS order_items CASCADE;
DROP TABLE IF EXISTS payments CASCADE;
DROP TABLE IF EXISTS orders CASCADE;
DROP TABLE IF EXISTS product_images CASCADE;
DROP TABLE IF EXISTS product_variations CASCADE;
DROP TABLE IF EXISTS products CASCADE;
DROP TABLE IF EXISTS categories CASCADE;
DROP TABLE IF EXISTS custom_order_images CASCADE;
DROP TABLE IF EXISTS custom_offers CASCADE;
DROP TABLE IF EXISTS custom_orders CASCADE;
DROP TABLE IF EXISTS addresses CASCADE;
DROP TABLE IF EXISTS store_settings CASCADE;
DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS cache CASCADE;
DROP TABLE IF EXISTS cache_locks CASCADE;
DROP TABLE IF EXISTS jobs CASCADE;
DROP TABLE IF EXISTS job_batches CASCADE;
DROP TABLE IF EXISTS failed_jobs CASCADE;
DROP TABLE IF EXISTS password_reset_tokens CASCADE;
DROP TABLE IF EXISTS sessions CASCADE;
DROP TABLE IF EXISTS migrations CASCADE;

-- ============================================
-- 1. USERS TABLE
-- ============================================
CREATE TABLE users (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'customer' CHECK (role IN ('customer', 'admin')),
    phone VARCHAR(255) NULL,
    address TEXT NULL,
    city VARCHAR(255) NULL,
    postal_code VARCHAR(255) NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_role ON users(role);

-- Sample Users
INSERT INTO users (name, email, password, role, phone, address, city, postal_code, created_at, updated_at) VALUES
('Admin KNOTTED', 'admin@knotted.com', '$2y$12$Bi3kpxEEW86XnkvUCf8MhOft1KMwfUst4.rPIBZYRP/pP2AyzEuKC', 'admin', '081234567890', 'Jl. Handmade No. 1', 'Jakarta', '12345', NOW(), NOW()),
('Customer Test', 'customer@test.com', '$2y$12$Bi3kpxEEW86XnkvUCf8MhOft1KMwfUst4.rPIBZYRP/pP2AyzEuKC', 'customer', '081234567891', 'Jl. Customer No. 2', 'Bandung', '40123', NOW(), NOW()),
('Faqih Muqoddam', 'faqihmuqoddam3007@gmail.com', '$2y$12$Bi3kpxEEW86XnkvUCf8MhOft1KMwfUst4.rPIBZYRP/pP2AyzEuKC', 'customer', '085892992913', 'Jl. Crochet Street', 'Jakarta', '12340', NOW(), NOW());

-- ============================================
-- 2. CATEGORIES TABLE
-- ============================================
CREATE TABLE categories (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NULL,
    image VARCHAR(255) NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

CREATE INDEX idx_categories_slug ON categories(slug);
CREATE INDEX idx_categories_active ON categories(is_active);

-- Sample Categories
INSERT INTO categories (name, slug, description, image, is_active, created_at, updated_at) VALUES
('Crochet Toys', 'crochet-toys', 'Handmade crochet stuffed toys and amigurumi', 'category-toys.jpg', TRUE, NOW(), NOW()),
('Crochet Accessories', 'crochet-accessories', 'Bags, pouches, and fashion accessories', 'category-accessories.jpg', TRUE, NOW(), NOW()),
('Crochet Apparel', 'crochet-apparel', 'Wearable crochet items like hats and scarves', 'category-apparel.jpg', TRUE, NOW(), NOW()),
('Home Decor', 'home-decor', 'Decorative items for your home', 'category-decor.jpg', TRUE, NOW(), NOW());

-- ============================================
-- 3. PRODUCTS TABLE
-- ============================================
CREATE TABLE products (
    id BIGSERIAL PRIMARY KEY,
    category_id BIGINT NOT NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NULL,
    price DECIMAL(10, 2) NOT NULL,
    stock INTEGER NOT NULL DEFAULT 0,
    image VARCHAR(255) NULL,
    is_featured BOOLEAN NOT NULL DEFAULT FALSE,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    sku VARCHAR(255) NULL UNIQUE,
    weight INTEGER NULL,
    dimensions VARCHAR(255) NULL,
    materials TEXT NULL,
    care_instructions TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

CREATE INDEX idx_products_category ON products(category_id);
CREATE INDEX idx_products_slug ON products(slug);
CREATE INDEX idx_products_featured ON products(is_featured);
CREATE INDEX idx_products_active ON products(is_active);

-- Sample Products
INSERT INTO products (category_id, name, slug, description, price, stock, image, is_featured, is_active, sku, weight, materials, care_instructions, created_at, updated_at) VALUES
(1, 'Bunny Amigurumi', 'bunny-amigurumi', 'Adorable handmade crochet bunny with long ears. Perfect as a gift or home decoration. Made with premium cotton yarn and filled with hypoallergenic stuffing. Height approximately 15cm.', 95000, 10, 'bunny-amigurumi.jpg', TRUE, TRUE, 'KNT-CRO-001', 150, 'Premium cotton yarn, Hypoallergenic polyester filling, Safety eyes', 'Hand wash with cold water, air dry', NOW(), NOW()),
(1, 'Bear Plushie', 'bear-plushie', 'Cute crochet bear with soft texture. Made with love and care for children and adults alike. Size: 20cm tall.', 125000, 8, 'bear-plushie.jpg', TRUE, TRUE, 'KNT-CRO-002', 200, 'Acrylic yarn, Soft filling', 'Hand wash, do not bleach', NOW(), NOW()),
(2, 'Crochet Tote Bag', 'crochet-tote-bag', 'Stylish and sturdy crochet tote bag perfect for daily use. Eco-friendly alternative to plastic bags. Size: 35x40cm.', 150000, 15, 'tote-bag.jpg', TRUE, TRUE, 'KNT-BAG-001', 300, '100% cotton yarn', 'Hand wash or gentle machine wash', NOW(), NOW()),
(2, 'Mini Pouch', 'mini-pouch', 'Small crochet pouch for cosmetics or accessories. Compact and cute design with zipper closure.', 45000, 20, 'mini-pouch.jpg', FALSE, TRUE, 'KNT-BAG-002', 50, 'Cotton blend yarn', 'Hand wash recommended', NOW(), NOW()),
(3, 'Crochet Beanie', 'crochet-beanie', 'Warm and comfortable crochet beanie hat. One size fits most. Available in multiple colors.', 85000, 12, 'beanie-hat.jpg', TRUE, TRUE, 'KNT-HAT-001', 100, 'Wool blend yarn', 'Hand wash cold, lay flat to dry', NOW(), NOW()),
(3, 'Infinity Scarf', 'infinity-scarf', 'Cozy crochet infinity scarf perfect for winter. Soft texture and elegant design.', 135000, 10, 'infinity-scarf.jpg', FALSE, TRUE, 'KNT-SCF-001', 180, 'Soft acrylic yarn', 'Hand wash, air dry', NOW(), NOW()),
(4, 'Crochet Coaster Set', 'crochet-coaster-set', 'Set of 4 decorative crochet coasters. Protect your furniture with style. Diameter: 10cm each.', 55000, 25, 'coaster-set.jpg', FALSE, TRUE, 'KNT-DEC-001', 80, '100% cotton yarn', 'Machine washable', NOW(), NOW()),
(4, 'Wall Hanging Decor', 'wall-hanging-decor', 'Beautiful macrame-style crochet wall hanging. Boho aesthetic for modern homes. Size: 30x50cm.', 175000, 5, 'wall-hanging.jpg', TRUE, TRUE, 'KNT-DEC-002', 250, 'Cotton rope, wooden dowel', 'Dust with soft cloth', NOW(), NOW());

-- ============================================
-- 4. PRODUCT VARIATIONS TABLE
-- ============================================
CREATE TABLE product_variations (
    id BIGSERIAL PRIMARY KEY,
    product_id BIGINT NOT NULL,
    name VARCHAR(255) NOT NULL,
    value VARCHAR(255) NOT NULL,
    price_modifier DECIMAL(10, 2) NOT NULL DEFAULT 0,
    stock_quantity INTEGER NOT NULL DEFAULT 0,
    sku VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE INDEX idx_variations_product ON product_variations(product_id);

-- Sample Variations (Colors for some products)
INSERT INTO product_variations (product_id, name, value, price_modifier, stock_quantity, sku, created_at, updated_at) VALUES
(1, 'Color', 'White', 0, 5, 'KNT-CRO-001-WHT', NOW(), NOW()),
(1, 'Color', 'Pink', 0, 5, 'KNT-CRO-001-PNK', NOW(), NOW()),
(5, 'Color', 'Cream', 0, 4, 'KNT-HAT-001-CRM', NOW(), NOW()),
(5, 'Color', 'Brown', 0, 4, 'KNT-HAT-001-BRN', NOW(), NOW()),
(5, 'Color', 'Grey', 0, 4, 'KNT-HAT-001-GRY', NOW(), NOW());

-- ============================================
-- 5. PRODUCT IMAGES TABLE
-- ============================================
CREATE TABLE product_images (
    id BIGSERIAL PRIMARY KEY,
    product_id BIGINT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    is_primary BOOLEAN NOT NULL DEFAULT FALSE,
    sort_order INTEGER NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE INDEX idx_product_images_product ON product_images(product_id);

-- ============================================
-- 6. CARTS TABLE
-- ============================================
CREATE TABLE carts (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NULL,
    session_id VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE INDEX idx_carts_user ON carts(user_id);
CREATE INDEX idx_carts_session ON carts(session_id);

-- ============================================
-- 7. CART ITEMS TABLE
-- ============================================
CREATE TABLE cart_items (
    id BIGSERIAL PRIMARY KEY,
    cart_id BIGINT NOT NULL,
    product_id BIGINT NOT NULL,
    variation_id BIGINT NULL,
    quantity INTEGER NOT NULL DEFAULT 1,
    price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (cart_id) REFERENCES carts(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (variation_id) REFERENCES product_variations(id) ON DELETE SET NULL
);

CREATE INDEX idx_cart_items_cart ON cart_items(cart_id);
CREATE INDEX idx_cart_items_product ON cart_items(product_id);

-- ============================================
-- 8. ORDERS TABLE
-- ============================================
CREATE TABLE orders (
    id BIGSERIAL PRIMARY KEY,
    order_number VARCHAR(255) NOT NULL UNIQUE,
    user_id BIGINT NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'pending_payment' CHECK (status IN ('pending_payment', 'processing', 'shipped', 'completed', 'cancelled')),
    payment_status VARCHAR(50) NOT NULL DEFAULT 'pending' CHECK (payment_status IN ('pending', 'under_review', 'paid', 'rejected')),
    subtotal DECIMAL(10, 2) NOT NULL,
    shipping_cost DECIMAL(10, 2) NOT NULL DEFAULT 0,
    total_amount DECIMAL(10, 2) NOT NULL,
    payment_method VARCHAR(50) NOT NULL CHECK (payment_method IN ('bank_transfer', 'qris')),
    payment_proof_path VARCHAR(255) NULL,
    payment_proof_uploaded_at TIMESTAMP NULL,
    payment_verified_at TIMESTAMP NULL,
    payment_verified_by BIGINT NULL,
    payment_rejection_reason TEXT NULL,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(255) NOT NULL,
    recipient_name VARCHAR(255) NULL,
    recipient_phone VARCHAR(255) NULL,
    shipping_address TEXT NOT NULL,
    city VARCHAR(255) NULL,
    province VARCHAR(255) NULL,
    district VARCHAR(255) NULL,
    postal_code VARCHAR(255) NULL,
    shipping_city VARCHAR(255) NULL,
    shipping_province VARCHAR(255) NULL,
    shipping_postal_code VARCHAR(255) NULL,
    shipping_phone VARCHAR(255) NULL,
    notes TEXT NULL,
    completed_at TIMESTAMP NULL,
    cancelled_at TIMESTAMP NULL,
    cancelled_by BIGINT NULL,
    cancellation_reason TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (payment_verified_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (cancelled_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE INDEX idx_orders_user ON orders(user_id);
CREATE INDEX idx_orders_number ON orders(order_number);
CREATE INDEX idx_orders_status ON orders(status);
CREATE INDEX idx_orders_payment_status ON orders(payment_status);

-- Sample Orders
INSERT INTO orders (order_number, user_id, status, payment_status, subtotal, shipping_cost, total_amount, payment_method, customer_name, customer_email, customer_phone, recipient_name, recipient_phone, shipping_address, city, province, district, postal_code, notes, created_at, updated_at) VALUES
('ORD-20261106-0001', 2, 'pending_payment', 'pending', 220000, 15000, 235000, 'bank_transfer', 'Customer Test', 'customer@test.com', '081234567891', 'Customer Test', '081234567891', 'Jl. Testing No. 123', 'Bandung', 'Jawa Barat', 'Coblong', '40132', 'Please pack carefully', NOW(), NOW()),
('ORD-20261106-0002', 3, 'processing', 'paid', 95000, 15000, 110000, 'qris', 'Faqih Muqoddam', 'faqihmuqoddam3007@gmail.com', '085892992913', 'Faqih Muqoddam', '085892992913', 'Jl. Crochet Street No. 45', 'Jakarta Selatan', 'DKI Jakarta', 'Kebayoran Baru', '12120', NULL, NOW() - INTERVAL '2 days', NOW()),
('ORD-20261106-0003', 2, 'completed', 'paid', 150000, 15000, 165000, 'bank_transfer', 'Customer Test', 'customer@test.com', '081234567891', 'Customer Test', '081234567891', 'Jl. Testing No. 123', 'Bandung', 'Jawa Barat', 'Coblong', '40132', NULL, NOW() - INTERVAL '7 days', NOW());

-- ============================================
-- 9. ORDER ITEMS TABLE
-- ============================================
CREATE TABLE order_items (
    id BIGSERIAL PRIMARY KEY,
    order_id BIGINT NOT NULL,
    product_id BIGINT NOT NULL,
    variation_id BIGINT NULL,
    product_name VARCHAR(255) NOT NULL,
    variation_details TEXT NULL,
    quantity INTEGER NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT,
    FOREIGN KEY (variation_id) REFERENCES product_variations(id) ON DELETE SET NULL
);

CREATE INDEX idx_order_items_order ON order_items(order_id);
CREATE INDEX idx_order_items_product ON order_items(product_id);

-- Sample Order Items
INSERT INTO order_items (order_id, product_id, variation_id, product_name, variation_details, quantity, price, subtotal, created_at, updated_at) VALUES
(1, 1, 1, 'Bunny Amigurumi', 'Color: White', 1, 95000, 95000, NOW(), NOW()),
(1, 5, 3, 'Crochet Beanie', 'Color: Cream', 1, 85000, 85000, NOW(), NOW()),
(1, 7, NULL, 'Crochet Coaster Set', NULL, 1, 55000, 55000, NOW(), NOW()),
(2, 1, 2, 'Bunny Amigurumi', 'Color: Pink', 1, 95000, 95000, NOW() - INTERVAL '2 days', NOW() - INTERVAL '2 days'),
(3, 3, NULL, 'Crochet Tote Bag', NULL, 1, 150000, 150000, NOW() - INTERVAL '7 days', NOW() - INTERVAL '7 days');

-- ============================================
-- 10. PAYMENTS TABLE
-- ============================================
CREATE TABLE payments (
    id BIGSERIAL PRIMARY KEY,
    order_id BIGINT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    payment_date DATE NULL,
    payment_proof VARCHAR(255) NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'pending' CHECK (status IN ('pending', 'under_review', 'paid', 'rejected')),
    notes TEXT NULL,
    admin_notes TEXT NULL,
    verified_at TIMESTAMP NULL,
    verified_by BIGINT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (verified_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE INDEX idx_payments_order ON payments(order_id);
CREATE INDEX idx_payments_status ON payments(status);

-- Sample Payments
INSERT INTO payments (order_id, amount, payment_method, payment_date, payment_proof, status, notes, admin_notes, verified_at, verified_by, created_at, updated_at) VALUES
(2, 110000, 'qris', '2026-11-04', 'payment_proof_order_2.jpg', 'paid', 'Paid via QRIS', 'Verified - payment received', NOW() - INTERVAL '1 day', 1, NOW() - INTERVAL '2 days', NOW() - INTERVAL '1 day'),
(3, 165000, 'bank_transfer', '2026-10-30', 'payment_proof_order_3.jpg', 'paid', 'Transfer dari BCA', 'Verified - amount correct', NOW() - INTERVAL '6 days', 1, NOW() - INTERVAL '7 days', NOW() - INTERVAL '6 days');

-- ============================================
-- 11. ADDRESSES TABLE
-- ============================================
CREATE TABLE addresses (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL,
    label VARCHAR(255) NOT NULL,
    recipient_name VARCHAR(255) NOT NULL,
    phone VARCHAR(255) NOT NULL,
    address TEXT NOT NULL,
    city VARCHAR(255) NOT NULL,
    province VARCHAR(255) NOT NULL,
    district VARCHAR(255) NULL,
    postal_code VARCHAR(255) NOT NULL,
    is_default BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE INDEX idx_addresses_user ON addresses(user_id);

-- Sample Addresses
INSERT INTO addresses (user_id, label, recipient_name, phone, address, city, province, district, postal_code, is_default, created_at, updated_at) VALUES
(2, 'Home', 'Customer Test', '081234567891', 'Jl. Testing No. 123', 'Bandung', 'Jawa Barat', 'Coblong', '40132', TRUE, NOW(), NOW()),
(3, 'Home', 'Faqih Muqoddam', '085892992913', 'Jl. Crochet Street No. 45', 'Jakarta Selatan', 'DKI Jakarta', 'Kebayoran Baru', '12120', TRUE, NOW(), NOW());

-- ============================================
-- 12. CUSTOM ORDERS TABLE
-- ============================================
CREATE TABLE custom_orders (
    id BIGSERIAL PRIMARY KEY,
    request_number VARCHAR(255) NOT NULL UNIQUE,
    user_id BIGINT NOT NULL,
    order_id BIGINT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    reference_image VARCHAR(255) NULL,
    budget_min DECIMAL(10, 2) NULL,
    budget_max DECIMAL(10, 2) NULL,
    deadline DATE NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'pending' CHECK (status IN ('pending', 'quoted', 'negotiating', 'approved', 'rejected', 'completed', 'cancelled')),
    admin_notes TEXT NULL,
    rejection_reason TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL
);

CREATE INDEX idx_custom_orders_user ON custom_orders(user_id);
CREATE INDEX idx_custom_orders_status ON custom_orders(status);
CREATE INDEX idx_custom_orders_request_number ON custom_orders(request_number);

-- ============================================
-- 13. CUSTOM OFFERS TABLE
-- ============================================
CREATE TABLE custom_offers (
    id BIGSERIAL PRIMARY KEY,
    custom_order_id BIGINT NOT NULL,
    offered_by BIGINT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    estimated_days INTEGER NOT NULL,
    notes TEXT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'pending' CHECK (status IN ('pending', 'accepted', 'rejected', 'counter')),
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (custom_order_id) REFERENCES custom_orders(id) ON DELETE CASCADE,
    FOREIGN KEY (offered_by) REFERENCES users(id) ON DELETE CASCADE
);

CREATE INDEX idx_custom_offers_custom_order ON custom_offers(custom_order_id);

-- ============================================
-- 14. CUSTOM ORDER IMAGES TABLE
-- ============================================
CREATE TABLE custom_order_images (
    id BIGSERIAL PRIMARY KEY,
    custom_order_id BIGINT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (custom_order_id) REFERENCES custom_orders(id) ON DELETE CASCADE
);

CREATE INDEX idx_custom_order_images_custom_order ON custom_order_images(custom_order_id);

-- ============================================
-- 15. STORE SETTINGS TABLE
-- ============================================
CREATE TABLE store_settings (
    id BIGSERIAL PRIMARY KEY,
    key VARCHAR(255) NOT NULL UNIQUE,
    value TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Sample Store Settings
INSERT INTO store_settings (key, value, created_at, updated_at) VALUES
('store_name', 'KNOTTED', NOW(), NOW()),
('store_email', 'faqihmuqoddam3007@gmail.com', NOW(), NOW()),
('store_phone', '085892992913', NOW(), NOW()),
('store_address', 'Jl. Handmade Crochet', NOW(), NOW()),
('instagram', '@faqiiihhhhhh', NOW(), NOW()),
('github', 'faqihanakbaik', NOW(), NOW()),
('bank_name', 'BCA', NOW(), NOW()),
('bank_account', '1234567890', NOW(), NOW()),
('bank_holder', 'KNOTTED Store', NOW(), NOW()),
('qris_image', 'qris-knotted.jpg', NOW(), NOW()),
('shipping_cost', '15000', NOW(), NOW());

-- ============================================
-- 16. LARAVEL SYSTEM TABLES
-- ============================================

-- Cache Table
CREATE TABLE cache (
    key VARCHAR(255) PRIMARY KEY,
    value TEXT NOT NULL,
    expiration INTEGER NOT NULL
);

CREATE TABLE cache_locks (
    key VARCHAR(255) PRIMARY KEY,
    owner VARCHAR(255) NOT NULL,
    expiration INTEGER NOT NULL
);

-- Jobs Table
CREATE TABLE jobs (
    id BIGSERIAL PRIMARY KEY,
    queue VARCHAR(255) NOT NULL,
    payload TEXT NOT NULL,
    attempts SMALLINT NOT NULL,
    reserved_at INTEGER NULL,
    available_at INTEGER NOT NULL,
    created_at INTEGER NOT NULL
);

CREATE INDEX idx_jobs_queue ON jobs(queue);

CREATE TABLE job_batches (
    id VARCHAR(255) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    total_jobs INTEGER NOT NULL,
    pending_jobs INTEGER NOT NULL,
    failed_jobs INTEGER NOT NULL,
    failed_job_ids TEXT NOT NULL,
    options TEXT NULL,
    cancelled_at INTEGER NULL,
    created_at INTEGER NOT NULL,
    finished_at INTEGER NULL
);

CREATE TABLE failed_jobs (
    id BIGSERIAL PRIMARY KEY,
    uuid VARCHAR(255) NOT NULL UNIQUE,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload TEXT NOT NULL,
    exception TEXT NOT NULL,
    failed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Password Reset
CREATE TABLE password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL
);

-- Sessions
CREATE TABLE sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload TEXT NOT NULL,
    last_activity INTEGER NOT NULL
);

CREATE INDEX idx_sessions_user ON sessions(user_id);
CREATE INDEX idx_sessions_last_activity ON sessions(last_activity);

-- Migrations (Laravel tracking)
CREATE TABLE migrations (
    id SERIAL PRIMARY KEY,
    migration VARCHAR(255) NOT NULL,
    batch INTEGER NOT NULL
);

-- ============================================
-- SUMMARY & CREDENTIALS
-- ============================================

-- Admin Login:
-- Email: admin@knotted.com
-- Password: password

-- Customer Login:
-- Email: customer@test.com
-- Password: password

-- Faqih Account:
-- Email: faqihmuqoddam3007@gmail.com  
-- Password: password

-- Contact Info:
-- Email: faqihmuqoddam3007@gmail.com
-- Phone/WA: 085892992913
-- Instagram: @faqiiihhhhhh
-- GitHub: faqihanakbaik

-- Payment Info:
-- Bank: BCA
-- Account: 1234567890
-- Holder: KNOTTED Store
-- Shipping: Rp 15,000 (flat rate)

-- Order Statuses:
-- 1. pending_payment - Menunggu pembayaran
-- 2. processing - Sedang diproses (after payment verified)
-- 3. shipped - Dikirim
-- 4. completed - Selesai
-- 5. cancelled - Dibatalkan

-- Payment Statuses:
-- 1. pending - Menunggu upload bukti
-- 2. under_review - Sedang diverifikasi admin
-- 3. paid - Pembayaran diverifikasi
-- 4. rejected - Bukti ditolak

-- ============================================
-- END OF SQL DUMP
-- ============================================
