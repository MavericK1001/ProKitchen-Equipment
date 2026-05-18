#!/usr/bin/env bash
# ═══════════════════════════════════════════════════════════
#  ProKitchen Equipment — Local Environment Setup
#  Installs WordPress + WooCommerce + 15 products via WP-CLI
# ═══════════════════════════════════════════════════════════
set -e

GREEN='\033[0;32m'
GOLD='\033[0;33m'
RED='\033[0;31m'
NC='\033[0m'

log() { echo -e "${GOLD}[ProKitchen]${NC} $1"; }
ok()  { echo -e "${GREEN}✓${NC} $1"; }
err() { echo -e "${RED}✗${NC} $1"; exit 1; }

CONTAINER="prokitchen_wp"
SITE_URL="http://localhost:8080"
ADMIN_USER="admin"
ADMIN_PASS="ProKitchen2024!"
ADMIN_EMAIL="admin@prokitchen.local"

echo ""
echo "════════════════════════════════════════"
echo "  ProKitchen Equipment — Local Setup"
echo "════════════════════════════════════════"
echo ""

# ─── Step 1: Start containers ───
log "[1/8] Starting Docker containers..."
docker-compose up -d
ok "Containers started"

# ─── Step 2: Wait for MySQL ───
log "[2/8] Waiting for MySQL to be healthy..."
for i in {1..30}; do
    STATUS=$(docker inspect --format='{{.State.Health.Status}}' prokitchen_db 2>/dev/null || echo "starting")
    if [ "$STATUS" = "healthy" ]; then
        ok "MySQL is ready"
        break
    fi
    [ $i -eq 30 ] && err "MySQL did not start in time. Check: docker logs prokitchen_db"
    printf "."
    sleep 3
done
echo ""

# ─── Step 3: Wait for WordPress HTTP ───
log "[3/8] Waiting for WordPress to be ready..."
for i in {1..20}; do
    if curl -sf --max-time 3 "$SITE_URL" > /dev/null 2>&1; then
        ok "WordPress is responding"
        break
    fi
    [ $i -eq 20 ] && err "WordPress not responding. Check: docker logs prokitchen_wp"
    printf "."
    sleep 4
done
echo ""

# ─── Step 4: Install WP-CLI ───
log "[4/8] Installing WP-CLI..."
docker exec $CONTAINER bash -c "
    if ! command -v wp &>/dev/null; then
        curl -sO https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
        chmod +x wp-cli.phar
        mv wp-cli.phar /usr/local/bin/wp
        echo 'WP-CLI installed'
    else
        echo 'WP-CLI already present'
    fi
"
ok "WP-CLI ready"

# Helper: run WP-CLI as www-data
wpcli() {
    docker exec -u www-data $CONTAINER wp --path=/var/www/html "$@"
}

# ─── Step 5: Install WordPress ───
log "[5/8] Installing WordPress..."
if wpcli core is-installed 2>/dev/null; then
    ok "WordPress already installed — skipping core install"
else
    wpcli core install \
        --url="$SITE_URL" \
        --title="ProKitchen Equipment Co." \
        --admin_user="$ADMIN_USER" \
        --admin_password="$ADMIN_PASS" \
        --admin_email="$ADMIN_EMAIL" \
        --skip-email
    ok "WordPress installed"
fi

# Update basic settings
wpcli option update blogdescription "Commercial Kitchen Equipment — NSF Certified, Fast Shipping"
wpcli option update timezone_string "America/Chicago"
wpcli option update date_format "F j, Y"
wpcli option update permalink_structure "/%postname%/"
wpcli rewrite flush
ok "WordPress settings updated"

# ─── Step 6: Plugins ───
log "[6/8] Installing plugins..."

# WooCommerce
if wpcli plugin is-installed woocommerce 2>/dev/null; then
    ok "WooCommerce already installed"
else
    wpcli plugin install woocommerce --activate
    ok "WooCommerce installed & activated"
fi

# Activate our custom plugin
wpcli plugin activate prokitchen-core 2>/dev/null || true
ok "ProKitchen Core plugin activated"

# Remove default plugins
wpcli plugin delete hello akismet 2>/dev/null || true

# ─── Step 7: Theme ───
log "[7/8] Activating ProKitchen theme..."
wpcli theme activate prokitchen 2>/dev/null || true

# Remove default themes
wpcli theme delete twentytwentyfour twentytwentythree twentytwentytwo 2>/dev/null || true
ok "ProKitchen theme activated"

# ─── Step 8: WooCommerce Setup ───
log "[8/8] Configuring WooCommerce & creating content..."

# WooCommerce pages
wpcli wc tool run install_pages --user=1 2>/dev/null || true

# Delete default sample page
wpcli post delete $(wpcli post list --post_type=page --name="sample-page" --field=ID 2>/dev/null) --force 2>/dev/null || true

# ─── Create Pages ───
log "  Creating pages..."

# Home (placeholder — front-page.php handles it)
HOME_ID=$(wpcli post list --post_type=page --name="home" --field=ID 2>/dev/null)
if [ -z "$HOME_ID" ]; then
    HOME_ID=$(wpcli post create \
        --post_type=page \
        --post_title="Home" \
        --post_status=publish \
        --post_content="" \
        --porcelain)
fi

# About
ABOUT_ID=$(wpcli post list --post_type=page --name="about" --field=ID 2>/dev/null)
if [ -z "$ABOUT_ID" ]; then
    ABOUT_ID=$(wpcli post create \
        --post_type=page \
        --post_title="About Us" \
        --post_name="about" \
        --post_status=publish \
        --post_content="<p>ProKitchen Equipment Co. has been supplying commercial kitchens across the United States since 2010. We're authorized dealers for True, Atosa, Vulcan, Blodgett, Cooltech, Imperial, and Federal Industries. Every unit we sell is NSF certified and ships with a manufacturer warranty.</p><p>We don't sell demo units. We don't sell grey-market equipment. We sell commercial-grade kitchen equipment from authorized sources, properly documented, backed by real warranty support.</p>" \
        --porcelain)
fi

# Financing
FIN_ID=$(wpcli post list --post_type=page --name="financing" --field=ID 2>/dev/null)
if [ -z "$FIN_ID" ]; then
    FIN_ID=$(wpcli post create \
        --post_type=page \
        --post_title="Financing" \
        --post_name="financing" \
        --post_status=publish \
        --post_content="" \
        --porcelain)
    wpcli post meta set $FIN_ID _wp_page_template "templates/financing.php"
fi

# Delivery
DEL_ID=$(wpcli post list --post_type=page --name="delivery" --field=ID 2>/dev/null)
if [ -z "$DEL_ID" ]; then
    DEL_ID=$(wpcli post create \
        --post_type=page \
        --post_title="Delivery & Installation" \
        --post_name="delivery" \
        --post_status=publish \
        --post_content="" \
        --porcelain)
    wpcli post meta set $DEL_ID _wp_page_template "templates/delivery.php"
fi

# Contact
CON_ID=$(wpcli post list --post_type=page --name="contact" --field=ID 2>/dev/null)
if [ -z "$CON_ID" ]; then
    CON_ID=$(wpcli post create \
        --post_type=page \
        --post_title="Contact" \
        --post_name="contact" \
        --post_status=publish \
        --post_content="" \
        --porcelain)
    wpcli post meta set $CON_ID _wp_page_template "templates/contact.php"
fi

# Set front page
wpcli option update show_on_front page
wpcli option update page_on_front $HOME_ID
ok "Pages created"

# ─── WooCommerce options ───
wpcli option update woocommerce_store_address "123 Industrial Blvd"
wpcli option update woocommerce_store_city "Chicago"
wpcli option update woocommerce_default_country "US:IL"
wpcli option update woocommerce_store_postcode "60601"
wpcli option update woocommerce_currency "USD"
wpcli option update woocommerce_price_num_decimals "0"
wpcli option update woocommerce_enable_reviews "yes"
wpcli option update woocommerce_enable_ajax_add_to_cart "yes"

# ─── Navigation Menu ───
log "  Creating navigation menu..."
wpcli menu delete "Main Menu" 2>/dev/null || true
wpcli menu create "Main Menu"
SHOP_PAGE=$(wpcli post list --post_type=page --name="shop" --field=ID 2>/dev/null | head -1)
wpcli menu item add-post "Main Menu" $SHOP_PAGE --title="Shop" 2>/dev/null || true
wpcli menu item add-post "Main Menu" $ABOUT_ID  --title="About" 2>/dev/null || true
wpcli menu item add-post "Main Menu" $FIN_ID    --title="Financing" 2>/dev/null || true
wpcli menu item add-post "Main Menu" $DEL_ID    --title="Delivery" 2>/dev/null || true
wpcli menu item add-post "Main Menu" $CON_ID    --title="Contact" 2>/dev/null || true
wpcli menu location assign "Main Menu" primary 2>/dev/null || true
ok "Navigation menu created"

# ─── Product Categories ───
log "  Creating product categories..."

create_cat() {
    local name="$1"; local slug="$2"; local desc="$3"
    local existing=$(wpcli term list product_cat --name="$name" --field=term_id 2>/dev/null | head -1)
    if [ -z "$existing" ]; then
        wpcli term create product_cat "$name" --slug="$slug" --description="$desc" --porcelain
    else
        echo "$existing"
    fi
}

CAT_FRIDGE=$(create_cat "Commercial Refrigeration" "commercial-refrigeration" "NSF-certified reach-in refrigerators, freezers, and glass-door merchandisers for restaurants and food service.")
CAT_OVEN=$(create_cat "Ovens & Ranges" "ovens-ranges" "Commercial convection ovens, deck ovens, and gas ranges built for professional kitchen production.")
CAT_PREP=$(create_cat "Prep Tables" "prep-tables" "Refrigerated sandwich and salad prep tables with mega-top configurations for high-volume operations.")
CAT_TABLE=$(create_cat "Stainless Work Tables" "work-tables" "NSF-certified 16 and 18-gauge stainless steel commercial work tables with undershelves.")
CAT_DISPLAY=$(create_cat "Display Cases" "display-cases" "Refrigerated display cases, glass-door merchandisers, and open-air cases for delis and bakeries.")

ok "Product categories created (IDs: $CAT_FRIDGE $CAT_OVEN $CAT_PREP $CAT_TABLE $CAT_DISPLAY)"

# ─── Helper: create product ───
create_product() {
    local name="$1"; local price="$2"; local cat_id="$3"; local sku="$4"
    local short_desc="$5"; local brand="$6"; local power="$7"
    local capacity="$8"; local dimensions="$9"; local nsf="${10}"

    local existing=$(wpcli post list --post_type=product --name="$(echo "$name" | tr '[:upper:]' '[:lower:]' | tr ' ' '-')" --field=ID 2>/dev/null | head -1)
    if [ -n "$existing" ]; then
        echo "$existing"
        return
    fi

    local prod_id=$(wpcli wc product create \
        --user=1 \
        --name="$name" \
        --regular_price="$price" \
        --short_description="$short_desc" \
        --status=publish \
        --featured=true \
        --manage_stock=false \
        --in_stock=true \
        --sku="$sku" \
        --porcelain 2>/dev/null)

    if [ -n "$prod_id" ]; then
        # Assign category
        wpcli wc product update $prod_id --user=1 --categories="[{\"id\":$cat_id}]" 2>/dev/null || true

        # Custom meta
        [ -n "$brand" ]      && wpcli post meta set $prod_id pk_brand "$brand"
        [ -n "$power" ]      && wpcli post meta set $prod_id pk_power "$power"
        [ -n "$capacity" ]   && wpcli post meta set $prod_id pk_capacity "$capacity"
        [ -n "$dimensions" ] && wpcli post meta set $prod_id pk_dimensions "$dimensions"
        [ -n "$nsf" ]        && wpcli post meta set $prod_id pk_nsf "$nsf"

        echo "$prod_id"
    fi
}

# ─── Products: Commercial Refrigeration ───
log "  Creating refrigeration products..."

create_product \
    "True T-49-HC Two-Door Reach-In Refrigerator" "3850" "$CAT_FRIDGE" "TRUE-T49HC" \
    "49 cu/ft two-section reach-in refrigerator. Stainless steel front, aluminum sides. Hydrocarbon refrigerant. True's industry-standard build quality." \
    "True" "115V/60Hz/1PH · 7.2A · 1/2 HP" "49 cu/ft" '54.875"W × 29.5"D × 78.375"H' "NSF/ANSI-7, Energy Star" > /dev/null

create_product \
    "Atosa MBF8507GR 27\" Glass Door Freezer" "2200" "$CAT_FRIDGE" "ATOSA-MBF8507GR" \
    "27\" one-section commercial glass-door freezer. Stainless steel interior and exterior. -10°F to 0°F temperature range. Bottom-mount compressor." \
    "Atosa" "115V/60Hz/1PH · 4.1A" "19 cu/ft" '27.25"W × 31.5"D × 83.25"H' "NSF/ANSI-7, ETL, Energy Star" > /dev/null

create_product \
    "True T-23-HC One-Door Reach-In Refrigerator" "1950" "$CAT_FRIDGE" "TRUE-T23HC" \
    "23 cu/ft single-section reach-in refrigerator. Perfect for tight kitchens. Stainless steel construction throughout. Hydrocarbon refrigerant." \
    "True" "115V/60Hz/1PH · 3.3A" "23 cu/ft" '27.5"W × 29.5"D × 78.375"H' "NSF/ANSI-7, Energy Star" > /dev/null

create_product \
    "Atosa MBF8503GR One-Door Reach-In Freezer" "1750" "$CAT_FRIDGE" "ATOSA-MBF8503GR" \
    "29\" single-door reach-in freezer. Stainless interior and exterior. Digital temperature display. Bottom-mount compressor for easy service access." \
    "Atosa" "115V/60Hz/1PH · 7.3A" "23 cu/ft" '29.25"W × 31.5"D × 83.25"H' "NSF/ANSI-7, ETL" > /dev/null

create_product \
    "Cooltech 4-Door Commercial Reach-In Refrigerator" "5600" "$CAT_FRIDGE" "CT-4DR-REACH" \
    "82 cu/ft four-section reach-in refrigerator. High-capacity commercial storage. Digital controls, LED lighting, self-cleaning condenser." \
    "Cooltech" "115V/60Hz/1PH · 14.2A" "82 cu/ft" '78"W × 29.5"D × 81"H' "NSF/ANSI-7, ETL, Energy Star" > /dev/null

ok "5 refrigeration products created"

# ─── Products: Ovens & Ranges ───
log "  Creating oven and range products..."

create_product \
    "Vulcan VC44GD Full-Size Gas Convection Oven" "4200" "$CAT_OVEN" "VULCAN-VC44GD" \
    "Full-size single-deck gas convection oven. 54,000 BTU. Two-speed motor, porcelain interior, stainless steel door. The workhorse of commercial bakeries and restaurants." \
    "Vulcan" "Natural Gas · 54,000 BTU/hr" "4 full-size sheet pans" '38"W × 37.25"D × 55.5"H' "NSF, ETL, AGA" > /dev/null

create_product \
    "Imperial 6-Burner Gas Range with Standard Oven" "3500" "$CAT_OVEN" "IMP-6B-RANGE" \
    "36\" commercial 6-burner gas range with standard oven below. Cast-iron grates, 30,000 BTU open burners. Stainless steel construction throughout." \
    "Imperial" "Natural Gas · 162,000 BTU total" "Full-size oven cavity" '36"W × 30"D × 35.5"H (+ backsplash)' "NSF, ETL, AGA, CSA" > /dev/null

create_product \
    "Blodgett DFG-100 Double Stack Deck Oven" "8800" "$CAT_OVEN" "BLODGETT-DFG100-DBL" \
    "Double-stack natural gas deck oven. Two independent 50,000 BTU decks with separate controls. Industry standard for artisan bread and pizza operations." \
    "Blodgett" "Natural Gas · 50,000 BTU per deck" "2 × 18\"×26\" full-size decks" '38.3125"W × 36.5"D × 69"H' "NSF, ETL, AGA" > /dev/null

ok "3 oven products created"

# ─── Products: Prep Tables ───
log "  Creating prep table products..."

create_product \
    "Atosa MSF8307GR 60\" Sandwich & Salad Prep Table" "1850" "$CAT_PREP" "ATOSA-MSF8307GR" \
    "60\" refrigerated sandwich/salad prep table. 12 cu/ft, 8 pan capacity (1/3 size). Digital controls, rear-mounted compressor, stainless construction." \
    "Atosa" "115V/60Hz/1PH · 2.5A" "12 cu/ft · 8× 1/3 pans" '60.25"W × 30"D × 36.875"H' "NSF/ANSI-7, ETL, Energy Star" > /dev/null

create_product \
    "True TSSU-60-24M-B 60\" Sandwich Prep Table" "4100" "$CAT_PREP" "TRUE-TSSU6024MB" \
    "60\" sandwich unit with 24 pan capacity (1/6 size). Mega-top configuration. Stainless steel inside and out. True's legendary refrigeration performance." \
    "True" "115V/60Hz/1PH · 3.4A" "24 cu/ft · 24× 1/6 pans" '59.875"W × 30"D × 36.75"H' "NSF/ANSI-7, Energy Star" > /dev/null

create_product \
    "Cooltech 93\" Mega Top Prep Table" "2800" "$CAT_PREP" "CT-93MT-PREP" \
    "93\" mega-top refrigerated prep table. Maximum topping capacity for high-volume sandwich and salad operations. Stainless steel construction, bottom storage." \
    "Cooltech" "115V/60Hz/1PH · 4.8A" "30 cu/ft" '93"W × 30"D × 36"H' "NSF/ANSI-7, ETL" > /dev/null

ok "3 prep table products created"

# ─── Products: Work Tables ───
log "  Creating work table products..."

create_product \
    "24\"×60\" 18-Gauge Stainless Steel Work Table" "320" "$CAT_TABLE" "WT-2460-18G" \
    "Commercial 18-gauge stainless steel work table. 1.5\" backsplash. Adjustable undershelf. Adjustable bullet feet. Ships fully assembled." \
    "" "No utilities required" "" '24"W × 60"L × 34"H' "NSF/ANSI-2" > /dev/null

create_product \
    "30\"×72\" 16-Gauge Heavy-Duty Work Table with Undershelf" "450" "$CAT_TABLE" "WT-3072-16G-US" \
    "Heavy-duty 16-gauge stainless steel commercial work table with fixed undershelf. 1\" diameter tubular legs. Built for butcher block-level abuse." \
    "" "No utilities required" "" '30"W × 72"L × 34"H' "NSF/ANSI-2" > /dev/null

ok "2 work table products created"

# ─── Products: Display Cases ───
log "  Creating display case products..."

create_product \
    "True GDM-49 Glass Door Merchandiser Cooler" "2800" "$CAT_DISPLAY" "TRUE-GDM49" \
    "49 cu/ft glass door reach-in merchandiser. Six shelves, self-contained refrigeration, LED lighting. For beverages, bottled products, and prepared foods." \
    "True" "115V/60Hz/1PH · 7.4A" "49 cu/ft · 6 shelves" '27.75"W × 29.75"D × 78.375"H' "NSF/ANSI-7, Energy Star" > /dev/null

create_product \
    "Federal Industries SL3660-B 5-Shelf Refrigerated Display Case" "5200" "$CAT_DISPLAY" "FED-SL3660B" \
    "60\" refrigerated island display case. Five shelves with top-mounted lighting. Straight glass front. For delis, bakeries, and prepared foods." \
    "Federal Industries" "120V/60Hz/1PH · 8.3A" "37 cu/ft · 5 shelves" '60"W × 36"D × 52.625"H' "NSF/ANSI-7, ETL" > /dev/null

ok "2 display case products created"

# ─── Set Featured Products ───
log "  Flagging featured products..."
for slug in "true-t-49-hc-two-door-reach-in-refrigerator" "vulcan-vc44gd-full-size-gas-convection-oven" "atosa-msf8307gr-60-sandwich-salad-prep-table"; do
    pid=$(wpcli post list --post_type=product --name="$slug" --field=ID 2>/dev/null | head -1)
    [ -n "$pid" ] && wpcli post meta set $pid _featured "yes" 2>/dev/null || true
done

ok "Featured products set"

echo ""
echo "════════════════════════════════════════"
echo "  ✓ Setup Complete!"
echo "════════════════════════════════════════"
echo ""
echo "  🌐 Site:       $SITE_URL"
echo "  🔧 Admin:      $SITE_URL/wp-admin"
echo "  🛒 Shop:       $SITE_URL/shop"
echo "  🗄️  phpMyAdmin: http://localhost:8081"
echo ""
echo "  Admin Login:"
echo "    User:     $ADMIN_USER"
echo "    Password: $ADMIN_PASS"
echo ""
echo "  To stop:  docker-compose down"
echo "  To reset: docker-compose down -v && bash setup.sh"
echo "════════════════════════════════════════"
echo ""
