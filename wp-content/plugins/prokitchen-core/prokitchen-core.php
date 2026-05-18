<?php
/**
 * ProKitchen Core — Custom plugin
 * Handles: product specs meta box, quote form AJAX, contact form AJAX, page templates registration
 */
defined( 'ABSPATH' ) || exit;
/*
 * Plugin Name:  ProKitchen Core
 * Plugin URI:   https://github.com/MavericK1001/ProKitchen-Equipment
 * Description:  Custom functionality for ProKitchen Equipment store: product specs meta boxes, quote request system, page template registration, and contact form handling.
 * Version:      1.0.0
 * Author:       ProKitchen Equipment
 * Text Domain:  prokitchen-core
 * Requires PHP: 8.0
 */

/* ─────────────────────────────────────────────
   1. PAGE TEMPLATES — Register custom page templates
───────────────────────────────────────────── */
add_filter( 'theme_page_templates', function ( $templates ) {
    $templates['templates/financing.php'] = 'Financing';
    $templates['templates/delivery.php']  = 'Delivery & Installation';
    $templates['templates/contact.php']   = 'Contact';
    return $templates;
} );

add_filter( 'template_include', function ( $template ) {
    if ( is_page() ) {
        $custom = get_post_meta( get_the_ID(), '_wp_page_template', true );
        if ( $custom && $custom !== 'default' ) {
            $file = get_template_directory() . '/' . $custom;
            if ( file_exists( $file ) ) {
                return $file;
            }
        }
    }
    return $template;
} );

/* ─────────────────────────────────────────────
   2. PRODUCT SPECS META BOX
───────────────────────────────────────────── */
add_action( 'add_meta_boxes', function () {
    add_meta_box(
        'pk_product_specs',
        '⚙ Equipment Specifications',
        'pk_render_specs_metabox',
        'product',
        'normal',
        'high'
    );
} );

function pk_render_specs_metabox( WP_Post $post ): void {
    wp_nonce_field( 'pk_save_specs', 'pk_specs_nonce' );
    $fields = [
        'pk_brand'      => [ 'label' => 'Brand / Manufacturer', 'placeholder' => 'e.g. True, Atosa, Vulcan' ],
        'pk_capacity'   => [ 'label' => 'Capacity',             'placeholder' => 'e.g. 49 cu/ft' ],
        'pk_power'      => [ 'label' => 'Power / Electrical',   'placeholder' => 'e.g. 115V/60Hz/1PH, 7.2A' ],
        'pk_dimensions' => [ 'label' => 'Dimensions (W×D×H)',   'placeholder' => 'e.g. 54.875"W × 29.5"D × 78.375"H' ],
        'pk_weight'     => [ 'label' => 'Ship Weight',          'placeholder' => 'e.g. 285 lbs' ],
        'pk_nsf'        => [ 'label' => 'Certifications',       'placeholder' => 'e.g. NSF/ANSI-7, Energy Star, ETL' ],
    ];
    echo '<style>.pk-meta-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px 20px;margin:10px 0}</style>';
    echo '<div class="pk-meta-grid">';
    foreach ( $fields as $key => $field ) {
        $val = esc_attr( get_post_meta( $post->ID, $key, true ) );
        printf(
            '<div><label for="%1$s" style="display:block;font-weight:600;font-size:12px;margin-bottom:4px;">%2$s</label>
             <input type="text" id="%1$s" name="%1$s" value="%3$s" placeholder="%4$s" style="width:100%%"></div>',
            esc_attr( $key ),
            esc_html( $field['label'] ),
            $val,
            esc_attr( $field['placeholder'] )
        );
    }
    echo '</div>';
}

add_action( 'save_post_product', function ( int $post_id ): void {
    if ( ! isset( $_POST['pk_specs_nonce'] ) || ! wp_verify_nonce( $_POST['pk_specs_nonce'], 'pk_save_specs' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $fields = [ 'pk_brand', 'pk_capacity', 'pk_power', 'pk_dimensions', 'pk_weight', 'pk_nsf' ];
    foreach ( $fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( $_POST[ $field ] ) );
        }
    }
} );

/* ─────────────────────────────────────────────
   3. QUOTE REQUEST AJAX
───────────────────────────────────────────── */
add_action( 'wp_ajax_pk_submit_quote',        'pk_handle_quote_request' );
add_action( 'wp_ajax_nopriv_pk_submit_quote', 'pk_handle_quote_request' );

function pk_handle_quote_request(): void {
    // Verify nonce
    $nonce = sanitize_text_field( $_POST['nonce'] ?? '' );
    if ( ! wp_verify_nonce( $nonce, 'prokitchen_nonce' ) ) {
        wp_send_json_error( [ 'message' => 'Security check failed.' ] );
    }

    // Parse form data
    parse_str( $_POST['formData'] ?? '', $data );

    $name         = sanitize_text_field( $data['name']         ?? '' );
    $company      = sanitize_text_field( $data['company']      ?? '' );
    $email        = sanitize_email(      $data['email']        ?? '' );
    $phone        = sanitize_text_field( $data['phone']        ?? '' );
    $product_name = sanitize_text_field( $data['product_name'] ?? '' );
    $quantity     = absint(              $data['quantity']     ?? 1 );
    $timeline     = sanitize_text_field( $data['timeline']     ?? '' );
    $message      = sanitize_textarea_field( $data['message']  ?? '' );

    if ( ! $name || ! $email || ! is_email( $email ) ) {
        wp_send_json_error( [ 'message' => 'Please fill in all required fields.' ] );
    }

    // Save as a custom post
    $quote_id = wp_insert_post( [
        'post_type'    => 'pk_quote',
        'post_title'   => sprintf( 'Quote: %s — %s', $product_name, $company ?: $name ),
        'post_status'  => 'publish',
        'post_content' => $message,
        'meta_input'   => [
            'pk_quote_name'     => $name,
            'pk_quote_company'  => $company,
            'pk_quote_email'    => $email,
            'pk_quote_phone'    => $phone,
            'pk_quote_product'  => $product_name,
            'pk_quote_quantity' => $quantity,
            'pk_quote_timeline' => $timeline,
        ],
    ] );

    // Email notification
    $admin_email = get_option( 'admin_email' );
    $subject     = "New Quote Request: {$product_name} — {$company}";
    $body        = "New quote request received.\n\n";
    $body       .= "Name:     {$name}\n";
    $body       .= "Company:  {$company}\n";
    $body       .= "Email:    {$email}\n";
    $body       .= "Phone:    {$phone}\n";
    $body       .= "Product:  {$product_name}\n";
    $body       .= "Quantity: {$quantity}\n";
    $body       .= "Timeline: {$timeline}\n\n";
    $body       .= "Notes:\n{$message}\n\n";
    $body       .= "Manage: " . admin_url( "post.php?post={$quote_id}&action=edit" );

    wp_mail( $admin_email, $subject, $body );

    // Confirmation to customer
    $confirm_body = "Hi {$name},\n\nWe've received your quote request for the {$product_name} and will get back to you within 2 business hours.\n\nIf you need immediate assistance, call us at (800) 776-5484.\n\nProKitchen Equipment Team";
    wp_mail( $email, 'Quote Request Received — ProKitchen Equipment', $confirm_body );

    wp_send_json_success( [ 'message' => 'Quote request submitted.' ] );
}

/* ─────────────────────────────────────────────
   4. CONTACT / FINANCING FORM AJAX
───────────────────────────────────────────── */
add_action( 'wp_ajax_pk_contact_submit',        'pk_handle_contact' );
add_action( 'wp_ajax_nopriv_pk_contact_submit', 'pk_handle_contact' );

function pk_handle_contact(): void {
    $nonce = sanitize_text_field( $_POST['nonce'] ?? '' );
    if ( ! wp_verify_nonce( $nonce, 'prokitchen_nonce' ) ) {
        wp_send_json_error();
    }

    parse_str( $_POST['formData'] ?? '', $data );

    $name      = sanitize_text_field( $data['name']    ?? '' );
    $email     = sanitize_email(      $data['email']   ?? '' );
    $company   = sanitize_text_field( $data['company'] ?? '' );
    $phone     = sanitize_text_field( $data['phone']   ?? '' );
    $subject   = sanitize_text_field( $data['subject'] ?? 'Contact Form' );
    $message   = sanitize_textarea_field( $data['message'] ?? '' );
    $form_type = sanitize_text_field( $data['form_type'] ?? 'contact' );

    if ( ! $name || ! $email ) {
        wp_send_json_error();
    }

    $admin_email = get_option( 'admin_email' );
    $mail_subject = "[ProKitchen] {$form_type}: {$subject} from {$name}";
    $mail_body    = "Form Type: {$form_type}\nName: {$name}\nCompany: {$company}\nEmail: {$email}\nPhone: {$phone}\n\nMessage:\n{$message}";

    wp_mail( $admin_email, $mail_subject, $mail_body );
    wp_send_json_success();
}

/* ─────────────────────────────────────────────
   5. REGISTER pk_quote CPT
───────────────────────────────────────────── */
add_action( 'init', function () {
    register_post_type( 'pk_quote', [
        'label'         => 'Quote Requests',
        'labels'        => [
            'name'          => 'Quote Requests',
            'singular_name' => 'Quote Request',
            'menu_name'     => 'Quotes',
            'all_items'     => 'All Quotes',
            'view_item'     => 'View Quote',
        ],
        'public'        => false,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'menu_icon'     => 'dashicons-testimonial',
        'supports'      => [ 'title', 'editor', 'custom-fields' ],
        'menu_position' => 25,
        'capability_type' => 'post',
    ] );
} );

/* ─────────────────────────────────────────────
   6. QUOTE FORM SHORTCODE
───────────────────────────────────────────── */
function pk_quote_form( array $atts = [] ): string {
    $product = $atts['product'] ?? '';
    ob_start();
    ?>
    <form class="pk-quote-form" id="pk-quote-form" method="post">
        <?php wp_nonce_field( 'pk_quote_request', 'pk_quote_nonce' ); ?>
        <input type="hidden" name="product_name" value="<?php echo esc_attr($product); ?>">
        <div class="pk-form-grid">
            <div class="pk-form-group"><label>Full Name *</label><input type="text" name="name" required></div>
            <div class="pk-form-group"><label>Company *</label><input type="text" name="company" required></div>
            <div class="pk-form-group"><label>Email *</label><input type="email" name="email" required></div>
            <div class="pk-form-group"><label>Phone</label><input type="tel" name="phone"></div>
            <div class="pk-form-group"><label>Quantity</label><input type="number" name="quantity" value="1" min="1"></div>
            <div class="pk-form-group"><label>Timeline</label>
                <select name="timeline">
                    <option value="asap">ASAP</option>
                    <option value="30days">Within 30 days</option>
                    <option value="60days">Within 60 days</option>
                    <option value="90days">90+ days</option>
                </select>
            </div>
            <div class="pk-form-group pk-form-group--full"><label>Notes</label><textarea name="message"></textarea></div>
        </div>
        <button type="submit" class="pk-btn pk-btn--gold pk-btn--full">
            Send Quote Request
        </button>
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode( 'pk_quote_form', 'pk_quote_form' );

/* ─────────────────────────────────────────────
   7. ADMIN: Quote list table columns
───────────────────────────────────────────── */
add_filter( 'manage_pk_quote_posts_columns', function ( $cols ) {
    return [
        'cb'          => $cols['cb'],
        'title'       => 'Product / Subject',
        'pk_customer' => 'Customer',
        'pk_email'    => 'Email',
        'pk_phone'    => 'Phone',
        'date'        => 'Date',
    ];
} );

add_action( 'manage_pk_quote_posts_custom_column', function ( string $col, int $post_id ): void {
    switch ( $col ) {
        case 'pk_customer':
            echo esc_html( get_post_meta( $post_id, 'pk_quote_name', true ) );
            $co = get_post_meta( $post_id, 'pk_quote_company', true );
            if ( $co ) echo '<br><small>' . esc_html($co) . '</small>';
            break;
        case 'pk_email':
            $em = get_post_meta( $post_id, 'pk_quote_email', true );
            echo '<a href="mailto:' . esc_attr($em) . '">' . esc_html($em) . '</a>';
            break;
        case 'pk_phone':
            echo esc_html( get_post_meta( $post_id, 'pk_quote_phone', true ) );
            break;
    }
}, 10, 2 );
