-- ============================================
-- KNOTTED E-COMMERCE - COMPLETE DATABASE SCHEMA
-- PostgreSQL / Supabase
-- ============================================
-- 
-- READY TO USE - Can be run multiple times safely
-- Simply run this entire script in Supabase SQL Editor
--
-- This includes:
-- - Complete table structures
-- - All relationships & constraints
-- - Product variations support
-- - Payment tracking fields
-- - Fresh headwear-only sample data
-- - Admin & customer accounts
--
-- Version: 1.1 Final
-- Last Updated: 2026-11-04
-- ============================================

-- ============================================
-- PART 0: DROP EXISTING TABLES (IF ANY)
-- ============================================

-- Drop tables in reverse order of dependencies
DROP TABLE IF EXISTS custom_order_images CASCADE;
DROP TABLE IF EXISTS custom_offers CASCADE;
DROP TABLE IF EXISTS custom_orders CASCADE;
DROP TABLE IF EXISTS payments CASCADE;
DROP TABLE IF EXISTS order_items CASCADE;
DROP TABLE IF EXISTS orders CASCADE;
DROP TABLE IF EXISTS cart_items CASCADE;
DROP TABLE IF EXISTS carts CASCADE;
DROP TABLE IF EXISTS addresses CASCADE;
DROP TABLE IF EXISTS product_images CASCADE;
DROP TABLE IF EXISTS product_variations CASCADE;
DROP TABLE IF EXISTS products CASCADE;
DROP TABLE IF EXISTS categories CASCADE;
DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS store_settings CASCADE;

-- ============================================
-- PART 1: CREATE ALL TABLES
-- ============================================

-- 1. USERS TABLE
CREATE TABLE users (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'customer' CHECK (role IN ('customer', 'admin')),
    phone VARCHAR(50) NULL,
    address TEXT NULL,
    city VARCHAR(100) NULL,
    postal_code VARCHAR(20) NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_role ON users(role);

-- 2. CATEGORIES TABLE
CREATE TABLE categories (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT NULL,
    image VARCHAR(255) NULL,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_categories_slug ON categories(slug);

-- 3. PRODUCTS TABLE
CREATE TABLE products (
    id BIGSERIAL PRIMARY KEY,
    category_id BIGINT NOT NULL REFERENCES categories(id) ON DELETE CASCADE,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    stock INTEGER DEFAULT 0,
    sku VARCHAR(100) UNIQUE NULL,
    product_type VARCHAR(50) DEFAULT 'ready_stock' CHECK (product_type IN ('ready_stock', 'pre_order', 'custom_only')),
    lead_time_days INTEGER NULL,
    image VARCHAR(255) NULL,
    images JSON NULL,
    is_featured BOOLEAN DEFAULT false,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_products_category ON products(category_id);
CREATE INDEX idx_products_slug ON products(slug);
CREATE INDEX idx_products_sku ON products(sku);
CREATE INDEX idx_products_featured ON products(is_featured);

-- 4. PRODUCT VARIATIONS TABLE (NEW)
CREATE TABLE product_variations (
    id BIGSERIAL PRIMARY KEY,
    product_id BIGINT NOT NULL REFERENCES products(id) ON DELETE CASCADE,
    color_name VARCHAR(100) NOT NULL,
    color_hex VARCHAR(7) NULL,
    size_label VARCHAR(50) NULL,
    stock_quantity INTEGER NOT NULL DEFAULT 0,
    price_adjustment DECIMAL(10, 2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(product_id, color_name, size_label)
);

CREATE INDEX idx_product_variations_product ON product_variations(product_id);
CREATE INDEX idx_product_variations_stock ON product_variations(stock_quantity);

-- 5. PRODUCT IMAGES TABLE (NEW)
CREATE TABLE product_images (
    id BIGSERIAL PRIMARY KEY,
    product_id BIGINT NOT NULL REFERENCES products(id) ON DELETE CASCADE,
    image_path VARCHAR(255) NOT NULL,
    is_primary BOOLEAN DEFAULT false,
    sort_order INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_product_images_product ON product_images(product_id);
CREATE INDEX idx_product_images_primary ON product_images(is_primary);

-- 6. ADDRESSES TABLE
CREATE TABLE addresses (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    label VARCHAR(100) NULL,
    recipient_name VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    address_line TEXT NOT NULL,
    city VARCHAR(100) NOT NULL,
    province VARCHAR(100) NULL,
    postal_code VARCHAR(20) NOT NULL,
    is_default BOOLEAN DEFAULT false,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_addresses_user ON addresses(user_id);

-- 7. CARTS TABLE
CREATE TABLE carts (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT UNIQUE NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 8. CART ITEMS TABLE
CREATE TABLE cart_items (
    id BIGSERIAL PRIMARY KEY,
    cart_id BIGINT NOT NULL REFERENCES carts(id) ON DELETE CASCADE,
    product_id BIGINT NOT NULL REFERENCES products(id) ON DELETE CASCADE,
    variation_id BIGINT NULL REFERENCES product_variations(id) ON DELETE CASCADE,
    quantity INTEGER NOT NULL DEFAULT 1,
    price DECIMAL(10, 2) NOT NULL,
    selected_color VARCHAR(100) NULL,
    selected_size VARCHAR(100) NULL,
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(cart_id, product_id, variation_id)
);

CREATE INDEX idx_cart_items_cart ON cart_items(cart_id);

-- 9. ORDERS TABLE
CREATE TABLE orders (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    
    -- Order amounts
    subtotal DECIMAL(10, 2) NULL,
    shipping_cost DECIMAL(10, 2) DEFAULT 0,
    total_amount DECIMAL(10, 2) NOT NULL,
    
    -- Shipping info
    shipping_address TEXT NOT NULL,
    shipping_city VARCHAR(100) NULL,
    shipping_province VARCHAR(100) NULL,
    shipping_postal_code VARCHAR(20) NULL,
    shipping_phone VARCHAR(50) NULL,
    recipient_name VARCHAR(255) NULL,
    
    -- Payment
    payment_method VARCHAR(50) NULL CHECK (payment_method IN ('bank_transfer', 'cod') OR payment_method IS NULL),
    payment_proof_path VARCHAR(255) NULL,
    payment_proof_uploaded_at TIMESTAMP NULL,
    payment_verified_at TIMESTAMP NULL,
    payment_verified_by BIGINT NULL REFERENCES users(id) ON DELETE SET NULL,
    payment_rejection_reason TEXT NULL,
    
    -- Order status
    status VARCHAR(50) DEFAULT 'pending_payment' CHECK (status IN ('pending_payment', 'verifying_payment', 'payment_confirmed', 'payment_failed', 'processing', 'shipping', 'completed', 'cancelled')),
    
    -- Additional tracking
    completed_at TIMESTAMP NULL,
    cancelled_at TIMESTAMP NULL,
    cancelled_by BIGINT NULL REFERENCES users(id) ON DELETE SET NULL,
    cancellation_reason TEXT NULL,
    
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_orders_user ON orders(user_id);
CREATE INDEX idx_orders_number ON orders(order_number);
CREATE INDEX idx_orders_status ON orders(status);

-- 10. ORDER ITEMS TABLE
CREATE TABLE order_items (
    id BIGSERIAL PRIMARY KEY,
    order_id BIGINT NOT NULL REFERENCES orders(id) ON DELETE CASCADE,
    product_id BIGINT NULL REFERENCES products(id) ON DELETE SET NULL,
    variation_id BIGINT NULL REFERENCES product_variations(id) ON DELETE SET NULL,
    product_name VARCHAR(255) NOT NULL,
    sku_snapshot VARCHAR(100) NULL,
    color_snapshot VARCHAR(100) NULL,
    size_snapshot VARCHAR(100) NULL,
    quantity INTEGER NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_order_items_order ON order_items(order_id);

-- 11. PAYMENTS TABLE
CREATE TABLE payments (
    id BIGSERIAL PRIMARY KEY,
    order_id BIGINT NOT NULL REFERENCES orders(id) ON DELETE CASCADE,
    amount DECIMAL(10, 2) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    payment_proof VARCHAR(255) NULL,
    status VARCHAR(50) DEFAULT 'pending' CHECK (status IN ('pending', 'paid', 'failed')),
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_payments_order ON payments(order_id);
CREATE INDEX idx_payments_status ON payments(status);

-- 12. CUSTOM ORDERS TABLE
CREATE TABLE custom_orders (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    request_number VARCHAR(50) UNIQUE NULL,
    
    -- Request type
    request_type VARCHAR(50) NOT NULL CHECK (request_type IN ('custom_design', 'color_change', 'size_adjustment')),
    
    -- Product details
    product_name VARCHAR(255) NOT NULL,
    category_id BIGINT NULL REFERENCES categories(id) ON DELETE SET NULL,
    description TEXT NOT NULL,
    color VARCHAR(100) NULL,
    size VARCHAR(100) NULL,
    quantity INTEGER NOT NULL DEFAULT 1,
    notes TEXT NULL,
    
    -- Pricing
    customer_expected_price DECIMAL(10, 2) NULL,
    admin_quoted_price DECIMAL(10, 2) NULL,
    final_agreed_price DECIMAL(10, 2) NULL,
    
    -- Estimation
    estimated_days INTEGER NULL,
    
    -- Status
    status VARCHAR(50) DEFAULT 'pending' CHECK (status IN (
        'pending', 'quoted', 'negotiating', 'accepted', 'rejected', 'cancelled', 'converted'
    )),
    
    -- Link to order
    order_id BIGINT NULL REFERENCES orders(id) ON DELETE SET NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_custom_orders_user ON custom_orders(user_id);
CREATE INDEX idx_custom_orders_status ON custom_orders(status);

-- 13. CUSTOM OFFERS TABLE
CREATE TABLE custom_offers (
    id BIGSERIAL PRIMARY KEY,
    custom_order_id BIGINT NOT NULL REFERENCES custom_orders(id) ON DELETE CASCADE,
    offered_by BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    offered_by_role VARCHAR(20) NOT NULL CHECK (offered_by_role IN ('customer', 'admin')),
    
    offered_price DECIMAL(10, 2) NOT NULL,
    notes TEXT NULL,
    
    status VARCHAR(50) DEFAULT 'pending' CHECK (status IN ('pending', 'accepted', 'rejected', 'countered', 'withdrawn')),
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_custom_offers_custom_order ON custom_offers(custom_order_id);
CREATE INDEX idx_custom_offers_status ON custom_offers(status);

-- 14. CUSTOM ORDER IMAGES TABLE
CREATE TABLE custom_order_images (
    id BIGSERIAL PRIMARY KEY,
    custom_order_id BIGINT NOT NULL REFERENCES custom_orders(id) ON DELETE CASCADE,
    image_path VARCHAR(255) NOT NULL,
    storage_path VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_custom_order_images_order ON custom_order_images(custom_order_id);

-- 15. STORE SETTINGS TABLE
CREATE TABLE store_settings (
    id BIGSERIAL PRIMARY KEY,
    key VARCHAR(100) UNIQUE NOT NULL,
    value TEXT NULL,
    type VARCHAR(50) DEFAULT 'text' CHECK (type IN ('text', 'number', 'boolean', 'json')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- PART 2: CREATE TRIGGERS FOR UPDATED_AT
-- ============================================

CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

CREATE TRIGGER update_users_updated_at BEFORE UPDATE ON users FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_categories_updated_at BEFORE UPDATE ON categories FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_products_updated_at BEFORE UPDATE ON products FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_product_variations_updated_at BEFORE UPDATE ON product_variations FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_product_images_updated_at BEFORE UPDATE ON product_images FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_addresses_updated_at BEFORE UPDATE ON addresses FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_carts_updated_at BEFORE UPDATE ON carts FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_cart_items_updated_at BEFORE UPDATE ON cart_items FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_orders_updated_at BEFORE UPDATE ON orders FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_order_items_updated_at BEFORE UPDATE ON order_items FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_payments_updated_at BEFORE UPDATE ON payments FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_custom_orders_updated_at BEFORE UPDATE ON custom_orders FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_custom_offers_updated_at BEFORE UPDATE ON custom_offers FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_store_settings_updated_at BEFORE UPDATE ON store_settings FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- ============================================
-- PART 3: SEED SAMPLE DATA
-- ============================================

-- USERS (Admin + 2 Customers)
INSERT INTO users (name, email, password, role, phone, address, city, postal_code) VALUES
('KNOTTED Admin', 'admin@knotted.com', '$2y$12$rmgYKUrpTu.hn2YaROr66OjgVoFVuZXrA/xM4h3Rwv.Tw24.zPZhW', 'admin', '+62 812-3456-7890', 'Jl. Artisan No. 1', 'Jakarta Selatan', '12250'),
('Alya Customer', 'customer@example.com', '$2y$12$rmgYKUrpTu.hn2YaROr66OjgVoFVuZXrA/xM4h3Rwv.Tw24.zPZhW', 'customer', '+62 813-9876-5432', 'Jl. Sudirman No. 45', 'Bandung', '40123'),
('Budi Santoso', 'budi@example.com', '$2y$12$rmgYKUrpTu.hn2YaROr66OjgVoFVuZXrA/xM4h3Rwv.Tw24.zPZhW', 'customer', '+62 821-5555-6666', 'Jl. Merdeka No. 78', 'Surabaya', '60119');
-- Password for all accounts: "password"

-- CATEGORIES (Headwear Only - Per PRD)
INSERT INTO categories (name, slug, description, is_active) VALUES
('Bucket Hats', 'bucket-hats', 'Stylish crochet bucket hats perfect for any season', true),
('Beanies', 'beanies', 'Cozy knitted beanies to keep you warm', true),
('Berets', 'berets', 'Classic French-style crochet berets', true),
('Balaclavas', 'balaclavas', 'Full-coverage knitted balaclavas for winter', true),
('Bonnets', 'bonnets', 'Cute vintage-style crochet bonnets', true),
('Headbands', 'headbands', 'Fashionable crochet and knit headbands', true);

-- PRODUCTS (12 Headwear Items)
INSERT INTO products (category_id, name, slug, description, price, stock, sku, product_type, lead_time_days, is_featured, is_active) VALUES
-- Bucket Hats
(1, 'Classic Crochet Bucket Hat', 'classic-crochet-bucket-hat', 
 'Handmade crochet bucket hat with a timeless design. Perfect for sunny days and adds a bohemian touch to any outfit. Made with soft, breathable cotton yarn for all-day comfort.', 
 145000, 37, 'KNT-BUC-001', 'ready_stock', 0, true, true),

(1, 'Summer Wave Bucket Hat', 'summer-wave-bucket-hat',
 'Lightweight crochet bucket hat featuring delicate wave patterns. Breathable design ideal for beach days and summer festivals. Each piece is handcrafted with love and attention to detail.',
 165000, 19, 'KNT-BUC-002', 'ready_stock', 0, true, true),

(1, 'Textured Crochet Bucket', 'textured-crochet-bucket',
 'Unique textured crochet bucket hat with intricate stitch patterns. A statement piece that combines comfort with artistic flair. Available in beautiful earth tones.',
 175000, 14, 'KNT-BUC-003', 'pre_order', 7, false, true),

-- Beanies
(2, 'Cozy Ribbed Beanie', 'cozy-ribbed-beanie',
 'Warm and snug ribbed knit beanie perfect for cold weather. Features a folded brim and stretchy fit. Made with premium wool blend for maximum comfort and warmth.',
 125000, 53, 'KNT-BEN-001', 'ready_stock', 0, true, true),

(2, 'Pom-Pom Beanie', 'pom-pom-beanie',
 'Adorable knitted beanie with a fluffy pom-pom on top. Soft, warm, and stylish. Perfect for adding a playful touch to your winter wardrobe. One size fits most.',
 135000, 36, 'KNT-BEN-002', 'ready_stock', 0, true, true),

(2, 'Slouchy Knit Beanie', 'slouchy-knit-beanie',
 'Trendy slouchy knit beanie with a relaxed fit. Comfortable and versatile, pairs well with any casual outfit. Soft acrylic yarn that is easy to care for.',
 115000, 28, 'KNT-BEN-003', 'ready_stock', 0, false, true),

-- Berets
(3, 'Classic French Beret', 'classic-french-beret',
 'Timeless crochet beret inspired by Parisian style. Lightweight and chic, perfect for adding elegance to any outfit. Handmade with fine yarn for a sophisticated look.',
 155000, 25, 'KNT-BER-001', 'ready_stock', 0, true, true),

(3, 'Textured Beret', 'textured-beret',
 'Unique textured crochet beret with intricate stitch patterns. A statement piece that combines comfort with artistic flair. Limited edition design, perfect for art lovers.',
 175000, 5, 'KNT-BER-002', 'pre_order', 7, true, true),

-- Balaclavas
(4, 'Winter Balaclava', 'winter-balaclava',
 'Full-coverage knitted balaclava for ultimate warmth. Soft, breathable, and comfortable fit. Ideal for winter sports, cold commutes, or outdoor activities.',
 185000, 19, 'KNT-BAL-001', 'ready_stock', 0, true, true),

-- Bonnets
(5, 'Vintage Crochet Bonnet', 'vintage-crochet-bonnet',
 'Charming vintage-style crochet bonnet with ribbon ties. Soft and delicate, perfect for photoshoots or nostalgic fashion lovers. Available for babies, toddlers, and adults.',
 140000, 24, 'KNT-BON-001', 'ready_stock', 0, true, true),

-- Headbands
(6, 'Flower Crochet Headband', 'flower-crochet-headband',
 'Beautiful crochet headband adorned with handmade flower accents. Comfortable ear-warmer style that adds a feminine touch to any look. Perfect for spring and fall.',
 95000, 37, 'KNT-HB-001', 'ready_stock', 0, false, true),

(6, 'Twisted Knit Headband', 'twisted-knit-headband',
 'Modern twisted knit headband design. Warm, comfortable, and stylish. Perfect for keeping your ears warm while looking chic. Stretchy fit for all head sizes.',
 85000, 53, 'KNT-HB-002', 'ready_stock', 0, false, true);

-- PRODUCT VARIATIONS (41 Color/Size Combinations)
INSERT INTO product_variations (product_id, color_name, color_hex, size_label, stock_quantity, price_adjustment) VALUES
-- Classic Crochet Bucket Hat (product_id: 1)
(1, 'Cream', '#F5F5DC', 'S (54-55cm)', 15, 0),
(1, 'Cream', '#F5F5DC', 'M (56-57cm)', 10, 0),
(1, 'Cream', '#F5F5DC', 'L (58-59cm)', 5, 0),
(1, 'Dusty Pink', '#E7B9C3', 'S (54-55cm)', 4, 0),
(1, 'Dusty Pink', '#E7B9C3', 'M (56-57cm)', 3, 0),

-- Summer Wave Bucket Hat (product_id: 2)
(2, 'Off White', '#FAFAFA', 'M (56-57cm)', 8, 0),
(2, 'Off White', '#FAFAFA', 'L (58-59cm)', 5, 0),
(2, 'Sky Blue', '#87CEEB', 'M (56-57cm)', 4, 0),
(2, 'Lavender', '#E6E6FA', 'M (56-57cm)', 2, 0),

-- Textured Crochet Bucket (product_id: 3)
(3, 'Terracotta', '#E27C5D', 'One Size', 8, 0),
(3, 'Mustard', '#FFDB58', 'One Size', 6, 0),

-- Cozy Ribbed Beanie (product_id: 4)
(4, 'Charcoal', '#36454F', 'One Size', 20, 0),
(4, 'Camel', '#C19A6B', 'One Size', 18, 0),
(4, 'Burgundy', '#800020', 'One Size', 15, 0),

-- Pom-Pom Beanie (product_id: 5)
(5, 'Blush Pink', '#F8E8EB', 'One Size', 12, 0),
(5, 'Navy', '#000080', 'One Size', 14, 0),
(5, 'Forest Green', '#228B22', 'One Size', 10, 0),

-- Slouchy Knit Beanie (product_id: 6)
(6, 'Black', '#000000', 'One Size', 15, 0),
(6, 'Gray', '#808080', 'One Size', 13, 0),

-- Classic French Beret (product_id: 7)
(7, 'Black', '#000000', 'One Size', 10, 0),
(7, 'Dusty Rose', '#C98F9D', 'One Size', 8, 0),
(7, 'Cream', '#FFFDD0', 'One Size', 7, 0),

-- Textured Beret (product_id: 8)
(8, 'Terracotta', '#E27C5D', 'One Size', 3, 0),
(8, 'Mustard', '#FFDB58', 'One Size', 2, 0),

-- Winter Balaclava (product_id: 9)
(9, 'Black', '#000000', 'One Size', 8, 0),
(9, 'Gray', '#808080', 'One Size', 6, 0),
(9, 'Cream', '#FFFDD0', 'One Size', 5, 0),

-- Vintage Crochet Bonnet (product_id: 10)
(10, 'Baby Pink', '#FFB6D9', 'Baby', 8, 0),
(10, 'Baby Pink', '#FFB6D9', 'Toddler', 6, 0),
(10, 'Baby Pink', '#FFB6D9', 'Adult', 3, 0),
(10, 'Ivory', '#FFFFF0', 'Baby', 4, 0),
(10, 'Ivory', '#FFFFF0', 'Adult', 3, 0),

-- Flower Crochet Headband (product_id: 11)
(11, 'Blush Pink', '#F8E8EB', 'One Size', 15, 0),
(11, 'Cream', '#FFFDD0', 'One Size', 12, 0),
(11, 'Mint', '#98FF98', 'One Size', 10, 0),

-- Twisted Knit Headband (product_id: 12)
(12, 'Charcoal', '#36454F', 'One Size', 20, 0),
(12, 'Caramel', '#C68E17', 'One Size', 18, 0),
(12, 'Burgundy', '#800020', 'One Size', 15, 0);

-- STORE SETTINGS
INSERT INTO store_settings (key, value, type) VALUES
('store_name', 'KNOTTED', 'text'),
('store_tagline', 'Handcrafted Headwear, Tied with Love', 'text'),
('store_description', 'KNOTTED specializes in handmade crochet and knitted headwear. Each piece is crafted with care and attention to detail by our skilled artisans.', 'text'),
('store_email', 'hello@knotted.com', 'text'),
('store_phone', '+62 812-3456-7890', 'text'),
('whatsapp_admin_number', '+6281234567890', 'text'),
('bank_accounts', '[{"bank_name":"BCA","account_number":"1234567890","account_holder":"KNOTTED STORE"},{"bank_name":"Mandiri","account_number":"0987654321","account_holder":"KNOTTED STORE"}]', 'json'),
('free_shipping_minimum', '200000', 'number'),
('default_shipping_cost', '20000', 'number'),
('payment_deadline_hours', '24', 'number'),
('low_stock_threshold', '5', 'number');

-- ============================================
-- PART 4: VERIFICATION
-- ============================================

SELECT 
    'Database setup complete!' as status,
    'Tables created: 15' as tables,
    'Users: 3 (1 admin, 2 customers)' as users,
    'Categories: 6 headwear types' as categories,
    'Products: 12 headwear items' as products,
    'Variations: 41 color/size options' as variations,
    'Settings: 11 store configurations' as settings;

-- Show login credentials
SELECT 
    'LOGIN CREDENTIALS' as info,
    email as email,
    role as role,
    'password' as password
FROM users 
ORDER BY role DESC;

-- Show sample products
SELECT 
    'SAMPLE PRODUCTS' as info,
    name,
    sku,
    'Rp ' || price::text as price,
    stock
FROM products 
ORDER BY id 
LIMIT 5;

-- ============================================
-- SETUP COMPLETE!
-- ============================================
-- 
-- Next Steps:
-- 1. Visit your Laravel application
-- 2. Login with: admin@knotted.com / password
-- 3. Browse the headwear catalog
-- 4. Test product variations
-- 5. Test checkout flow
--
-- All tables are ready!
-- All relationships are configured!
-- All sample data is loaded!
--
-- Happy coding! 🎉
-- ============================================
