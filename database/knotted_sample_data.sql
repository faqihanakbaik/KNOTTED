-- ============================================
-- KNOTTED E-Commerce - Sample Data Only
-- Insert sample data into existing tables
-- Use after running: php artisan migrate
-- ============================================

-- Clear existing data (optional - remove if you want to keep existing data)
TRUNCATE TABLE cart_items, carts, order_items, payments, orders, product_images, product_variations, products, categories, custom_order_images, custom_offers, custom_orders, addresses, store_settings, users CASCADE;

-- ============================================
-- 1. USERS
-- ============================================
INSERT INTO users (name, email, email_verified_at, password, role, phone, address, city, postal_code, created_at, updated_at) VALUES
('Admin KNOTTED', 'admin@knotted.com', NOW(), '$2y$12$Bi3kpxEEW86XnkvUCf8MhOft1KMwfUst4.rPIBZYRP/pP2AyzEuKC', 'admin', '081234567890', 'Jl. Handmade No. 1', 'Jakarta', '12345', NOW(), NOW()),
('Customer Test', 'customer@test.com', NOW(), '$2y$12$Bi3kpxEEW86XnkvUCf8MhOft1KMwfUst4.rPIBZYRP/pP2AyzEuKC', 'customer', '081234567891', 'Jl. Customer No. 2', 'Bandung', '40123', NOW(), NOW()),
('Faqih Muqoddam', 'faqihmuqoddam3007@gmail.com', NOW(), '$2y$12$Bi3kpxEEW86XnkvUCf8MhOft1KMwfUst4.rPIBZYRP/pP2AyzEuKC', 'customer', '085892992913', 'Jl. Crochet Street', 'Jakarta Selatan', '12340', NOW(), NOW());

-- ============================================
-- 2. CATEGORIES
-- ============================================
INSERT INTO categories (name, slug, description, image, is_active, created_at, updated_at) VALUES
('Crochet Toys', 'crochet-toys', 'Handmade crochet stuffed toys and amigurumi', 'category-toys.jpg', TRUE, NOW(), NOW()),
('Crochet Accessories', 'crochet-accessories', 'Bags, pouches, and fashion accessories', 'category-accessories.jpg', TRUE, NOW(), NOW()),
('Crochet Apparel', 'crochet-apparel', 'Wearable crochet items like hats and scarves', 'category-apparel.jpg', TRUE, NOW(), NOW()),
('Home Decor', 'home-decor', 'Decorative items for your home', 'category-decor.jpg', TRUE, NOW(), NOW());

-- ============================================
-- 3. PRODUCTS
-- ============================================
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
-- 4. PRODUCT VARIATIONS
-- ============================================
INSERT INTO product_variations (product_id, name, value, price_modifier, stock_quantity, sku, created_at, updated_at) VALUES
(1, 'Color', 'White', 0, 5, 'KNT-CRO-001-WHT', NOW(), NOW()),
(1, 'Color', 'Pink', 0, 5, 'KNT-CRO-001-PNK', NOW(), NOW()),
(5, 'Color', 'Cream', 0, 4, 'KNT-HAT-001-CRM', NOW(), NOW()),
(5, 'Color', 'Brown', 0, 4, 'KNT-HAT-001-BRN', NOW(), NOW()),
(5, 'Color', 'Grey', 0, 4, 'KNT-HAT-001-GRY', NOW(), NOW());

-- ============================================
-- 5. ADDRESSES
-- ============================================
INSERT INTO addresses (user_id, label, recipient_name, phone, address, city, province, district, postal_code, is_default, created_at, updated_at) VALUES
(2, 'Home', 'Customer Test', '081234567891', 'Jl. Testing No. 123', 'Bandung', 'Jawa Barat', 'Coblong', '40132', TRUE, NOW(), NOW()),
(3, 'Home', 'Faqih Muqoddam', '085892992913', 'Jl. Crochet Street No. 45', 'Jakarta Selatan', 'DKI Jakarta', 'Kebayoran Baru', '12120', TRUE, NOW(), NOW());

-- ============================================
-- 6. ORDERS
-- ============================================
INSERT INTO orders (order_number, user_id, status, payment_status, subtotal, shipping_cost, total_amount, payment_method, customer_name, customer_email, customer_phone, recipient_name, recipient_phone, shipping_address, city, province, district, postal_code, notes, created_at, updated_at) VALUES
('ORD-20261106-0001', 2, 'pending_payment', 'pending', 220000, 15000, 235000, 'bank_transfer', 'Customer Test', 'customer@test.com', '081234567891', 'Customer Test', '081234567891', 'Jl. Testing No. 123', 'Bandung', 'Jawa Barat', 'Coblong', '40132', 'Please pack carefully', NOW(), NOW()),
('ORD-20261106-0002', 3, 'processing', 'paid', 95000, 15000, 110000, 'qris', 'Faqih Muqoddam', 'faqihmuqoddam3007@gmail.com', '085892992913', 'Faqih Muqoddam', '085892992913', 'Jl. Crochet Street No. 45', 'Jakarta Selatan', 'DKI Jakarta', 'Kebayoran Baru', '12120', NULL, NOW() - INTERVAL '2 days', NOW()),
('ORD-20261106-0003', 2, 'completed', 'paid', 150000, 15000, 165000, 'bank_transfer', 'Customer Test', 'customer@test.com', '081234567891', 'Customer Test', '081234567891', 'Jl. Testing No. 123', 'Bandung', 'Jawa Barat', 'Coblong', '40132', NULL, NOW() - INTERVAL '7 days', NOW());

-- ============================================
-- 7. ORDER ITEMS
-- ============================================
INSERT INTO order_items (order_id, product_id, variation_id, product_name, variation_details, quantity, price, subtotal, created_at, updated_at) VALUES
(1, 1, 1, 'Bunny Amigurumi', 'Color: White', 1, 95000, 95000, NOW(), NOW()),
(1, 5, 3, 'Crochet Beanie', 'Color: Cream', 1, 85000, 85000, NOW(), NOW()),
(1, 7, NULL, 'Crochet Coaster Set', NULL, 1, 55000, 55000, NOW(), NOW()),
(2, 1, 2, 'Bunny Amigurumi', 'Color: Pink', 1, 95000, 95000, NOW() - INTERVAL '2 days', NOW() - INTERVAL '2 days'),
(3, 3, NULL, 'Crochet Tote Bag', NULL, 1, 150000, 150000, NOW() - INTERVAL '7 days', NOW() - INTERVAL '7 days');

-- ============================================
-- 8. PAYMENTS
-- ============================================
INSERT INTO payments (order_id, amount, payment_method, payment_date, payment_proof, status, notes, admin_notes, verified_at, verified_by, created_at, updated_at) VALUES
(2, 110000, 'qris', CURRENT_DATE - INTERVAL '2 days', 'payment_proof_order_2.jpg', 'paid', 'Paid via QRIS', 'Verified - payment received', NOW() - INTERVAL '1 day', 1, NOW() - INTERVAL '2 days', NOW() - INTERVAL '1 day'),
(3, 165000, 'bank_transfer', CURRENT_DATE - INTERVAL '7 days', 'payment_proof_order_3.jpg', 'paid', 'Transfer dari BCA', 'Verified - amount correct', NOW() - INTERVAL '6 days', 1, NOW() - INTERVAL '7 days', NOW() - INTERVAL '6 days');

-- ============================================
-- 9. STORE SETTINGS
-- ============================================
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
-- SUMMARY
-- ============================================

-- Data Inserted:
-- ✓ 3 Users (1 Admin, 2 Customers)
-- ✓ 4 Categories
-- ✓ 8 Products
-- ✓ 5 Product Variations
-- ✓ 2 Addresses
-- ✓ 3 Orders (pending, processing, completed)
-- ✓ 5 Order Items
-- ✓ 2 Payments (paid)
-- ✓ 11 Store Settings

-- Login Credentials:
-- Admin: admin@knotted.com / password
-- Customer: customer@test.com / password
-- Faqih: faqihmuqoddam3007@gmail.com / password

-- Contact:
-- Email: faqihmuqoddam3007@gmail.com
-- Phone: 085892992913
-- Instagram: @faqiiihhhhhh
-- GitHub: faqihanakbaik

SELECT 'Sample data imported successfully!' AS status;
