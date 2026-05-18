# ProKitchen Equipment Co.

**Premium B2B WooCommerce store for commercial restaurant kitchen equipment.**  
Black/gold industrial aesthetic. Custom product specs, quote request system, financing pages. Built to ship real orders.

## Stack
- WordPress 6.5 + WooCommerce 8.x
- Custom theme: `prokitchen` (zero dependencies — no page builder required)
- Custom plugin: `prokitchen-core` (product specs meta box, quote CPT, AJAX forms)
- Docker Compose (WordPress + MySQL 8 + phpMyAdmin)
- WP-CLI for automated setup

## Local Development

### Prerequisites
- Docker Desktop
- Git

### Start the site

```bash
# Clone
git clone https://github.com/MavericK1001/ProKitchen-Equipment.git
cd "ProKitchen Equipment"

# Run setup (installs WordPress, WooCommerce, 15 products)
chmod +x setup.sh && bash setup.sh
```

### URLs
| URL | Description |
|-----|-------------|
| http://localhost:8080 | Site frontend |
| http://localhost:8080/wp-admin | WordPress admin |
| http://localhost:8080/shop | Shop (all 15 products) |
| http://localhost:8081 | phpMyAdmin |

**Admin credentials:** `admin` / `ProKitchen2024!`

### Daily workflow

```bash
# Start containers
docker-compose up -d

# Stop containers
docker-compose down

# Full reset (deletes database)
docker-compose down -v && bash setup.sh

# WP-CLI commands
docker exec -u www-data prokitchen_wp wp [command]
```

## Project Structure

```
ProKitchen Equipment/
├── docker-compose.yml          # WordPress + MySQL + phpMyAdmin
├── .env                        # Credentials (not committed to git)
├── setup.sh                    # One-command automated setup
└── wp-content/
    ├── themes/
    │   └── prokitchen/         # Custom theme
    │       ├── style.css           # Theme header
    │       ├── functions.php       # Theme setup, WC support, helpers
    │       ├── header.php          # Topbar + main header + mobile nav
    │       ├── footer.php          # CTA strip + footer columns
    │       ├── front-page.php      # Home page template
    │       ├── page.php            # Default page
    │       ├── index.php           # Fallback
    │       ├── assets/
    │       │   ├── css/main.css        # Full theme styles (~800 lines)
    │       │   ├── css/woocommerce.css # WC overrides (shop, product, cart)
    │       │   └── js/main.js          # Mobile nav, tabs, quote modal, calc
    │       ├── woocommerce/
    │       │   ├── single-product.php  # Custom product page with specs/tabs
    │       │   └── archive-product.php # Shop page with sidebar filters
    │       └── templates/
    │           ├── financing.php       # Financing calculator + apply form
    │           ├── delivery.php        # Delivery tiers + timeline
    │           └── contact.php         # Contact form + business info
    └── plugins/
        └── prokitchen-core/        # Custom plugin
            └── prokitchen-core.php     # Specs metabox, quote CPT, AJAX handlers
```

## Products (15 total)

| Category | Products |
|----------|----------|
| Commercial Refrigeration | True T-49-HC, Atosa MBF8507GR, True T-23-HC, Atosa MBF8503GR, Cooltech 4-Door |
| Ovens & Ranges | Vulcan VC44GD, Imperial 6-Burner Range, Blodgett DFG-100 Double Stack |
| Prep Tables | Atosa MSF8307GR, True TSSU-60-24M-B, Cooltech 93" Mega Top |
| Stainless Work Tables | 24×60 18-Gauge, 30×72 16-Gauge with Undershelf |
| Display Cases | True GDM-49, Federal Industries SL3660-B |

## Features

- **Custom product specs** — Brand, Power, Capacity, Dimensions, Weight, Certifications (meta box in WP admin)
- **Quote request system** — Modal on product pages, AJAX submit, email notification, stored as CPT
- **Financing calculator** — JavaScript payment estimator on the Financing page
- **Shop filters** — By category, price range, and brand attribute
- **Financing tab** — Monthly payment estimates on every product page
- **Mobile-first** — Responsive at all breakpoints, custom mobile nav
- **Fast** — No page builder, no unnecessary JS, Google Fonts async

## Design Tokens

```css
--pk-black:    #0A0A0A
--pk-dark:     #111111
--pk-gold:     #C9A84C
--pk-white:    #FFFFFF
--pk-text:     #E0E0E0
```

Fonts: **Playfair Display** (headings) + **Inter** (body) + **Rajdhani** (labels/nav)

## Deploying Live

1. Point domain to server
2. Replace `.env` with production credentials
3. Update `WP_SITE_URL` in `.env` to production URL
4. Import DB or run `setup.sh` on server (via SSH + Docker)
5. Replace placeholder product images with real manufacturer photos (Atosa, True, Vulcan)
6. Add real Google Analytics / GTM
