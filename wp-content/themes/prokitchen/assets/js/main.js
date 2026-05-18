/**
 * ProKitchen Equipment — Main JS
 */
(function($) {
    'use strict';

    /* ─── Mobile Nav ─── */
    const $toggle  = $('#pk-menu-toggle');
    const $close   = $('#pk-mobile-close');
    const $nav     = $('#pk-mobile-nav');
    const $overlay = $('#pk-overlay');

    function openNav() {
        $nav.addClass('is-open').attr('aria-hidden', 'false');
        $overlay.addClass('is-active');
        $toggle.attr('aria-expanded', 'true');
        $('body').css('overflow', 'hidden');
    }
    function closeNav() {
        $nav.removeClass('is-open').attr('aria-hidden', 'true');
        $overlay.removeClass('is-active');
        $toggle.attr('aria-expanded', 'false');
        $('body').css('overflow', '');
    }

    $toggle.on('click', openNav);
    $close.on('click', closeNav);
    $overlay.on('click', closeNav);
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') closeNav();
    });

    /* ─── Sticky Header ─── */
    const $header = $('#pk-header');
    $(window).on('scroll', function() {
        if ($(this).scrollTop() > 80) {
            $header.addClass('is-scrolled');
        } else {
            $header.removeClass('is-scrolled');
        }
    });

    /* ─── Product Tabs ─── */
    $(document).on('click', '.pk-tab-btn', function() {
        const tab     = $(this).data('tab');
        const $parent = $(this).closest('.pk-product-tabs');
        $parent.find('.pk-tab-btn').removeClass('is-active').attr('aria-selected', 'false');
        $parent.find('.pk-tab-panel').removeClass('is-active');
        $(this).addClass('is-active').attr('aria-selected', 'true');
        $parent.find('#tab-' + tab).addClass('is-active');
    });

    /* ─── Quote Modal ─── */
    const $modal    = $('#pk-quote-modal');
    const $backdrop = $('#pk-quote-backdrop');
    const $closeBtn = $('#pk-quote-close');

    function openQuote() {
        $modal.addClass('is-open');
        $('body').css('overflow', 'hidden');
        // Focus first input
        setTimeout(function() {
            $modal.find('input, textarea').first().focus();
        }, 300);
    }
    function closeQuote() {
        $modal.removeClass('is-open');
        $('body').css('overflow', '');
    }

    $(document).on('click', '.pk-open-quote', function() {
        const productName = $(this).data('product') || '';
        $modal.find('[name="product_name"]').val(productName);
        openQuote();
    });
    $backdrop.on('click', closeQuote);
    $closeBtn.on('click', closeQuote);
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $modal.hasClass('is-open')) closeQuote();
    });

    /* ─── Quote Form AJAX Submit ─── */
    $(document).on('submit', '#pk-quote-form', function(e) {
        e.preventDefault();
        const $form   = $(this);
        const $btn    = $form.find('button[type="submit"]');
        const origTxt = $btn.html();

        $btn.html('Sending...').prop('disabled', true);

        $.ajax({
            url:  pkData.ajaxUrl,
            type: 'POST',
            data: {
                action:   'pk_submit_quote',
                nonce:    pkData.nonce,
                formData: $form.serialize()
            },
            success: function(res) {
                if (res.success) {
                    $form.html('<div class="pk-notice pk-notice--success" style="margin:2rem 0;font-size:1rem;">✓ Quote request received! We\'ll contact you within 2 business hours.</div>');
                } else {
                    $btn.html(origTxt).prop('disabled', false);
                    $form.prepend('<div class="pk-notice pk-notice--error">Something went wrong. Please try again or call us directly.</div>');
                }
            },
            error: function() {
                $btn.html(origTxt).prop('disabled', false);
            }
        });
    });

    /* ─── Qty Control ─── */
    $(document).on('click', '.pk-qty-plus', function() {
        const $input = $(this).siblings('.pk-qty-input');
        const max    = parseInt($input.attr('max')) || 9999;
        let   val    = parseInt($input.val()) || 1;
        if (val < max) $input.val(val + 1).trigger('change');
    });
    $(document).on('click', '.pk-qty-minus', function() {
        const $input = $(this).siblings('.pk-qty-input');
        const min    = parseInt($input.attr('min')) || 1;
        let   val    = parseInt($input.val()) || 1;
        if (val > min) $input.val(val - 1).trigger('change');
    });

    /* ─── Product Gallery Thumbnails ─── */
    $(document).on('click', '.pk-product-thumb', function() {
        const fullSrc = $(this).data('full');
        $('.pk-product-thumb').removeClass('is-active');
        $(this).addClass('is-active');
        // Update main WooCommerce gallery image
        $('.woocommerce-product-gallery__image img').first().attr('src', fullSrc);
    });

    /* ─── Financing Calculator (financing page) ─── */
    if ($('#pk-financing-calc').length) {
        function calcPayment() {
            const amount = parseFloat($('#calc-amount').val()) || 0;
            const term   = parseInt($('#calc-term').val()) || 36;
            const rate   = parseFloat($('#calc-rate').val()) || 0;

            let monthly;
            if (rate === 0) {
                monthly = amount / term;
            } else {
                const r = (rate / 100) / 12;
                monthly = amount * (r * Math.pow(1 + r, term)) / (Math.pow(1 + r, term) - 1);
            }

            $('#calc-result').text('$' + Math.ceil(monthly).toLocaleString() + '/mo');
            $('#calc-total').text('Total: $' + Math.ceil(monthly * term).toLocaleString());
        }
        $('#pk-financing-calc input, #pk-financing-calc select').on('input change', calcPayment);
        calcPayment();
    }

    /* ─── Smooth Scroll for Anchor Links ─── */
    $(document).on('click', 'a[href^="#"]:not([href="#"])', function(e) {
        const target = $($(this).attr('href'));
        if (target.length) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: target.offset().top - 90
            }, 400);
        }
    });

    /* ─── Contact Form Validation ─── */
    $(document).on('submit', '.pk-contact-form', function(e) {
        e.preventDefault();
        const $form = $(this);
        const $btn  = $form.find('button[type="submit"]');

        $.ajax({
            url:  pkData.ajaxUrl,
            type: 'POST',
            data: {
                action:   'pk_contact_submit',
                nonce:    pkData.nonce,
                formData: $form.serialize()
            },
            success: function(res) {
                if (res.success) {
                    $form.html('<div class="pk-notice pk-notice--success" style="padding:2rem; font-size:1rem; margin-top:1rem;">✓ Message sent! We\'ll respond within 24 hours.</div>');
                } else {
                    $btn.prop('disabled', false);
                }
            }
        });
    });

    /* ─── Filter Panel Toggle (mobile) ─── */
    $(document).on('click', '.pk-filter-panel__head', function() {
        $(this).siblings('.pk-filter-panel__body').slideToggle(200);
        $(this).find('.pk-filter-toggle-icon').text(function(_, t) {
            return t === '−' ? '+' : '−';
        });
    });

})(jQuery);
