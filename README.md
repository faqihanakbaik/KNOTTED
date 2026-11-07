# KNOTTED - Handmade Crochet E-Commerce

![KNOTTED](https://img.shields.io/badge/Laravel-12-red?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.5-blue?style=for-the-badge&logo=php)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-15-blue?style=for-the-badge&logo=postgresql)
![Railway](https://img.shields.io/badge/Railway-Ready-purple?style=for-the-badge&logo=railway)

**KNOTTED** adalah website e-commerce modern untuk produk handmade crochet dengan desain editorial yang elegan dan fitur lengkap untuk customer dan admin.

## 🎨 Design Philosophy

**"Made by hand, tied with love"**

- **Editorial Handmade**: Desain yang warm dan personal
- **Warm Minimalist**: Clean layout dengan sentuhan hangat
- **Vintage Modern**: Kombinasi klasik dan kontemporer

### Brand Colors
- **Cream** (#F5F0E8) - Background utama
- **Oat** (#DCCBBC) - Secondary elements
- **Mocha** (#947765) - Accents & hover
- **Espresso** (#342C28) - Text & primary buttons
- **Sage** (#A7A58C) - Tags & highlights

## ✨ Features

### Public Pages
- ✅ **Home** - Hero section, featured products, categories, testimonials
- ✅ **Shop** - Katalog dengan filter, search, sorting, dan pagination
- ✅ **Product Detail** - Galeri foto, deskripsi, add to cart, related products
- ✅ **About** - Brand story dan values
- ✅ **Contact** - Contact info dan FAQ

### Authentication
- ✅ **Login** - With remember me
- ✅ **Register** - Customer registration
- ✅ **Forgot Password** - Password reset

### Customer Features
- ✅ **Shopping Cart** - Add, update, remove items
- ✅ **Checkout** - Order placement dengan shipping info
- ✅ **Order History** - View semua pesanan
- ✅ **Order Detail** - Detail pesanan + payment proof upload
- ✅ **Profile Management** - Update info & change password
- ✅ **Payment Methods** - Bank Transfer & COD

### Admin Features
- ✅ **Dashboard** - Statistics & overview
- ✅ **Product Management** - CRUD products dengan image upload
- ✅ **Category Management** - CRUD categories
- ✅ **Order Management** - View & update order status
- ✅ **Payment Verification** - Verify bank transfer payments
- ✅ **Customer Management** - View customer data & order history

### Technical Features
- ✅ Role-based access control (Admin & Customer)
- ✅ Responsive design (Desktop, Tablet, Mobile)
- ✅ Image upload & management
- ✅ Stock management
- ✅ Order status tracking
- ✅ Flash messages & notifications
- ✅ Form validation
- ✅ Secure authentication

## 🛠️ Tech Stack

- **Backend**: Laravel 12, PHP 8.5
- **Database**: PostgreSQL (Supabase) / MySQL
- **Frontend**: Blade Templates, Tailwind CSS v4
- **Build Tools**: Vite
- **Assets**: Custom CSS with brand colors
- **Deployment**: Railway (PHP 8.5)

## 🚀 Railway Deployment

**Ready for production deployment on Railway.app**

### Quick Deploy to Railway

1. **Push to GitHub**
```bash
git push origin main
```

2. **Connect Railway**
   - Go to [Railway.app](https://railway.app)
   - Create new project → Connect GitHub repository
   - Railway auto-detects Laravel via `nixpacks.toml`

3. **Set Environment Variables**
   - See `DEPLOYMENT.md` for complete list
   - Required: `APP_KEY`, `APP_URL`, database credentials

4. **Post-Deploy Commands**
```bash
php artisan migrate --force
php artisan storage:link
php artisan optimize
```

### Deployment Files
- ✅ `nixpacks.toml` - Railway PHP 8.5 configuration
- ✅ `Procfile` - Web process definition
- ✅ `DEPLOYMENT.md` - Comprehensive deployment guide
- ✅ `DEPLOYMENT_CHECKLIST.md` - Step-by-step checklist
- ✅ `DEPLOYMENT_SUMMARY.md` - Deployment summary report

### PHP Version
- **Development**: PHP ^8.4 (tested on 8.5.12)
- **Production (Railway)**: PHP 8.5
- **Minimum**: PHP 8.4

## 📦 Installation

### Prerequisites
```bash
- PHP >= 8.4 (recommended: 8.5)
- Composer
- PostgreSQL >= 14 (or MySQL >= 8.0)
- Node.js & NPM
```

### Setup Steps

1. **Clone Repository**
```bash
git clone <repository-url>
cd KNOTTED
```

2. **Install Dependencies**
```bash
composer install
npm install
```

3. **Create Database**
```sql
CREATE DATABASE knotted CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

4. **Configure Environment**
```bash
# .env already configured, just update MySQL credentials if needed
DB_DATABASE=knotted
DB_USERNAME=root
DB_PASSWORD=
```

5. **Run Migrations & Seeders**
```bash
php artisan migrate
php artisan db:seed
```

6. **Build Assets**
```bash
npm run dev
# Or for production:
npm run build
```

7. **Start Server**
```bash
php artisan serve
```

Visit: **http://localhost:8000**

## 👤 Demo Accounts

### Admin
- **Email**: admin@knotted.com
- **Password**: password
- **Access**: Full admin dashboard

### Customer
- **Email**: customer@example.com
- **Password**: password
- **Access**: Shopping & orders

## 📁 Project Structure

```
KNOTTED/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/         # Admin controllers
│   │   │   ├── Auth/          # Authentication
│   │   │   ├── Customer/      # Customer features
│   │   │   └── *.php          # Public controllers
│   │   └── Middleware/        # Custom middleware
│   └── Models/                # Eloquent models
├── database/
│   ├── migrations/            # Database schema
│   └── seeders/               # Sample data
├── resources/
│   ├── css/
│   │   └── app.css           # Custom Tailwind CSS
│   ├── js/
│   │   └── app.js
│   └── views/
│       ├── layouts/          # Main & admin layouts
│       ├── components/       # Reusable components
│       ├── admin/           # Admin pages
│       ├── customer/        # Customer pages
│       ├── shop/            # Shop pages
│       └── auth/            # Auth pages
├── routes/
│   └── web.php              # All routes
└── public/
    └── images/
        ├── products/        # Product images
        └── payments/        # Payment proofs
```

## 📊 Database Schema

### Core Tables
- **users** - Admin & customers dengan role
- **categories** - Product categories
- **products** - Crochet products
- **carts** & **cart_items** - Shopping cart
- **orders** & **order_items** - Order management
- **payments** - Payment tracking

## 🎯 Sample Data

**18 Products** across **5 Categories**:
1. Crochet Amigurumi (4 products)
2. Crochet Flowers (3 products)
3. Crochet Keychains (3 products)
4. Crochet Bags (3 products)
5. Crochet Accessories (5 products)

## 🚀 Key Features Implemented

### Customer Journey
1. Browse products (public)
2. Register/Login
3. Add to cart
4. Checkout with shipping info
5. Choose payment method (Bank Transfer/COD)
6. Upload payment proof (Bank Transfer)
7. Track order status
8. Manage profile

### Admin Journey
1. Login as admin
2. View dashboard statistics
3. Manage products & categories
4. View & update orders
5. Verify payments
6. Manage customers

## 🎨 Design Highlights

- **Typography**: Serif untuk headings, Sans-serif untuk body
- **Whitespace**: Generous spacing untuk breathing room
- **Cards**: Soft shadows dengan border subtle
- **Hover Effects**: Smooth transitions
- **Responsive**: Mobile-first approach
- **Colors**: Consistent brand palette
- **Forms**: Clean dengan validation feedback
- **Buttons**: Clear primary & secondary actions

## 🔐 Security

- Password hashing dengan bcrypt
- CSRF protection
- Role-based middleware
- Input validation
- Secure file uploads
- SQL injection prevention (Eloquent ORM)

## 📱 Responsive Design

- **Desktop**: Full layout dengan sidebar (admin)
- **Tablet**: Optimized grid
- **Mobile**: Stacked layout dengan hamburger menu

## 🎯 Todo / Future Enhancements

- [ ] Product reviews & ratings
- [ ] Wishlist functionality
- [ ] Email notifications
- [ ] Advanced search
- [ ] Sales reports & analytics
- [ ] Inventory alerts
- [ ] Multiple product images
- [ ] Custom product options
- [ ] Discount codes
- [ ] Newsletter subscription

## 📄 License

This project is for educational purposes.

## 👨‍💻 Developer

Built with ❤️ using Laravel 12

---

**KNOTTED** - Where every stitch tells a story
