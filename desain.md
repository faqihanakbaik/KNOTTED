# KNOTTED — ULTIMATE MASTER IMPLEMENTATION PROMPT
## Complete Laravel 12 E-Commerce Platform | Google Stitch UI/UX | Supabase PostgreSQL | Supabase Storage

**Project:** KNOTTED — Premium Handmade Crochet & Knitted Headwear E-Commerce Platform

**Tagline:** Made by hand, customized by you.

**Primary Objective:** Transform the existing KNOTTED Laravel project into a complete, functional, secure, responsive, and visually accurate e-commerce application based on the approved Google Stitch designs and the official KNOTTED Product Requirements Document.

**Development Environment:** Windows, FlyEnv, PHP 8.5, Laravel 12, Blade, Supabase PostgreSQL.

---

# 1. YOUR ROLE AND RESPONSIBILITIES

Act as a Senior Full-Stack Laravel Developer, Software Architect, Database Engineer, UI/UX Implementation Specialist, Security Engineer, and QA Engineer.

Your responsibility is to complete the existing KNOTTED project.

You must work directly inside the existing Laravel codebase.

This is NOT a request to:

- Create a new Laravel project.
- Build a static HTML prototype.
- Generate screenshots only.
- Create a frontend that uses fake data.
- Provide implementation instructions without changing the code.
- Replace the existing application with a different technology stack.

You must inspect, implement, integrate, test, and document the actual application.

The final application must support a complete customer-to-admin workflow.

## Expected application capabilities

Customers must be able to:

- Browse the public product catalog.
- Search and filter headwear products.
- View detailed product information.
- Select product colors and sizes.
- Register and log in.
- Manage their profiles and addresses.
- Add products to their shopping cart.
- Place orders.
- Pay using manual QRIS or bank transfer.
- Upload payment proof.
- View order status and production progress.
- View shipping information and tracking numbers.
- Submit customized headwear requests.
- Receive and respond to custom quotations.
- Contact the administrator through WhatsApp.

Administrators must be able to:

- View dashboard statistics.
- Manage products.
- Manage categories.
- Manage product images.
- Manage product variations and inventory.
- Manage customers.
- Manage orders.
- Verify payment proofs.
- Manage custom requests and quotations.
- Update production and shipping statuses.
- Configure store information.
- Configure QRIS and bank transfer information.
- Configure the WhatsApp contact number.

All important operations must be connected to the actual Laravel backend and Supabase PostgreSQL database.

Every visible button, form, navigation link, filter, modal, and interactive component must have a real and working purpose.

---

# 2. FIXED PROJECT ENVIRONMENT

The existing project uses the following technology stack.

| Component | Required Technology |
|---|---|
| Backend | Laravel 12 |
| PHP | PHP 8.5 |
| Frontend | Laravel Blade |
| Styling | Existing Tailwind CSS configuration + custom CSS |
| Client-side interaction | Vanilla JavaScript |
| Database | Supabase PostgreSQL |
| Image/file storage | Supabase Storage |
| Authentication | Laravel Authentication |
| Architecture | MVC |
| Version control | Git and GitHub |
| Local development | Windows + FlyEnv |

## Strict technology restrictions

Do not:

- Switch to MySQL.
- Switch to SQLite.
- Switch to MongoDB.
- Replace Laravel with React.
- Replace Laravel with Next.js.
- Replace Laravel with FastAPI.
- Build a separate frontend application.
- Replace Blade with another frontend framework.
- Introduce unnecessary dependencies.
- Create a second database.
- Hardcode database credentials.
- Expose Supabase service-role keys in frontend JavaScript.

Keep Laravel as the central application backend.

All application database operations must be handled by Laravel through the existing PostgreSQL connection.

## Styling decision

Inspect the existing `package.json`, Tailwind configuration, Vite configuration, and CSS files.

Use the existing Tailwind setup if it is already configured.

Use custom CSS for design tokens, complex layouts, animations, and styling that is more maintainable outside utility classes.

Do not migrate between CSS frameworks unnecessarily.

The visual result matters more than introducing a new styling dependency.

---

# 3. PROJECT IDENTITY AND BUSINESS SCOPE

**Brand:** KNOTTED

**Tagline:** Made by hand, customized by you.

**Business concept:** Premium handmade crochet and knitted headwear with ready-to-ship products and a bespoke atelier customization service.

## Strict product scope

KNOTTED exclusively sells handmade crochet and knitted headwear.

Approved product examples:

- Crochet bucket hats.
- Knitted beanies.
- Open-stitch beanies.
- Handcrafted berets.
- Sculptural sun hats.
- Balaclavas.
- Bonnets.
- Knitted headbands.
- Other handmade headwear.

Do not introduce unrelated product categories such as:

- Bags.
- Cardigans.
- Sweaters.
- Shoes.
- General clothing.
- Unrelated fashion accessories.

Some Stitch reference screens may contain unrelated placeholder products.

Replace those examples with appropriate headwear products while preserving the original layout and visual treatment.

The custom-order service must also remain focused on headwear.

## Brand personality

- Premium.
- Handmade.
- Contemporary.
- Editorial.
- Warm.
- Minimalist.
- Elegant.
- Creative.
- Personal.
- Feminine without looking childish.

The application must feel like a real independent handmade fashion brand, not a generic e-commerce template.

Do not invent business claims, certifications, artisan identities, material certifications, production locations, or sustainability claims.

Use editable content or clearly identified placeholders until the owner provides verified information.

---

# 4. FIRST TASK — COMPLETE PROJECT AUDIT

Before changing anything, inspect the existing project.

Do not immediately overwrite files.

Do not generate a replacement Laravel application.

Inspect at least:

- `composer.json`
- `composer.lock`
- `package.json`
- `.env.example`
- `.gitignore`
- `config/`
- `routes/web.php`
- `routes/console.php`
- `app/Http/Controllers/`
- `app/Http/Middleware/`
- `app/Models/`
- `app/Providers/`
- `app/Services/`, if present
- `database/migrations/`
- `database/seeders/`
- `resources/views/`
- `resources/css/`
- `resources/js/`
- `public/`
- `storage/`
- Existing Supabase configuration.
- Existing storage integration.
- Existing authentication implementation.

Inspect the current database schema using safe, read-only operations wherever possible.

## Known existing project architecture

The existing KNOTTED project has previously included controllers and models for:

- Products.
- Categories.
- Orders.
- Order items.
- Payments.
- Cart.
- Cart items.
- Addresses.
- Custom orders.
- Custom offers.
- Custom order images.
- Store settings.
- Customer management.
- Admin dashboard.
- Authentication.
- Payment verification.

It may also contain existing Blade views for public pages, customer pages, and admin pages.

These are starting points, not guaranteed complete implementations.

Inspect the actual current files before making decisions.

## Audit document

Create or update:

`PROJECT_AUDIT.md`

Include:

1. Existing project architecture.
2. Existing controllers and their responsibilities.
3. Existing models and relationships.
4. Existing migrations.
5. Existing database tables.
6. Existing routes.
7. Existing UI pages.
8. Existing completed features.
9. Incomplete features.
10. Missing features.
11. Database inconsistencies.
12. Storage configuration.
13. Security issues.
14. Broken functionality.
15. Recommended implementation order.

After completing the audit, continue with implementation.

Do not stop after producing an audit or plan.

---

# 5. GOOGLE STITCH DESIGN REFERENCES

The supplied Google Stitch ZIP is the primary visual reference.

The recommended extracted location is:

`design-references/knotted-stitch/`

Inspect the actual files, not only their filenames.

Look for:

- `DESIGN.md`
- `code.html`
- `screen.png`

Preserve the original directory structure.

If the ZIP has not been extracted, identify that limitation and request extraction rather than pretending to have inspected its contents.

## The 18 approved reference screens

### Public pages

1. `knotted_homepage`
2. `knotted_product_detail`
3. `knotted_category_listing`
4. `knotted_search_results`
5. `knotted_about_knotted`
6. `knotted_faq_help_center`
7. `knotted_contact_us`
8. `knotted_404_not_found`

### Authentication pages

9. `knotted_login`
10. `knotted_register`

### Customer pages

11. `knotted_cart_checkout`
12. `knotted_order_confirmation`
13. `knotted_customer_dashboard`
14. `knotted_detail_pesanan_pelanggan`
15. `knotted_konfirmasi_unggah_bukti_pembayaran`

### Custom order page

16. `knotted_bespoke_custom_order`

### Admin pages

17. `knotted_admin_dashboard_overview`
18. `knotted_admin_detail_kelola_pesanan`

All 18 screens must be implemented.

## Design implementation rules

Treat the original Stitch HTML and screenshots as the approved UI/UX direction.

Reproduce:

- Overall composition.
- Section order.
- Content hierarchy.
- Navigation.
- Typography.
- Spacing.
- Image proportions.
- Card dimensions.
- Button styling.
- Input styling.
- Border radius.
- Background colors.
- Footer composition.
- Desktop layout.
- Mobile layout.

Do not arbitrarily redesign the website.

Do not simplify every page into the same generic dashboard template.

Do not replace the approved design with Bootstrap templates.

Shared components should be reusable, but each page must preserve its intended composition.

When a design contains a decorative element that cannot be implemented literally, preserve its visual appearance and implement an appropriate functional alternative.

---

# 6. DESIGN SYSTEM AND VISUAL IDENTITY

Follow the supplied `DESIGN.md`.

## Color palette

| Token | Hex | Usage |
|---|---|---|
| Blush Pink | `#F8E8EB` | Soft backgrounds |
| Dusty Pink | `#E7B9C3` | Borders and accents |
| Rose Pink | `#C98F9D` | Interactive secondary elements |
| Deep Rose | `#A96F7D` | Primary CTA |
| Warm White | `#FFFDFB` | Main background |
| Pure White | `#FFFFFF` | Card surfaces |
| Primary Text | `#514449` | Main typography |
| Secondary Text | `#74666B` | Metadata and captions |
| Border | `#F0E1E4` | Dividers and outlines |

Use white and warm white as the dominant backgrounds.

Use pink as a supporting accent.

Avoid excessive pink surfaces.

Avoid neon colors, overly saturated gradients, and childish visual styling.

## Typography

Use:

- Syne for headings and editorial display.
- Plus Jakarta Sans for body text, forms, tables, navigation, and metadata.

Maintain consistent typography hierarchy.

## Component geometry

Preferred styling:

- Pill-shaped buttons where appropriate.
- Rounded badges.
- 16px and 24px card radii.
- Soft shadows.
- Subtle rose borders.
- Generous whitespace.
- Editorial image compositions.
- Consistent spacing.
- Clear hover, focus, active, and disabled states.

## Centralized design tokens

Create or update centralized CSS variables.

Example:

```css
:root {
    --color-blush: #F8E8EB;
    --color-dusty-pink: #E7B9C3;
    --color-rose: #C98F9D;
    --color-deep-rose: #A96F7D;

    --color-warm-white: #FFFDFB;
    --color-white: #FFFFFF;

    --color-text: #514449;
    --color-muted: #74666B;
    --color-border: #F0E1E4;

    --font-heading: 'Syne', sans-serif;
    --font-body: 'Plus Jakarta Sans', sans-serif;
}
```

Do not create multiple conflicting versions of the same design tokens.

## Responsive breakpoints

Test at:

- 320px.
- 375px.
- 390px.
- 430px.
- 768px.
- 1024px.
- 1280px.
- 1440px.

No horizontal overflow.

Do not simply shrink desktop layouts.

Use appropriate mobile-specific layouts while preserving the visual identity.

---

# 7. APPLICATION ARCHITECTURE

Follow Laravel MVC conventions.

## Controllers

Controllers should coordinate:

- HTTP requests.
- Validation.
- Authorization.
- Service calls.
- Responses and redirects.

Avoid putting complicated business logic inside controllers.

## Models

Use Eloquent relationships.

Define:

- Fillable or guarded attributes.
- Relationships.
- Casts.
- Scopes.
- Accessors where useful.
- Appropriate model behavior.

## Services

Create service classes for complex or reused operations.

Suggested services:

- `OrderService`
- `OrderNumberService`
- `PaymentService`
- `CustomOrderService`
- `WhatsAppService`
- `ImageUploadService`
- `ShippingService`

Do not create service classes for every trivial operation.

## Middleware and authorization

Use proper server-side authorization.

Existing middleware such as `AdminMiddleware` and `CustomerMiddleware` should be inspected and reused where appropriate.

Use policies or explicit ownership checks for customer-owned resources.

A hidden admin button is not security.

## Blade architecture

Use reusable layouts, components, and partials.

Suggested structure:

```text
resources/views/
├── layouts/
│   ├── app.blade.php
│   ├── admin.blade.php
│   └── auth.blade.php
├── components/
├── partials/
├── auth/
├── shop/
├── products/
├── customer/
├── admin/
├── custom-orders/
├── about.blade.php
├── faq.blade.php
├── contact.blade.php
└── errors/
```

Adapt this structure to the existing project.

Do not create duplicate directories or duplicate components unnecessarily.

---

# 8. DATABASE AND SUPABASE POSTGRESQL

The existing Supabase PostgreSQL database is the application's database.

Do not create a second database.

Do not assume the current database exactly matches the Laravel migrations.

Inspect the actual PostgreSQL structure and compare it with:

- Existing migrations.
- Existing models.
- Existing relationships.
- Existing application queries.

## Important existing database considerations

The existing Supabase project may already contain application tables such as:

- `users`
- `addresses`
- `categories`
- `products`
- `carts`
- `cart_items`
- `orders`
- `order_items`
- `payments`
- `custom_orders`
- `custom_offers`
- `custom_order_images`
- `store_settings`

Some existing columns may differ from the original migration files.

For example, existing payment and order fields may have different names from older migrations.

Inspect the actual schema before implementing queries or migrations.

Do not assume a missing Laravel migrations table means all application tables are missing.

## Database safety rules

Never execute:

- `php artisan migrate:fresh`
- `php artisan db:wipe`
- `DROP SCHEMA public CASCADE`
- Unreviewed `DROP TABLE` operations.
- Destructive SQL against existing application tables.

Never modify Supabase-managed schemas:

- `auth`
- `storage`
- `realtime`
- `vault`

Do not blindly rerun old migrations against an existing populated database.

Prefer:

- Additive migrations.
- Reversible schema changes.
- Safe data transformations.
- Explicit schema reconciliation.

If a destructive operation is genuinely required, explain its impact and request explicit approval.

Do not silently delete existing records.

Do not commit database passwords, `.env`, Supabase service-role keys, or private customer information.

---

# 9. REQUIRED DATABASE ENTITIES

Reuse existing tables whenever possible.

The target application should support:

1. `users`
2. `categories`
3. `products`
4. `product_images`
5. `product_variations`
6. `addresses`
7. `carts`
8. `cart_items`
9. `orders`
10. `order_items`
11. `payments`
12. `custom_orders`
13. `custom_order_images`
14. `custom_offers`
15. `store_settings`

Wishlist and reviews may be implemented as additional entities if they can be integrated safely.

Do not create duplicate custom-order tables if `custom_orders`, `custom_offers`, and `custom_order_images` already provide the required structure.

## 9.1 Users

Required information:

- ID.
- Name.
- Email.
- Password hash.
- Phone.
- Role.
- Email verification timestamp where applicable.
- Timestamps.

Roles:

- `customer`
- `admin`

Registration must always assign `customer`.

Never trust a role submitted from the frontend.

## 9.2 Categories

Required information:

- ID.
- Name.
- Slug.
- Description.
- Image or storage path.
- Active status.
- Timestamps.

Only headwear-related categories are permitted.

## 9.3 Products

Required information:

- ID.
- Category ID.
- Name or title, following existing conventions.
- Slug.
- SKU.
- Description.
- Base price.
- Product type.
- Lead time.
- Active status.
- Featured status.
- Timestamps.

Support:

- `ready_stock`
- `made_to_order`

Use consistent internal values.

## 9.4 Product images

Where supported by the existing schema, store:

- Product ID.
- Storage path.
- Alt text.
- Sort order.
- Primary image indicator.
- Timestamps.

## 9.5 Product variations

Implement or reconcile `product_variations`.

Required fields:

- `id`
- `product_id`
- `color_name`
- `hex_code`
- `size_label`
- `stock_count`
- `created_at`
- `updated_at`

Rules:

1. Each variation belongs to one product.
2. One product can have multiple variations.
3. Customers can select colors.
4. Customers can select sizes.
5. A color and size combination must identify the correct variation.
6. Stock must be tracked per variation.
7. Duplicate combinations for the same product must be prevented.
8. Out-of-stock variations cannot be purchased.
9. Admin can create, edit, and delete variations.
10. Cart items must preserve the selected variation.
11. Order items must preserve the selected variation as a snapshot.

Do not rely only on frontend validation.

The backend must revalidate stock during checkout.

## 9.6 Addresses

Support:

- User ID.
- Recipient name.
- Phone.
- Street address.
- District or subdistrict where appropriate.
- City.
- Province or region.
- Postal code.
- Delivery instructions.
- Default address indicator.
- Timestamps.

Customers must only manage their own addresses.

## 9.7 Carts and cart items

Support one active cart per authenticated customer where appropriate.

Cart items must preserve:

- Cart ID.
- Product ID.
- Variation ID where applicable.
- Quantity.
- Selected color.
- Selected size.
- Custom circumference if applicable.
- Customer note.
- Price snapshot if required by the existing design.

Validate product availability and stock when adding or updating cart items.

## 9.8 Orders

Required fields or equivalent existing fields:

- ID.
- Order number.
- User ID.
- Subtotal.
- Shipping cost.
- Total amount.
- Shipping address snapshot.
- Courier name.
- Tracking number.
- Order status.
- Payment status.
- Customer note.
- Timestamps.

The shipping address must be snapshotted when the order is created.

Changing a saved address must never change a previous order.

## 9.9 Order items

Store immutable purchase snapshots:

- Order ID.
- Product ID where applicable.
- Variation ID where applicable.
- Product name snapshot.
- SKU snapshot.
- Color snapshot.
- Size snapshot.
- Custom measurement snapshot if applicable.
- Unit price snapshot.
- Quantity.
- Item subtotal.
- Customer note.
- Timestamps.

Past order records must remain accurate even if the catalog changes.

## 9.10 Payments

Support:

- Order ID.
- Payment method.
- Transfer amount.
- Sender name.
- Sender bank where applicable.
- Proof storage path.
- Payment status.
- Rejection reason.
- Verified timestamp.
- Verified by admin ID.
- Timestamps.

Use the existing column names when appropriate.

Do not blindly rename existing fields such as `payment_proof`, `proof_image`, or `proof_image_url` without checking dependencies.

## 9.11 Custom orders

Reuse existing `custom_orders`.

The target functionality must support:

- Customer ID.
- Unique request number.
- Request type.
- Existing product ID where applicable.
- Product name snapshot.
- Headwear type.
- Description.
- Desired color.
- Head circumference.
- Size preference.
- Yarn preference.
- Quantity.
- Target budget.
- Quoted price.
- Final agreed price.
- Production days.
- Customer notes.
- Status.
- Timestamps.

## 9.12 Custom order images

Support:

- Custom order ID.
- Storage path.
- Original filename where appropriate.
- Timestamps.

## 9.13 Custom offers

Support:

- Custom order ID.
- Offered price.
- Production days.
- Offer notes.
- Offer status.
- Created by admin.
- Accepted or rejected timestamp where appropriate.

Preserve quotation history.

## 9.14 Store settings

Use the existing settings architecture where possible.

Support configuration for:

- Store name.
- Store logo.
- Contact email.
- WhatsApp number.
- BCA bank name.
- BCA account number.
- Account holder name.
- QRIS image path.
- Payment instructions.
- Shipping settings.
- Store availability.
- Contact information.

Sensitive settings must never be exposed to unauthorized users.

---

# 10. ORDER NUMBER GENERATION

Order number format:

`#KNT-YYYY-XXXX`

Example:

`#KNT-2026-0001`

Rules:

- `YYYY` represents the order year.
- `XXXX` represents a four-digit sequence.
- Generate the number on the backend.
- Enforce a unique database constraint.
- Handle concurrent checkout requests safely.
- Do not rely only on timestamps.
- Do not generate order numbers in JavaScript.
- Handle sequence overflow without producing duplicate numbers.

Use a transaction-safe PostgreSQL-compatible sequence or another concurrency-safe implementation.

The same order number must be used consistently throughout:

- Order confirmation.
- Customer dashboard.
- Payment page.
- Admin order management.
- WhatsApp messages.
- Invoices.

Custom request numbers should follow a separate unique format, for example:

`#CR-2026-001`

---

# 11. PUBLIC WEBSITE

Implement all public Stitch screens.

## 11.1 Homepage

Reference: `knotted_homepage`

Implement:

- Responsive navigation.
- KNOTTED branding.
- Search.
- Account navigation.
- Cart indicator.
- Editorial hero section.
- Brand introduction.
- Featured headwear.
- Category sections.
- Craftsmanship storytelling.
- Slow-fashion manifesto.
- Custom-order promotion.
- Customer reviews if real review data exists.
- Footer.
- WhatsApp concierge shortcut.

Product data must come from the database.

Do not use hardcoded product arrays as the permanent catalog.

Quick Add must perform a real cart operation.

If authentication is required, redirect the customer to login and preserve the intended destination where appropriate.

## 11.2 Product detail

Reference: `knotted_product_detail`

Implement:

- Product gallery.
- Main image.
- Additional images.
- Product name.
- SKU.
- Description.
- Price.
- Category.
- Product availability.
- Production lead time.
- Color selector.
- Size selector.
- Custom circumference option.
- Quantity selector.
- Customer note.
- Size guide.
- Product specifications.
- Care instructions.
- Add to cart.
- Ask Artisan via WhatsApp.
- Related products.

### Supported sizing

- Petite: 54–55cm.
- Standard: 56–57cm.
- Relaxed: 58–59cm.
- Custom circumference.

When custom circumference is selected, display a measurement input in centimeters.

Validate measurements on the backend.

The selected variation must be stored in the cart and order.

## 11.3 Category listing

Reference: `knotted_category_listing`

Implement:

- Dynamic category navigation.
- Product grid.
- Product counts.
- Availability filters.
- Color filters where supported.
- Size filters where supported.
- Price sorting.
- Newest sorting.
- Featured sorting.
- Clear filters.
- Pagination.
- Empty state.

All filters must operate on database queries.

## 11.4 Search results

Reference: `knotted_search_results`

Implement:

- Search input.
- Query persistence.
- Search results.
- Result count.
- Product filters.
- Sorting.
- Empty search state.
- Clear search.
- Relevant suggestions.

Search real product data.

Support searching by product name, SKU, description, and category where appropriate.

## 11.5 About

Reference: `knotted_about_knotted`

Implement:

- Brand story.
- Handmade philosophy.
- Craftsmanship.
- Materials and yarn.
- Production process.
- Brand values.
- Custom-order introduction.
- Contact CTA.

Do not invent verified business claims.

## 11.6 FAQ

Reference: `knotted_faq_help_center`

Implement:

- FAQ categories.
- Searchable questions.
- Expandable answers.
- Product sizing.
- Product care.
- Ready-to-ship.
- Made-to-order.
- Custom orders.
- Payment.
- Shipping.
- Returns and cancellation.
- Contact support CTA.

All answers must reflect the actual application behavior.

## 11.7 Contact

Reference: `knotted_contact_us`

Implement:

- Contact information.
- WhatsApp contact.
- Contact form.
- Inquiry category.
- Name.
- Email.
- Phone.
- Order reference where relevant.
- Message.
- Consent checkbox.
- Validation.
- Success and error states.

Do not invent email addresses, phone numbers, or business addresses.

## 11.8 404

Reference: `knotted_404_not_found`

Implement a branded Laravel 404 page.

Include:

- Return home.
- Explore collection.
- Custom-order CTA.
- Contact support.

Do not expose internal stack traces or application errors.

---

# 12. AUTHENTICATION

References:

- `knotted_login`
- `knotted_register`

Implement:

- Login.
- Registration.
- Logout.
- Forgot password.
- Reset password.

## Login

Support:

- Email.
- Password.
- Show/hide password.
- Remember me.
- Validation.
- Error feedback.
- Loading state.
- Role-based redirection.

Admin users go to the admin dashboard.

Customers go to the customer dashboard or intended destination.

## Registration

Support:

- Full name.
- Email.
- WhatsApp number.
- Password.
- Password confirmation.
- Terms and privacy agreement.
- Validation.
- Duplicate email prevention.

New registrations must always receive the customer role.

Never accept an admin role from a public form.

## Password reset

Use Laravel's proper password reset facilities.

Implement:

- Reset request.
- Email delivery.
- Token validation.
- Expiration.
- New password form.
- Confirmation.
- Error handling.

Do not create a fake password reset process.

If SMTP is not configured, document the missing configuration.

---

# 13. CUSTOMER DASHBOARD AND ACCOUNT

Reference: `knotted_customer_dashboard`

Implement:

- Overview.
- My Orders.
- Custom Requests.
- My Profile.
- My Addresses.
- Payment History.
- Logout.

Display real database records.

## Dashboard overview

Display:

- Customer greeting.
- Recent orders.
- Recent custom requests.
- Order statuses.
- Payment statuses.
- Quick actions.
- Support access.

## Profile

Allow customers to:

- View profile.
- Update name.
- Update email with verification if applicable.
- Update WhatsApp number.
- Change password.

## Addresses

Allow customers to:

- Add addresses.
- Edit addresses.
- Delete addresses.
- Set default address.
- Select delivery address during checkout.

## Orders

Display:

- Order number.
- Date.
- Items.
- Total.
- Payment status.
- Order status.
- Detail link.
- Invoice download if implemented.

## Custom requests

Display:

- Request number.
- Request type.
- Submission date.
- Status.
- Quoted price.
- Estimated production days.
- Request detail.
- Accept/reject actions when applicable.

## Wishlist and reviews

Implement as additional features if they can be integrated cleanly.

Wishlist:

- Add.
- Remove.
- View.
- Prevent duplicates.

Reviews:

- Only eligible customers can review purchased products.
- Validate rating and text.
- Prevent unauthorized reviews.
- Add moderation if appropriate.

Do not let optional features delay the core e-commerce workflow.

---

# 14. CART AND CHECKOUT

Reference: `knotted_cart_checkout`

Customers must authenticate before using cart and checkout.

## Cart

Implement:

- Product thumbnails.
- Product name.
- Selected variation.
- Selected color.
- Selected size.
- Customer note.
- Quantity.
- Unit price.
- Item subtotal.
- Remove item.
- Update quantity.
- Empty cart.
- Cart total.

Requirements:

- Persist cart in the database.
- Validate availability.
- Prevent negative quantities.
- Recalculate totals on the backend.
- Never trust frontend prices.
- Update the cart indicator.
- Show clear feedback.

## Checkout information

Collect:

- Recipient name.
- WhatsApp number.
- Street address.
- District.
- City.
- Province or region.
- Postal code.
- Delivery notes.

Allow customers to select a saved address.

## Shipping methods

The PRD includes:

- SiCepat BEST.
- JNE YES.
- GoSend Instant.

Support these as configurable shipping options.

However, do not claim real-time API integration or fabricate courier prices.

The current operational direction is manual shipping confirmation.

Rules:

- Customer selects an available shipping preference.
- Shipping cost can be pending confirmation.
- Admin can confirm or enter shipping cost.
- Do not silently charge an invented shipping amount.
- Display shipping status clearly.
- Admin can enter the tracking number when applicable.
- GoSend may be handled manually if no tracking API is available.

## Checkout transaction

When the customer submits checkout:

1. Validate authentication.
2. Validate cart ownership.
3. Validate products and variations.
4. Recheck stock.
5. Lock or safely recheck relevant stock records.
6. Recalculate prices.
7. Validate the delivery address.
8. Determine shipping cost according to configured rules.
9. Generate a unique order number.
10. Create the order.
11. Create order items with immutable snapshots.
12. Save the shipping address snapshot.
13. Create the initial payment record.
14. Update stock according to the selected fulfillment policy.
15. Clear or update the cart.
16. Commit the transaction.
17. Redirect to order confirmation.

Use database transactions.

Prevent duplicate orders from double-clicks or page refreshes.

If the checkout fails, rollback all related writes.

---

# 15. ORDER LIFECYCLE AND PRODUCTION TRACKING

Use a production-oriented lifecycle suitable for KNOTTED.

Internal statuses:

- `pending_payment`
- `waiting_verification`
- `knitting_queue`
- `quality_check`
- `shipped`
- `completed`
- `cancelled`

Indonesian display labels:

| Internal status | Customer-facing label |
|---|---|
| `pending_payment` | Menunggu Pembayaran |
| `waiting_verification` | Menunggu Verifikasi Admin |
| `knitting_queue` | Antrean Rajut |
| `quality_check` | Pemeriksaan QC & Kemasan |
| `shipped` | Dalam Pengiriman |
| `completed` | Selesai |
| `cancelled` | Dibatalkan |

The system must validate allowed status transitions.

Example lifecycle:

1. Customer creates an order.
2. Order enters Menunggu Pembayaran.
3. Customer uploads payment proof.
4. Order enters Menunggu Verifikasi Admin.
5. Admin verifies payment.
6. Order enters Antrean Rajut.
7. Artisan completes production.
8. Order enters Pemeriksaan QC & Kemasan.
9. Admin dispatches the order.
10. Order enters Dalam Pengiriman.
11. Customer receives the order.
12. Admin or authorized workflow marks it Selesai.

Do not mark future timeline steps as completed.

Only authorized administrators can update production statuses.

Record status history where practical.

Cancellation must follow explicit business rules.

Do not allow a customer to cancel a shipped or completed order through an unrestricted action.

---

# 16. PAYMENT SYSTEM

Implement manual payments only.

Supported methods:

1. QRIS.
2. BCA Bank Transfer.

Do not integrate an external payment gateway.

Do not simulate automatic payment verification.

## QRIS

Display the actual QRIS image configured by admin.

The image must come from Supabase Storage or the configured storage path.

Do not generate a fake QRIS image.

## Bank transfer

Display configured:

- Bank name.
- Account number.
- Account holder.

Provide copy-to-clipboard functionality.

Do not hardcode fictional account details.

## Payment proof upload

Reference:

`knotted_konfirmasi_unggah_bukti_pembayaran`

Collect:

- Order number.
- Payment method.
- Amount.
- Sender name.
- Sender bank where applicable.
- Transfer date and time.
- Payment proof.
- Optional note.

Accepted formats:

- JPG.
- JPEG.
- PNG.
- PDF.

Maximum size:

5MB.

Validate MIME type and content on the server.

Store payment proof in a private Supabase Storage bucket.

Store the object path in the database.

## Payment statuses

Use consistent internal values:

- `unpaid`
- `waiting_verification`
- `paid`
- `rejected`

If existing data uses `verified`, reconcile it safely with the chosen application convention.

Do not blindly rename existing data.

## Admin verification

Admin must be able to:

- View payment submissions.
- View private proof securely.
- Approve payment.
- Reject payment.
- Provide mandatory rejection reason.
- Record verifier ID.
- Record verification timestamp.
- View payment history.

Uploading proof must never automatically mark a payment as paid.

When rejected:

- Customer sees the rejection reason.
- Customer can submit corrected proof.
- Previous payment history is preserved where practical.

Payment approval must update payment and order status consistently.

---

# 17. ORDER CONFIRMATION AND CUSTOMER ORDER DETAIL

References:

- `knotted_order_confirmation`
- `knotted_detail_pesanan_pelanggan`

## Order confirmation

Display:

- Order number.
- Order summary.
- Products.
- Variations.
- Quantity.
- Subtotal.
- Shipping cost or pending shipping confirmation.
- Total.
- Payment method.
- Payment status.
- Next steps.
- WhatsApp contact.
- View order detail.
- Return to catalog.

The order must already exist in the database.

Never display hardcoded sample order numbers.

## Order details

Display:

- Order number.
- Order date.
- Ordered products.
- Product snapshots.
- Variations.
- Quantity.
- Payment summary.
- Payment proof status.
- Shipping address snapshot.
- Shipping method.
- Tracking number.
- Order timeline.
- Current order status.
- WhatsApp support.
- Invoice download if implemented.

Customers may only view their own orders.

Do not expose another customer's order by changing the URL ID.

---

# 18. WHATSAPP INTEGRATION

Implement a reusable `WhatsAppService`.

The administrator phone number must be configurable.

Suggested environment variable:

```env
WHATSAPP_ADMIN_NUMBER=
```

Alternatively, use protected store settings.

Do not hardcode the number into multiple Blade files.

Requirements:

- Normalize phone number.
- Generate valid `wa.me` links.
- URL-encode messages.
- Use real application data.
- Avoid unnecessary sensitive information.
- Handle missing phone configuration gracefully.

## WhatsApp entry points

- Product detail: Ask Artisan.
- Order confirmation: Continue to WhatsApp.
- Order details: Contact Admin.
- Custom order: Discuss Request.
- FAQ: Chat with Concierge.
- Contact page: WhatsApp Support.
- Admin shipping update: Prepare customer message.

## Example message

```text
Hello KNOTTED Admin,

I would like to confirm my order.

Order Number: {order_number}
Items: {items}
Total: {total}
Payment Method: {payment_method}

Please provide the next instructions.

Thank you.
```

Messages must use real data.

WhatsApp Click-to-Chat opens a prepared conversation; it does not automatically send the message.

Do not claim automatic WhatsApp notifications unless a real authorized messaging API is implemented.

If the WhatsApp number is missing, show a configuration warning instead of generating an invalid link.

---

# 19. BESPOKE CUSTOM ORDER PORTAL

Reference:

`knotted_bespoke_custom_order`

This is a core KNOTTED feature.

Customers must be able to request customized headwear.

Use a four-step flow.

## Step 1 — Pathway

Customer chooses:

- Customize an existing KNOTTED product.
- Commission an original headwear design.

## Step 2 — Fit and Fibers

Collect:

- Existing product where applicable.
- Headwear type.
- Head circumference in centimeters.
- Size preference.
- Base pattern.
- Preferred color.
- Yarn/material preference.
- Quantity.
- Target budget.
- Additional notes.

Suggested material preferences:

- Organic Peruvian Cotton.
- Botanical Dyed Mohair.
- Recycled Silk Ribbon.

Treat these as selectable preferences, not guaranteed stock or verified sourcing claims.

## Step 3 — Visual references

Allow multiple reference files.

Implement:

- Drag and drop.
- File preview.
- Remove selected image.
- Upload progress where practical.
- File validation.
- Private Supabase Storage upload.

## Step 4 — Review

Show a summary of the request.

Customer confirms and submits.

## Custom request statuses

Use a consistent internal lifecycle:

- `pending`
- `under_review`
- `quoted`
- `accepted`
- `rejected`
- `cancelled`
- `converted_to_order`

Map any existing status values safely.

## Custom request number

Suggested format:

`#CR-YYYY-XXX`

The number must be generated on the backend and unique.

## Admin review

Admin can:

- View requests.
- View reference images.
- Review measurements.
- Add internal notes.
- Request clarification.
- Set quotation price.
- Set production days.
- Submit offer.
- Reject with reason.

Suggested production duration:

5–10 days, but the admin must set the actual estimate.

## Customer offer response

Customers can:

- View offer.
- Accept offer.
- Reject offer.
- View agreed price.
- Contact administrator.

When accepted:

- Record acceptance timestamp.
- Preserve accepted price.
- Preserve quotation history.
- Prevent duplicate acceptance.
- Create a formal order or conversion record transactionally.
- Generate the appropriate order number.
- Provide WhatsApp consultation.

Do not create duplicate orders if the customer submits the acceptance action multiple times.

---

# 20. ADMIN MANAGEMENT CONSOLE

References:

- `knotted_admin_dashboard_overview`
- `knotted_admin_detail_kelola_pesanan`

The admin interface must follow the approved Stitch visual identity.

## Admin navigation

Include:

- Dashboard.
- Products.
- Categories.
- Inventory.
- Customers.
- Orders.
- Payments.
- Custom Requests.
- Reviews if implemented.
- Store Settings.
- Logout.

## 20.1 Dashboard

Display actual database statistics:

- Total orders.
- Confirmed revenue.
- Pending payments.
- Active products.
- Total customers.
- Pending custom requests.
- Recent orders.
- Recent custom requests.
- Inventory alerts.
- Sales overview.

Charts must use actual database data.

Do not show fabricated metrics.

## 20.2 Product management

Implement:

- Product listing.
- Search.
- Filtering.
- Pagination.
- Create.
- Edit.
- Deactivate/delete safely.
- Product images.
- Image ordering.
- Price.
- SKU.
- Description.
- Category.
- Ready-to-ship or made-to-order.
- Lead time.
- Featured status.
- Product variations.
- Stock.

Historical order records must remain intact when products are edited or deactivated.

## 20.3 Category management

Implement:

- Listing.
- Create.
- Edit.
- Image upload.
- Active/inactive status.
- Safe deletion.

Prevent deletion when products still depend on a category unless a safe reassignment exists.

## 20.4 Inventory management

Display:

- Product.
- Variation.
- Current stock.
- Availability.
- Low-stock status.
- Stock adjustment.
- Inventory history where practical.

Never allow accidental negative stock.

## 20.5 Customer management

Implement:

- Customer listing.
- Search.
- Customer details.
- Account status.
- Order history.
- Custom request history.

Never display customer password hashes.

## 20.6 Order management

Implement:

- Order listing.
- Search by order number.
- Status filters.
- Payment filters.
- Order detail.
- Customer information.
- Product details.
- Payment details.
- Shipping information.
- Internal notes.
- Status updates.
- Cancellation handling.

## 20.7 Payment management

Implement:

- Payment list.
- Waiting verification queue.
- Payment detail.
- Secure proof viewing.
- Approve.
- Reject with mandatory reason.
- Verification history.

## 20.8 Custom request management

Implement:

- Request list.
- Search and filters.
- Request detail.
- Reference image viewer.
- Customer information.
- Quotation.
- Production estimate.
- Internal notes.
- Acceptance/rejection history.

## 20.9 Store settings

Implement configurable:

- Store name.
- Logo.
- Contact email.
- WhatsApp number.
- QRIS image.
- Bank name.
- Account number.
- Account holder.
- Payment instructions.
- Shipping configuration.
- Store availability.

Protect all settings with admin authorization.

---

# 21. ADMIN ORDER DETAIL

Reference:

`knotted_admin_detail_kelola_pesanan`

This page must be fully functional.

Display:

- Order number.
- Order date.
- Order status.
- Customer details.
- Ordered products.
- Variations.
- Quantities.
- Payment details.
- Payment proof.
- Shipping address.
- Shipping method.
- Shipping cost.
- Tracking number.
- Internal notes.
- Order history.

Admin actions:

- Update order status.
- Verify payment.
- Reject payment.
- Add internal notes.
- Contact customer through WhatsApp.
- Print or download invoice.
- Enter shipping information.
- Update tracking information.
- Prepare shipping notification.

Enforce status transitions on the backend.

Examples:

- An unpaid order cannot move directly to shipped.
- Unverified payment cannot be treated as paid.
- Completed orders cannot silently return to production.
- Shipped orders cannot be cancelled through an unrestricted action.

---

# 22. SUPABASE STORAGE

Use Supabase Storage.

Recommended buckets:

## Public bucket: `product-images`

For:

- Product images.
- Category images.
- Public brand assets.

## Private bucket: `payment-proofs`

For:

- Payment proof.
- Transfer slips.
- Payment documents.

## Private bucket: `custom-order-references`

For:

- Inspiration photos.
- Sketches.
- Custom-order reference images.

Requirements:

- Validate file extension.
- Validate MIME type.
- Validate file size.
- Validate upload authorization.
- Store object paths in the database.
- Use signed URLs for private files.
- Enforce ownership checks.
- Do not expose service-role credentials.
- Do not use unrestricted public URLs for private files.

If a bucket is missing, document and configure it safely.

Do not delete existing storage objects without checking their database references.

---

# 23. SHIPPING AND DELIVERY BUSINESS RULES

Supported preferences:

- SiCepat BEST.
- JNE YES.
- GoSend Instant.

The application must distinguish between:

1. Customer's selected shipping preference.
2. Confirmed courier.
3. Confirmed shipping cost.
4. Tracking or delivery reference.

Do not assume a courier selection means its price has been calculated.

Do not invent:

- Shipping rates.
- Delivery dates.
- Live tracking.
- Courier API integration.

Unless a real API is configured, the administrator manages shipping confirmation manually.

Admin can:

- Confirm courier.
- Enter shipping cost.
- Update shipping status.
- Enter tracking number or delivery reference.
- Contact customer through WhatsApp.

If shipping cost is pending, clearly indicate it.

The application must not charge an unconfirmed shipping amount.

---

# 24. LOCALIZATION AND CURRENCY

Primary operational language:

Indonesian.

Editorial headings may use English.

Keep the language consistent within each page.

Use Indonesian Rupiah formatting.

Examples:

- `Rp 125.000`
- `Rp 1.250.000`

Rules:

- No unnecessary decimal places.
- Use Indonesian thousand separators.
- Store monetary values as numeric database values.
- Never store formatted currency strings as the actual financial amount.
- Use a reusable formatting helper or service.

---

# 25. SECURITY AND DATA PROTECTION

Security is mandatory.

Implement:

- CSRF protection.
- Server-side validation.
- Authentication middleware.
- Admin authorization.
- Customer ownership checks.
- Policies where appropriate.
- Password hashing.
- Secure password reset.
- Mass-assignment protection.
- Secure file upload validation.
- Private storage access.
- Rate limiting.
- Safe redirects.
- Proper error handling.
- Session security.
- Database transactions.
- Unique constraints.
- Server-side price calculations.

Never trust frontend-submitted:

- Prices.
- Totals.
- Roles.
- User IDs.
- Payment statuses.
- Verification statuses.
- Stock values.
- Shipping charges.

Never expose:

- `.env`
- Database passwords.
- Supabase service-role keys.
- Private API credentials.
- Payment credentials.
- Customer private information.

Review `.gitignore` and `.env.example`.

The example environment file must contain placeholders only.

Do not commit SQL exports containing private information or credentials.

---

# 26. UI INTERACTIONS AND APPLICATION STATES

Every page must implement relevant states.

## Loading states

- Button loading.
- Form submission loading.
- Product loading.
- Search loading.
- File upload progress.

## Empty states

- Empty cart.
- No products.
- No search results.
- No orders.
- No custom requests.
- No payment history.
- No notifications.

## Error states

- Invalid forms.
- Missing products.
- Out-of-stock variations.
- Database errors.
- Unauthorized access.
- Invalid upload.
- Rejected payment.
- Invalid status transition.

## Success states

- Registration.
- Login.
- Cart update.
- Order creation.
- Payment proof upload.
- Payment verification.
- Profile update.
- Custom request submission.
- Offer acceptance.

## Confirmation dialogs

Use branded confirmations for:

- Product deletion.
- Category deletion.
- Cart item removal.
- Order cancellation.
- Payment rejection.
- Custom request rejection.

Do not use browser alerts as the main interface.

All buttons and links must perform their advertised actions.

---

# 27. ROUTE ORGANIZATION

Use named routes and logical route groups.

Suggested public routes:

- `/`
- `/shop`
- `/categories/{slug}`
- `/search`
- `/products/{slug}`
- `/about`
- `/faq`
- `/contact`
- `/custom-order`

Authentication:

- `/login`
- `/register`
- `/forgot-password`
- `/reset-password`

Customer:

- `/account/dashboard`
- `/account/orders`
- `/account/orders/{id}`
- `/account/orders/{id}/payment`
- `/account/custom-requests`
- `/account/custom-requests/{id}`
- `/account/profile`
- `/account/addresses`
- `/account/wishlist`
- `/account/reviews`

Admin:

- `/admin/dashboard`
- `/admin/products`
- `/admin/categories`
- `/admin/inventory`
- `/admin/orders`
- `/admin/orders/{id}`
- `/admin/payments`
- `/admin/custom-requests`
- `/admin/customers`
- `/admin/settings`

Adapt route names and structure to the existing project.

Avoid route collisions.

Use appropriate HTTP methods.

Protect every sensitive endpoint.

Do not place complicated business logic inside route closures.

---

# 28. OPTIONAL FEATURES

The following are valuable but must not delay core business functionality:

- Wishlist.
- Product reviews.
- Invoice PDF.
- Admin activity log.
- Stock adjustment history.
- Contact message management.
- Dashboard sales charts.
- Customer notifications.

Implement them when the existing architecture supports them cleanly.

Do not create fake functionality merely to fill a dashboard.

---

# 29. TESTING AND QUALITY ASSURANCE

Do not consider the application complete merely because the pages render.

Run actual tests.

## Backend testing

Test:

1. Registration.
2. Login.
3. Logout.
4. Role authorization.
5. Product retrieval.
6. Category filtering.
7. Search.
8. Product variations.
9. Stock validation.
10. Cart operations.
11. Checkout.
12. Order number generation.
13. Duplicate checkout prevention.
14. Order snapshots.
15. Payment proof upload.
16. Admin payment approval.
17. Admin payment rejection.
18. Payment resubmission.
19. Order status transitions.
20. Custom request submission.
21. Custom quotation.
22. Customer quotation acceptance.
23. WhatsApp link generation.
24. Profile updates.
25. Address management.
26. File authorization.
27. Customer data isolation.

## Database testing

Check:

- Relationships.
- Foreign keys.
- Unique constraints.
- Nullability.
- Indexes.
- Price snapshots.
- Address snapshots.
- Transaction rollback.
- Stock integrity.
- Duplicate prevention.
- Status consistency.

## UI testing

Compare all 18 Laravel pages with their original Stitch references.

Check:

- Header composition.
- Navigation.
- Typography.
- Main content width.
- Spacing.
- Image proportions.
- Card layout.
- Buttons.
- Forms.
- Background colors.
- Borders.
- Footer.
- Mobile layout.

Test:

- 320px.
- 375px.
- 390px.
- 430px.
- 768px.
- 1024px.
- 1280px.
- 1440px.

Fix:

- Broken layouts.
- Horizontal overflow.
- Overlapping components.
- Broken images.
- Dead buttons.
- Unreadable typography.
- Missing responsive navigation.
- JavaScript errors.

## Laravel commands

Run relevant checks:

```bash
php artisan route:list
php artisan test
php artisan view:cache
npm run build
```

Use:

```bash
php artisan migrate:status
```

only after checking database connectivity and migration compatibility.

Use:

```bash
php artisan optimize:clear
```

when appropriate during development.

Do not run destructive migration commands.

Record the actual result of each command.

Do not claim a test passed if it was not executed.

---

# 30. DOCUMENTATION REQUIREMENTS

Create or update these files.

## `PROJECT_AUDIT.md`

Contains the initial codebase and database audit.

## `IMPLEMENTATION_PROGRESS.md`

Track:

- Completed features.
- Completed pages.
- Backend progress.
- Database progress.
- Known issues.
- Pending tasks.
- Test results.

Update it continuously.

## `DATABASE_DOCUMENTATION.md`

Document:

- Tables.
- Columns.
- Relationships.
- Constraints.
- Indexes.
- Storage buckets.
- Migration decisions.
- Schema differences.

## `SETUP_GUIDE.md`

Document:

- PHP requirements.
- Composer.
- Node.js.
- Laravel setup.
- Environment configuration.
- Supabase configuration.
- Storage setup.
- Database setup.
- Local development commands.
- Troubleshooting.

## `ADMIN_GUIDE.md`

Document:

- Admin access requirements.
- Product management.
- Category management.
- Inventory.
- Order management.
- Payment verification.
- Custom quotation.
- Store settings.

## `CUSTOMER_GUIDE.md`

Document:

- Registration.
- Login.
- Product browsing.
- Ordering.
- Payment.
- Payment proof upload.
- Order tracking.
- Custom-order submission.

Do not include private credentials in documentation.

---

# 31. IMPLEMENTATION PHASES

Execute the project systematically.

Do not generate the entire application in one uncontrolled operation.

Complete each phase, verify it, and proceed.

## PHASE 1 — Project and design audit

- Inspect existing project.
- Inspect all 18 Stitch references.
- Inspect `DESIGN.md`.
- Inspect database schema.
- Inspect migrations.
- Inspect routes.
- Inspect models.
- Inspect controllers.
- Inspect storage.
- Create `PROJECT_AUDIT.md`.

Deliverable: Accurate project audit and implementation plan.

## PHASE 2 — Database and backend architecture

- Reconcile existing schema.
- Fix relationships.
- Prepare safe migrations.
- Review models.
- Organize controllers.
- Organize services.
- Organize routes.
- Review authentication.
- Review authorization.
- Review Supabase Storage.

Deliverable: Safe and consistent backend foundation.

## PHASE 3 — Shared design system

- Implement design tokens.
- Load typography.
- Implement public layout.
- Implement customer layout.
- Implement admin layout.
- Implement auth layout.
- Implement navigation.
- Implement footer.
- Implement reusable components.
- Implement responsive foundations.

Deliverable: Shared visual foundation.

## PHASE 4 — Public pages

Implement:

- Homepage.
- Catalog.
- Category listing.
- Search results.
- Product detail.
- About.
- FAQ.
- Contact.
- 404.

Deliverable: Complete public browsing experience.

## PHASE 5 — Authentication and customer accounts

Implement:

- Login.
- Register.
- Forgot password.
- Reset password.
- Customer dashboard.
- Profile.
- Addresses.
- Orders.
- Order details.
- Custom request history.
- Payment history.

Deliverable: Functional customer account system.

## PHASE 6 — Product variations, cart, and checkout

Implement:

- Product variations.
- Color and size selection.
- Stock management.
- Cart persistence.
- Quantity updates.
- Address selection.
- Checkout.
- Transactional order creation.
- Order number generation.
- Order confirmation.

Deliverable: Functional ordering system.

## PHASE 7 — Payment and shipping

Implement:

- QRIS display.
- Bank transfer instructions.
- Payment proof upload.
- Private storage.
- Admin payment verification.
- Rejection and resubmission.
- Shipping configuration.
- Shipping cost confirmation.
- Tracking information.
- Order status timeline.

Deliverable: Functional payment and delivery workflow.

## PHASE 8 — Custom order

Implement:

- Customize existing product.
- Original headwear request.
- Four-step form.
- Reference image uploads.
- Request submission.
- Admin review.
- Quotation.
- Production estimate.
- Customer acceptance/rejection.
- Order conversion.
- WhatsApp consultation.

Deliverable: Complete bespoke-order workflow.

## PHASE 9 — Admin console

Implement:

- Dashboard.
- Product CRUD.
- Category CRUD.
- Inventory.
- Customer management.
- Order management.
- Payment verification.
- Custom request management.
- Store settings.

Deliverable: Functional administration system.

## PHASE 10 — Final QA and polish

- Test all routes.
- Test all workflows.
- Check database consistency.
- Check storage security.
- Check authorization.
- Compare every Stitch screen.
- Test responsive layouts.
- Fix visual differences.
- Fix errors.
- Update documentation.

Deliverable: Stable, polished, presentation-ready application.

Do not stop after completing the first phases.

Continue until all feasible requirements have been implemented and tested.

---

# 32. VISUAL ACCEPTANCE CRITERIA

For each implemented Stitch screen, compare the actual Laravel result against the original screenshot.

Evaluate:

1. Header height and composition.
2. Navigation spacing.
3. Typography and weights.
4. Main content width.
5. Section spacing.
6. Image aspect ratios.
7. Card layout.
8. Button proportions.
9. Background colors.
10. Border radii.
11. Footer composition.
12. Responsive behavior.

A page is not complete merely because it uses the correct colors.

It must preserve the recognizable composition and visual identity of the original reference.

Do not introduce unnecessary visual changes.

If business functionality requires a visual adjustment, preserve the design language and document the reason.

---

# 33. FINAL ACCEPTANCE CHECKLIST

The application is considered complete only when all applicable requirements have been implemented and verified.

## Design

- [ ] All 18 Stitch screens implemented.
- [ ] Design system applied consistently.
- [ ] Product scope restricted to headwear.
- [ ] Desktop responsive.
- [ ] Mobile responsive.
- [ ] No horizontal overflow.
- [ ] No broken layouts.
- [ ] No dead buttons.
- [ ] No generic redesign replacing Stitch references.

## Public functionality

- [ ] Homepage.
- [ ] Catalog.
- [ ] Category filters.
- [ ] Search.
- [ ] Product detail.
- [ ] Product gallery.
- [ ] Product variations.
- [ ] About.
- [ ] FAQ.
- [ ] Contact.
- [ ] 404.

## Authentication

- [ ] Registration.
- [ ] Login.
- [ ] Logout.
- [ ] Forgot password.
- [ ] Reset password.
- [ ] Role-based redirection.
- [ ] Admin authorization.
- [ ] Customer authorization.

## Customer features

- [ ] Dashboard.
- [ ] Profile.
- [ ] Addresses.
- [ ] Cart.
- [ ] Checkout.
- [ ] Order confirmation.
- [ ] Order history.
- [ ] Order details.
- [ ] Payment history.
- [ ] Payment proof upload.
- [ ] Custom request history.

## Business functionality

- [ ] Product variation selection.
- [ ] Stock validation.
- [ ] Unique order number.
- [ ] Price snapshots.
- [ ] Address snapshots.
- [ ] Manual QRIS.
- [ ] Manual bank transfer.
- [ ] Payment verification.
- [ ] Payment rejection.
- [ ] Payment resubmission.
- [ ] WhatsApp integration.
- [ ] Custom order submission.
- [ ] Custom quotation.
- [ ] Custom offer acceptance.
- [ ] Order status tracking.
- [ ] Shipping confirmation.
- [ ] Tracking information.

## Admin functionality

- [ ] Dashboard.
- [ ] Product CRUD.
- [ ] Category CRUD.
- [ ] Inventory.
- [ ] Customer management.
- [ ] Order management.
- [ ] Payment verification.
- [ ] Custom request management.
- [ ] Store settings.

## Technical quality

- [ ] Laravel MVC architecture.
- [ ] Supabase PostgreSQL connection.
- [ ] Supabase Storage integration.
- [ ] Private file access.
- [ ] No exposed secrets.
- [ ] No broken routes.
- [ ] No duplicate orders.
- [ ] No negative stock.
- [ ] No unauthorized data access.
- [ ] No critical JavaScript errors.
- [ ] No critical Laravel errors.
- [ ] Tests executed.
- [ ] Documentation updated.

---

# 34. IMPORTANT WORKING RULES

Follow these rules throughout development.

1. Work on the existing KNOTTED project.
2. Read relevant files before modifying them.
3. Inspect all available Stitch references.
4. Preserve existing working functionality.
5. Do not overwrite working code without understanding it.
6. Do not create duplicate controllers, models, migrations, or tables unnecessarily.
7. Do not switch technology stacks.
8. Do not introduce unrelated products.
9. Do not use fake data as a replacement for backend functionality.
10. Do not create dead buttons.
11. Do not hardcode credentials or private configuration.
12. Do not expose Supabase service-role keys.
13. Do not run destructive database operations.
14. Do not trust frontend-submitted financial values.
15. Keep customer data isolated.
16. Enforce admin permissions on the backend.
17. Use transactions for important order operations.
18. Validate all user input.
19. Test each implementation phase.
20. Update `IMPLEMENTATION_PROGRESS.md`.
21. Report real errors instead of hiding them.
22. Do not mark unfinished functionality as completed.
23. Do not stop after implementing the visual frontend.
24. Do not stop after creating an audit document.
25. Do not ask unnecessary questions when the answer already exists in this prompt.
26. Ask for approval before destructive operations.
27. Preserve existing database records.
28. Keep code maintainable and understandable.

## Missing configuration behavior

If the administrator WhatsApp number is missing:

- Use a configuration placeholder.
- Do not invent a phone number.

If bank information is missing:

- Create configurable settings.
- Do not invent account information.

If the QRIS image is missing:

- Show a clear configuration state.
- Do not generate a fake QRIS.

If product photography is missing:

- Use existing approved assets.
- Otherwise use clearly marked temporary placeholders.

If the business address is missing:

- Do not invent one.

If shipping price is missing:

- Require administrator confirmation.

If email delivery is not configured:

- Document the SMTP configuration requirement.

If a payment gateway is missing:

- Retain the manual payment workflow.

---

# 35. REQUIRED FINAL DELIVERY REPORT

After implementation, provide a clear report.

## A. Completed features

List implemented modules and functionality.

## B. Stitch implementation

List all 18 screens and their implementation status.

Clearly identify any visual differences that remain.

## C. Backend implementation

List:

- Controllers.
- Models.
- Services.
- Middleware.
- Routes.
- Business workflows.

## D. Database changes

List:

- New migrations.
- Updated migrations.
- Tables.
- Columns.
- Relationships.
- Constraints.
- Indexes.
- Schema reconciliation decisions.

Do not claim a database operation succeeded unless it was executed and verified.

## E. Supabase Storage

Report:

- Buckets used.
- Public/private access.
- Upload implementation.
- Signed URL implementation.
- Remaining manual configuration.

## F. Security

Report:

- Authentication.
- Authorization.
- Ownership checks.
- File access protection.
- Validation.
- Secrets handling.

## G. Test results

Separate:

- Passed.
- Failed.
- Not executed.
- Blocked by missing configuration.

List the actual commands executed.

## H. Remaining configuration

Identify requirements such as:

- WhatsApp number.
- QRIS image.
- BCA account information.
- SMTP settings.
- Supabase Storage configuration.
- Production environment configuration.

## I. Run instructions

Provide the correct commands for running the project locally using the existing Windows and FlyEnv environment.

Never claim the application is production-ready if important security checks, workflows, or tests remain incomplete.

---

# FINAL DIRECTIVE

Transform the existing KNOTTED Laravel project into a complete, polished, secure, and functional premium handmade headwear e-commerce platform.

The supplied Google Stitch designs define the visual direction.

The KNOTTED PRD defines the business requirements.

The existing Laravel project defines the starting codebase.

Laravel 12 and PHP 8.5 define the backend environment.

Blade defines the frontend rendering architecture.

Supabase PostgreSQL defines the database.

Supabase Storage manages product images and private customer uploads.

Laravel handles business logic, authentication, authorization, validation, transactions, and database operations.

The final result must combine:

- Accurate Stitch visual implementation.
- Complete public shopping experience.
- Functional customer accounts.
- Product variations and inventory.
- Cart and checkout.
- Unique order generation.
- Secure manual payment verification.
- Production-oriented order tracking.
- Custom headwear commissions.
- Admin management console.
- WhatsApp Click-to-Chat.
- Responsive design.
- Secure database and storage access.
- Maintainable Laravel architecture.

**Do not merely reproduce screenshots. Build the real application behind those screenshots.**

**Do not merely create a plan. Implement the actual application.**

Start by auditing the existing project and every available design reference. Then work through the implementation phases, test the real workflows, fix errors, and update the documentation until all feasible acceptance criteria are satisfied.