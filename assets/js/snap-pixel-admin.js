/**
 * Pixel Integration for Snapchat — Admin JavaScript
 */
(function($) {
    'use strict';

    // Initialize when document is ready
    $(document).ready(function() {
        // Initialize the admin interface
        initSnapPixelAdmin();
    });

    /**
     * Initialize the admin interface
     */
    function initSnapPixelAdmin() {
        // Get the WooCommerce toggle element
        var wooToggle = $('#woo_activate');
        
        // If the toggle exists, initialize it
        if (wooToggle.length) {
            // Set initial state
            toggleWooSettings(wooToggle.val());
            
            // Add change event listener
            wooToggle.on('change', function() {
                toggleWooSettings($(this).val());
            });
        }

        // Handle tab switching
        $('.snap-pixel-tab').on('click', function(e) {
            if ($(this).attr('disabled')) {
                e.preventDefault();
            }
        });
        
        // Show the cards after initialization
        $('.snap-pixel-card').addClass('loaded');
    }
    
    /**
     * Toggle WooCommerce settings visibility based on the toggle value
     * 
     * @param {string} value The value of the WooCommerce integration toggle
     */
    function toggleWooSettings(value) {
        var wooSettings = $('#woocommerce-settings');
        
        if (value === 'yes') {
            wooSettings.slideDown(300);
            // Enable all checkboxes when WooCommerce is enabled
            wooSettings.find('input[type="checkbox"]').prop('disabled', false);
        } else {
            wooSettings.slideUp(300);
            
            // Uncheck and disable all checkboxes when WooCommerce is disabled
            wooSettings.find('input[type="checkbox"]').prop('checked', false).prop('disabled', true);
        }
    }
    
})(jQuery); 