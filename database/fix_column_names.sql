-- Fix column names untuk product_variations table
-- Run this in Supabase SQL Editor

-- Rename 'stock' to 'stock_quantity' if exists
DO $$
BEGIN
    IF EXISTS (
        SELECT 1 
        FROM information_schema.columns 
        WHERE table_name = 'product_variations' 
        AND column_name = 'stock'
    ) AND NOT EXISTS (
        SELECT 1 
        FROM information_schema.columns 
        WHERE table_name = 'product_variations' 
        AND column_name = 'stock_quantity'
    ) THEN
        ALTER TABLE product_variations RENAME COLUMN stock TO stock_quantity;
        RAISE NOTICE 'Column renamed from stock to stock_quantity';
    ELSE
        RAISE NOTICE 'Column already named stock_quantity or stock does not exist';
    END IF;
END $$;

-- Verify the change
SELECT column_name, data_type 
FROM information_schema.columns 
WHERE table_name = 'product_variations' 
AND column_name IN ('stock', 'stock_quantity');

-- Show sample data
SELECT id, product_id, name, value, stock_quantity 
FROM product_variations 
LIMIT 5;
