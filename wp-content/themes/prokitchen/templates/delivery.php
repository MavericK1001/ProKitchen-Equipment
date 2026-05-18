<?php
/**
 * Page Template: Delivery & Installation
 * Template Name: Delivery
 */
get_header();
?>

<div class="pk-page-hero">
    <div class="pk-container pk-page-hero__inner">
        <span class="pk-label">Nationwide Service</span>
        <h1>Delivery &amp; Installation</h1>
        <p class="pk-lead">We don't just drop equipment at the loading dock. Our crews deliver, position, connect, and test every unit before they leave your kitchen.</p>
    </div>
</div>

<!-- Service Tiers -->
<section class="pk-section pk-section--dark">
    <div class="pk-container">
        <div class="pk-section-header pk-section-header--center">
            <span class="pk-label">Delivery Options</span>
            <h2>Choose Your Service Level</h2>
        </div>
        <div class="pk-grid-3" style="gap:1.5rem;">
            <?php
            $tiers = [
                [
                    'name' => 'Standard Delivery',
                    'price' => 'Free over $2,500',
                    'desc'  => 'Curbside delivery with lift gate service. You handle moving to installation point. Ships via freight carrier within 48 hours.',
                    'items' => ['Freight delivery', 'Lift gate included', 'Delivery appointment', 'Packaging removal', '48-hour ship time'],
                    'tag'   => 'Most Common',
                ],
                [
                    'name' => 'Room of Choice',
                    'price' => 'From $150',
                    'desc'  => 'Our crew brings equipment to the exact installation point in your kitchen. We handle the heavy lifting through doors, hallways, and stairs.',
                    'items' => ['Everything in Standard', 'Interior placement', 'Stair carry available', 'Old equipment removal', 'Same-day delivery available'],
                    'tag'   => 'Recommended',
                    'gold'  => true,
                ],
                [
                    'name' => 'White-Glove Install',
                    'price' => 'From $350',
                    'desc'  => 'Full service: delivery, placement, electrical/gas hookup, commissioning test, and cleanup. Your kitchen is ready to cook.',
                    'items' => ['Everything in Room of Choice', 'Utility hookup', 'Commissioning test', 'Staff orientation', '30-day follow-up'],
                    'tag'   => 'Best Experience',
                ],
            ];
            foreach ($tiers as $tier) :
                $border = !empty($tier['gold']) ? 'border-color:var(--pk-gold);' : '';
            ?>
            <div class="pk-feature-item" style="text-align:left; <?php echo $border; ?>">
                <?php if (!empty($tier['gold'])) : ?>
                <div class="pk-product-card__badge" style="position:relative;top:auto;left:auto;display:inline-block;margin-bottom:1rem;"><?php echo $tier['tag']; ?></div>
                <?php endif; ?>
                <h3 style="font-size:1.25rem;margin-bottom:0.25rem;"><?php echo $tier['name']; ?></h3>
                <div style="font-family:var(--pk-font-heading);font-size:1.5rem;font-weight:700;color:var(--pk-gold);margin-bottom:1rem;"><?php echo $tier['price']; ?></div>
                <p class="pk-feature-item__text" style="margin-bottom:1.25rem;"><?php echo $tier['desc']; ?></p>
                <ul style="display:flex;flex-direction:column;gap:0.4rem;">
                    <?php foreach ($tier['items'] as $item) : ?>
                    <li style="display:flex;align-items:center;gap:0.5rem;font-size:0.875rem;color:var(--pk-text-2);">
                        <span style="color:var(--pk-gold);flex-shrink:0;"><?php echo pk_icon('check'); ?></span>
                        <?php echo $item; ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Delivery Times -->
<section class="pk-section pk-section--darker">
    <div class="pk-container">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:start;">
            <div>
                <span class="pk-label" style="display:block;margin-bottom:0.75rem;">Shipping Timeline</span>
                <h2>When Will My Equipment Arrive?</h2>
                <p style="color:var(--pk-text-2);margin:1rem 0 2rem;">Most in-stock items ship within 48 hours of order confirmation. Here's a typical timeline:</p>
                <div style="display:flex;flex-direction:column;gap:0;position:relative;">
                    <div style="position:absolute;left:12px;top:20px;bottom:20px;width:2px;background:var(--pk-border);"></div>
                    <?php
                    $timeline = [
                        ['Day 0', 'Order Placed', 'Order confirmed, financing (if applicable) funded, processing begins.'],
                        ['Day 1', 'Order Processed', 'Warehouse picks and stages your equipment for freight carrier pickup.'],
                        ['Days 2–3', 'Ships', 'Freight carrier picks up. Tracking number emailed to you.'],
                        ['Days 3–7', 'In Transit', 'Standard ground freight depending on your location from our distribution centers.'],
                        ['Delivery Day', 'Delivered', 'Delivery appointment scheduled. Our crew arrives at the agreed time.'],
                    ];
                    foreach ($timeline as $i => $t) : ?>
                    <div style="display:flex;gap:1.5rem;padding-bottom:2rem;position:relative;">
                        <div style="width:24px;height:24px;background:var(--pk-gold);border-radius:50%;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:0.6rem;font-weight:700;color:var(--pk-black);z-index:1;"><?php echo $i+1; ?></div>
                        <div>
                            <div style="font-family:var(--pk-font-accent);font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:var(--pk-gold);"><?php echo $t[0]; ?></div>
                            <div style="font-weight:600;color:var(--pk-white);margin:0.2rem 0;"><?php echo $t[1]; ?></div>
                            <div style="font-size:0.85rem;color:var(--pk-text-3);"><?php echo $t[2]; ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div>
                <span class="pk-label" style="display:block;margin-bottom:0.75rem;">Coverage</span>
                <h2>Where We Deliver</h2>
                <p style="color:var(--pk-text-2);margin:1rem 0 1.5rem;">We deliver to all 50 states via our network of freight carriers and installation crews.</p>
                <div style="background:var(--pk-dark-card);border:1px solid var(--pk-border);border-radius:var(--pk-radius-lg);padding:1.5rem;">
                    <table style="width:100%;font-size:0.875rem;border-collapse:collapse;">
                        <tr style="border-bottom:1px solid var(--pk-border);">
                            <td style="padding:0.75rem 0;color:var(--pk-text-3);">Continental US</td>
                            <td style="padding:0.75rem 0;color:var(--pk-white);text-align:right;">Free over $2,500</td>
                        </tr>
                        <tr style="border-bottom:1px solid var(--pk-border);">
                            <td style="padding:0.75rem 0;color:var(--pk-text-3);">Alaska &amp; Hawaii</td>
                            <td style="padding:0.75rem 0;color:var(--pk-white);text-align:right;">Quote required</td>
                        </tr>
                        <tr style="border-bottom:1px solid var(--pk-border);">
                            <td style="padding:0.75rem 0;color:var(--pk-text-3);">Canada</td>
                            <td style="padding:0.75rem 0;color:var(--pk-white);text-align:right;">Selected provinces</td>
                        </tr>
                        <tr>
                            <td style="padding:0.75rem 0;color:var(--pk-text-3);">Rush / Next-Day</td>
                            <td style="padding:0.75rem 0;color:var(--pk-gold);text-align:right;">Available — call us</td>
                        </tr>
                    </table>
                </div>
                <div style="margin-top:1.5rem;">
                    <a href="<?php echo home_url('/contact'); ?>" class="pk-btn pk-btn--gold pk-btn--lg">
                        Get a Delivery Quote <?php echo pk_icon('arrow'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
