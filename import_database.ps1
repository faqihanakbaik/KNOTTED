# Import Database to Supabase via psql
# Make sure you have PostgreSQL client (psql) installed

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  KNOTTED Database Import to Supabase" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Load .env variables
if (Test-Path .env) {
    Get-Content .env | ForEach-Object {
        if ($_ -match '^\s*([^#][^=]+)=(.*)$') {
            $name = $matches[1].Trim()
            $value = $matches[2].Trim()
            [Environment]::SetEnvironmentVariable($name, $value, "Process")
        }
    }
    Write-Host "✓ Loaded .env variables" -ForegroundColor Green
} else {
    Write-Host "✗ .env file not found!" -ForegroundColor Red
    exit 1
}

# Get database credentials from .env
$DB_HOST = $env:DB_HOST
$DB_PORT = $env:DB_PORT
$DB_DATABASE = $env:DB_DATABASE
$DB_USERNAME = $env:DB_USERNAME
$DB_PASSWORD = $env:DB_PASSWORD

Write-Host ""
Write-Host "Database Connection Info:" -ForegroundColor Yellow
Write-Host "  Host: $DB_HOST"
Write-Host "  Port: $DB_PORT"
Write-Host "  Database: $DB_DATABASE"
Write-Host "  Username: $DB_USERNAME"
Write-Host ""

# Check if psql is available
$psqlPath = Get-Command psql -ErrorAction SilentlyContinue
if (-not $psqlPath) {
    Write-Host "✗ psql command not found!" -ForegroundColor Red
    Write-Host ""
    Write-Host "Please install PostgreSQL client tools:" -ForegroundColor Yellow
    Write-Host "  1. Download from: https://www.postgresql.org/download/windows/" -ForegroundColor Cyan
    Write-Host "  2. OR use Supabase SQL Editor directly" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "Alternative: Copy contents of 'database/knotted_complete_database.sql'" -ForegroundColor Yellow
    Write-Host "and paste into Supabase SQL Editor manually." -ForegroundColor Yellow
    Write-Host ""
    exit 1
}

Write-Host "✓ psql found: $($psqlPath.Source)" -ForegroundColor Green
Write-Host ""

# Confirm before proceeding
Write-Host "⚠️  WARNING: This will DROP all existing tables and recreate them!" -ForegroundColor Red
Write-Host "   All current data will be lost." -ForegroundColor Red
Write-Host ""
$confirmation = Read-Host "Type 'YES' to continue"

if ($confirmation -ne "YES") {
    Write-Host "Import cancelled." -ForegroundColor Yellow
    exit 0
}

Write-Host ""
Write-Host "Starting database import..." -ForegroundColor Cyan

# Set PGPASSWORD environment variable for psql
$env:PGPASSWORD = $DB_PASSWORD

# Run psql to import the SQL file
$connectionString = "postgresql://${DB_USERNAME}@${DB_HOST}:${DB_PORT}/${DB_DATABASE}"

Write-Host "Executing SQL file..." -ForegroundColor Yellow

try {
    psql $connectionString -f "database/knotted_complete_database.sql" 2>&1 | Tee-Object -Variable output
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host ""
        Write-Host "========================================" -ForegroundColor Green
        Write-Host "  ✓ Database imported successfully!" -ForegroundColor Green
        Write-Host "========================================" -ForegroundColor Green
        Write-Host ""
        Write-Host "Tables created:" -ForegroundColor Cyan
        Write-Host "  ✓ users (with admin & customer sample data)"
        Write-Host "  ✓ categories (4 categories)"
        Write-Host "  ✓ products (8 products)"
        Write-Host "  ✓ product_variations"
        Write-Host "  ✓ orders, order_items, payments"
        Write-Host "  ✓ carts, cart_items"
        Write-Host "  ✓ addresses"
        Write-Host "  ✓ custom_orders, custom_offers"
        Write-Host "  ✓ store_settings"
        Write-Host ""
        Write-Host "Sample login credentials:" -ForegroundColor Yellow
        Write-Host "  Admin: admin@knotted.com / admin123"
        Write-Host "  Customer: customer@example.com / password"
        Write-Host ""
    } else {
        Write-Host ""
        Write-Host "✗ Import failed! Check errors above." -ForegroundColor Red
        Write-Host ""
    }
} catch {
    Write-Host ""
    Write-Host "✗ Error during import: $_" -ForegroundColor Red
    Write-Host ""
}

# Clear password from environment
Remove-Item Env:PGPASSWORD -ErrorAction SilentlyContinue
