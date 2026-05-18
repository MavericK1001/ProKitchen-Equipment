<?php
/**
 * Page Template: Financing
 * Template Name: Financing
 */
get_header();
?>

<!-- Page Hero -->
<div class="pk-page-hero">
    <div class="pk-container pk-page-hero__inner">
        <span class="pk-label">Easy Approvals</span>
        <h1>Equipment Financing</h1>
        <p class="pk-lead">Stop letting cash flow dictate your opening date. We've helped 400+ operators finance their kitchens with terms that work around revenue, not against it.</p>
    </div>
</div>

<!-- Options -->
<section class="pk-section pk-section--dark">
    <div class="pk-container">
        <div class="pk-section-header pk-section-header--center">
            <span class="pk-label">Financing Programs</span>
            <h2>Choose What Works For You</h2>
        </div>
        <div class="pk-grid-3" style="gap:1.5rem;">
            <div class="pk-feature-item">
                <div class="pk-feature-item__icon"><?php echo pk_icon('dollar'); ?></div>
                <h4 class="pk-feature-item__title">$0 Down Financing</h4>
                <p class="pk-feature-item__text">Full equipment cost financed. Keep your cash in operations, payroll, and inventory. 12–60 month terms available.</p>
                <div style="margin-top:1rem;">
                    <span class="pk-tag pk-tag--gold">Best For: New Openings</span>
                </div>
            </div>
            <div class="pk-feature-item" style="border-color: var(--pk-gold);">
                <div class="pk-feature-item__icon"><?php echo pk_icon('check'); ?></div>
                <h4 class="pk-feature-item__title">Deferred Payment</h4>
                <p class="pk-feature-item__text">No payments for 90 days. Perfect if you're building out and not yet generating revenue. Start paying when you start earning.</p>
                <div style="margin-top:1rem;">
                    <span class="pk-tag pk-tag--gold">Best For: Pre-Opening</span>
                </div>
            </div>
            <div class="pk-feature-item">
                <div class="pk-feature-item__icon"><?php echo pk_icon('shield'); ?></div>
                <h4 class="pk-feature-item__title">Trade Account</h4>
                <p class="pk-feature-item__text">Net-30 and Net-60 terms for established operators. Volume pricing for multiple units. Call to set up your trade account.</p>
                <div style="margin-top:1rem;">
                    <span class="pk-tag pk-tag--gold">Best For: Multi-Unit Groups</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Calculator -->
<section class="pk-section pk-section--darker">
    <div class="pk-container">
        <div style="max-width:680px; margin:0 auto;">
            <div class="pk-section-header pk-section-header--center">
                <span class="pk-label">Payment Estimator</span>
                <h2>Calculate Your Monthly Payment</h2>
            </div>
            <div style="background:var(--pk-dark-card); border:1px solid var(--pk-border); border-radius:var(--pk-radius-lg); padding:2.5rem;" id="pk-financing-calc">
                <div class="pk-form-grid">
                    <div class="pk-form-group">
                        <label for="calc-amount">Equipment Cost ($)</label>
                        <input type="number" id="calc-amount" value="10000" min="500" placeholder="10000">
                    </div>
                    <div class="pk-form-group">
                        <label for="calc-term">Loan Term</label>
                        <select id="calc-term">
                            <option value="12">12 Months</option>
                            <option value="24">24 Months</option>
                            <option value="36" selected>36 Months</option>
                            <option value="48">48 Months</option>
                            <option value="60">60 Months</option>
                        </select>
                    </div>
                    <div class="pk-form-group pk-form-group--full">
                        <label for="calc-rate">Annual Interest Rate (%)</label>
                        <input type="number" id="calc-rate" value="0" min="0" max="30" step="0.5" placeholder="0 for promo rate">
                    </div>
                </div>
                <div style="background:var(--pk-dark-2); border:1px solid var(--pk-gold); border-radius:var(--pk-radius-lg); padding:2rem; text-align:center; margin-top:1rem;">
                    <p class="pk-label" style="margin-bottom:0.5rem;">Estimated Monthly Payment</p>
                    <div id="calc-result" style="font-family:var(--pk-font-heading);font-size:3rem;font-weight:700;color:var(--pk-gold);"></div>
                    <div id="calc-total" style="font-size:0.875rem;color:var(--pk-text-3);margin-top:0.5rem;"></div>
                    <p style="font-size:0.75rem;color:var(--pk-text-3);margin-top:1rem;">*Estimates only. Actual rates depend on credit profile and lender.</p>
                </div>
                <div style="margin-top:1.5rem; text-align:center;">
                    <a href="#apply" class="pk-btn pk-btn--gold pk-btn--lg">Apply Now — 24hr Approval</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="pk-section pk-section--dark">
    <div class="pk-container">
        <div class="pk-section-header pk-section-header--center">
            <span class="pk-label">The Process</span>
            <h2>Approved in 24 Hours</h2>
        </div>
        <div class="pk-grid-4" style="gap:1.5rem;">
            <?php
            $steps = [
                ['01', 'Submit Application', 'Online or by phone. Takes 5 minutes. Basic business and personal info.'],
                ['02', '24hr Decision', 'Our lending partners review and respond within one business day.'],
                ['03', 'Sign Docs', 'Digital signature. No fax, no paper, no branch visit.'],
                ['04', 'Equipment Ships', 'Order confirmed, equipment ships within 48 hours of funding.'],
            ];
            foreach ($steps as $step) : ?>
            <div class="pk-feature-item">
                <div style="font-family:var(--pk-font-heading);font-size:2.5rem;font-weight:700;color:var(--pk-gold);opacity:0.4;margin-bottom:0.5rem;"><?php echo $step[0]; ?></div>
                <h4 class="pk-feature-item__title"><?php echo $step[1]; ?></h4>
                <p class="pk-feature-item__text"><?php echo $step[2]; ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Apply Form -->
<section id="apply" class="pk-section pk-section--darker">
    <div class="pk-container">
        <div style="max-width:640px; margin:0 auto;">
            <div class="pk-section-header pk-section-header--center">
                <span class="pk-label">Get Started</span>
                <h2>Apply for Equipment Financing</h2>
                <p>Not an application — a conversation starter. Fill this out and a financing specialist calls you within 24 hours.</p>
            </div>
            <div style="background:var(--pk-dark-card); border:1px solid var(--pk-border); border-top:3px solid var(--pk-gold); border-radius:var(--pk-radius-lg); padding:2.5rem;">
                <form class="pk-contact-form">
                    <?php wp_nonce_field( 'pk_contact_submit', 'contact_nonce' ); ?>
                    <input type="hidden" name="form_type" value="financing">
                    <div class="pk-form-grid">
                        <div class="pk-form-group">
                            <label>Full Name *</label>
                            <input type="text" name="name" required>
                        </div>
                        <div class="pk-form-group">
                            <label>Restaurant / Business *</label>
                            <input type="text" name="company" required>
                        </div>
                        <div class="pk-form-group">
                            <label>Email Address *</label>
                            <input type="email" name="email" required>
                        </div>
                        <div class="pk-form-group">
                            <label>Phone Number *</label>
                            <input type="tel" name="phone" required>
                        </div>
                        <div class="pk-form-group">
                            <label>Financing Amount Needed</label>
                            <select name="amount">
                                <option value="under5k">Under $5,000</option>
                                <option value="5k-15k" selected>$5,000 – $15,000</option>
                                <option value="15k-50k">$15,000 – $50,000</option>
                                <option value="over50k">Over $50,000</option>
                            </select>
                        </div>
                        <div class="pk-form-group">
                            <label>Business in Operation</label>
                            <select name="time_in_business">
                                <option value="new">New / Pre-opening</option>
                                <option value="under1yr">Under 1 Year</option>
                                <option value="1-3yr">1–3 Years</option>
                                <option value="over3yr">3+ Years</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="pk-btn pk-btn--gold pk-btn--full pk-btn--lg" style="margin-top:0.5rem;">
                        Request Financing Info <?php echo pk_icon('arrow'); ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
