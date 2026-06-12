# Plantsware Project Functionality Reference

This reference documents the complete functionality, views, routes, forms, scripts, and styling rules of the Plantsware project. It ensures that 100% of the existing backend, frontend, and interactive behaviors are preserved exactly while we perform visual and styling upgrades.

---

## 1. Project Overview & Tech Stack
* **Framework**: Laravel 11.x
* **Database**: MySQL/MariaDB (port `3307`, database `whatelse_plantsware`)
* **Styling & Icons**: Bootstrap 4.x (main styles in `style.css`, responsive adjustments in `responsive.css`, customized rules in `customized.css`), Font Awesome 6.4.0
* **Animations**: Animate.css, AOS (required for all sections)
* **Slider/Carousel Libraries**: Swiper 9, Slick Carousel, Owl Carousel

---

## 2. Core Frontend Routes & Page Mapping

| Route Name | URL Pattern | View File | Key Content & Dynamic Bindings |
| :--- | :--- | :--- | :--- |
| `home` | `/` | `view.index` | Category carousel, main slider (`$sliders`), service highlights, product sliders (New Arrivals, Garden, Aquarium, Natural), ad banner, testimonials carousel (`$testimonials`), recent blogs (`$blogs`), add-to-cart alert. |
| `products.index` | `/products` | `view.product` | Product catalog with filters (price, categories), sorting, paginated search results, and dynamic product cards. |
| `product.show` | `/product/{slug}` | `view.product` | Individual product details, zoomable images, review listings, rating stars, dynamic pricing, and variants. |
| `categories` | `/categories` | `view.productcategory` | Main category listings with visual cards. |
| `category.show` | `/category/{slug}` | `view.productcategory` | Category-specific product lists. |
| `subcategory.show`| `/sub-category/{slug}`| `view.subcategory` | Subcategory-specific product lists. |
| `blog.index` | `/blogs` | `view.blog` | Excerpts of all active articles with pagination. |
| `blog.show` | `/blog/{slug}` | `view.singleblog` | Full article reading view with categories list. |
| `blog.categories` | `/blog-categories` | `view.blogcategory` | Category-wise blog browsing. |
| `cart.index` | `/cart` | `view.cart` | Shopping cart items, quantity modifiers, subtotals, GST calculation, shipping rates, and proceed button. |
| `wishlist.index` | `/wishlist` | `view.wishlist` | Logged-in/session wishlist items. |
| `checkout.index` | `/checkout` | `view.checkout.index` | Checkout forms, address inputs, default selectors, shipping option calculation, total price summary. |
| `combo_packs.*` | `/combo-packs/*` | `view.combo_packs` | Pre-made bundle packages. |
| `combo-builder.*` | `/combo-builder` | `view.combo-builder` | Custom builder to mix and match items for discount slab pricing. |
| `user.dashboard` | `/user/dashboard` | `view.userdashboard` | Profile update form, address manager, order history, cancel/return order hooks, reviews generator. |
| `login` | `/login` | `view.login` | OTP-based authentication forms, phone number verify, name setter, and Google OAuth redirection. |

---

## 3. Dynamic PHP Outputs & Blade Bindings
We must preserve the exact PHP syntax, variables, loops, and conditional outputs. These include:
* **Header details**:
  * `$headerFooter->home_meta_title`
  * `$headerFooter->header_title`
  * Category iteration (`$headerCategories`)
  * Cart and Wishlist quantities: `{{ $wishlistCount }}` and `{{ $cartCount }}`
* **Home Page**:
  * Categories loop: `@forelse($categories as $category)`
  * Home Sliders loop: `@foreach($sliders as $slider)`
  * Product highlights loop: `@forelse($newArrivals as $product)`, `@forelse($gardenProducts as $product)`, etc.
  * Ad Banner bindings: `$adBanner->title`, `$adBanner->content`, `$adBanner->extra_content['button_text']`
  * Testimonials loop: `@forelse(($testimonials ?? []) as $testimonial)`
  * Blogs loop: `@forelse(($blogs ?? []) as $blog)`
* **Product Card Component (`view.partials.product-card`)**:
  * Product images, name, prices, ratings, and stock status.
  * Form actions for cart and wishlist: `route('cart.add', ...)` and `route('wishlist.add', ...)`

---

## 4. Key Interactive JavaScript & AJAX Functions
Do not modify the scripts or rename element IDs/classes referenced in:
* **Live Autocomplete Search (`liveSearchInput`, `searchDropdown`, `liveSearchBtn`)**:
  * Uses fetch call to `route('search.autocomplete')` with custom keyboard event-listener navigation.
* **Add to Cart & Wishlist Alert**:
  * Form event-listener triggering a fixed alert container (`#cart-alert-container` and `#cart-added-alert`).
* **Mobile Sidebar Menu Navigation**:
  * Handled via `#menuToggle` and `#closeMenu` trigger events that toggle the `.show` class on `.main-menu`.
  * Mobile category tree collapse using `.main-menu .dropdown > a` toggle listeners.
* **SweetAlert2 Notifications**:
  * Standardized popup dialog alerts for system success/error messages.

---

## 5. Visual Standards & Style Constraints (`RULE[user_global]`)
* **CSS Files Location**:
  * Core styles: `public/assets/css/style.css`
  * Responsive rules: `public/assets/css/responsive.css`
  * Customized overlays: `public/assets/css/customized.css`
  * *No page-specific stylesheet files or inline/internal `<style>` blocks.*
* **Grid & Structure**:
  * Section padding: strictly **70px spacing** between main content blocks.
  * Responsive Range: **320px – 1920px+** (supporting DPR values and display zooms).
  * Web margins: **30px padding** on left and right edges.
  * Card height/width: uniform sizes for all product cards in standard layouts.
  * Footer: columns must occupy equal width.
* **Typography**:
  * Standardize font sizes, families, and colors across all pages.
  * Nav Menu: sentence case only (e.g., *Garden Products*, *Build a Combo*).
* **Images**:
  * File format: strictly `.webp` only.
  * File compression limit: max **250 KB**.
  * Attribute: descriptive `alt` content required for LCP/accessibility.
  * Rendering: `loading="eager"` above-the-fold, `loading="lazy"` below-the-fold.
  * Fixed dimension rules using responsive CSS and `object-fit: cover`.
* **Animations**:
  * Integrate AOS animations across all primary sections.
* **Forms & Verification**:
  * All input forms must embed Google reCAPTCHA.
* **Footer Copyright & Developer Label**:
  * Auto-updated year via JS.
  * Developer signature link: *Developed by Jayam Web Solutions* hyperlinked exactly to `https://jayamwebsolutions.com/web-design-company-in-chennai.php`
