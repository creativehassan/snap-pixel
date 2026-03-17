/**
 * Pixel Integration for Snapchat — Frontend JavaScript
 * 
 * Provides a wrapper for the snaptr function to prevent duplicate event firing.
 */
(function($) {
    'use strict';

    /**
     * Get product data via AJAX
     * 
     * @param {number} product_id Product ID
     * @return {void}
     */
    window.get_product_record = function(product_id) {
        $.ajax({
            type: "POST",
            url: snappixel.ajaxurl,
            data: {
                action: "snapchat_product_data",
                snap_product_id: product_id,
                security: snappixel.nonce
            },
            success: function(response) {
                if (response.success) {
                    $("body").append('<input type="hidden" id="return_response" value="' + response.data + '">');
                }
            }
        });
    };

    // Check if duplicate prevention is enabled
    var preventDuplicates = typeof snappixel !== 'undefined' && snappixel.prevent_duplicates;
    
    // Store fired events to prevent duplicates
    var firedEvents = {};
    
    // Special flag for PAGE_VIEW since it's the most common duplicate
    var pageViewFired = false;

    // Wait for the snaptr function to be available
    var checkSnaptr = setInterval(function() {
        if (typeof window.snaptr === 'function') {
            // Clear the interval once snaptr is available
            clearInterval(checkSnaptr);
            
            // Only wrap if duplicate prevention is enabled
            if (preventDuplicates) {
                // Store the original snaptr function
                var originalSnaptr = window.snaptr;
                
                // Replace with our wrapped version
                window.snaptr = function(command, eventName, eventData) {
                    // If it's not a track command, pass through to the original function
                    if (command !== 'track') {
                        return originalSnaptr.apply(this, arguments);
                    }
                    
                    // Special handling for PAGE_VIEW event
                    if (eventName === 'PAGE_VIEW') {
                        if (pageViewFired) {
                            console.log('Prevented duplicate PAGE_VIEW event');
                            return;
                        }
                        pageViewFired = true;
                    } else {
                        // For other events, create a fingerprint to detect duplicates
                        var fingerprint = createEventFingerprint(eventName, eventData);
                        
                        // Check if this event has already been fired
                        if (firedEvents[fingerprint]) {
                            console.log('Prevented duplicate event: ' + eventName);
                            return;
                        }
                        
                        // Mark this event as fired
                        firedEvents[fingerprint] = true;
                    }
                    
                    // Call the original function
                    return originalSnaptr.apply(this, arguments);
                };
                
                // Preserve the queue
                window.snaptr.queue = originalSnaptr.queue;
                window.snaptr.handleRequest = originalSnaptr.handleRequest;
            }
        }
    }, 100);
    
    /**
     * Create a fingerprint for an event to detect duplicates.
     * 
     * @param {string} eventName The name of the event.
     * @param {object} eventData The event data.
     * @return {string} A fingerprint for the event.
     */
    function createEventFingerprint(eventName, eventData) {
        // Start with the event name
        var parts = [eventName];
        
        // Add important event data if available
        if (eventData) {
            // For item_ids
            if (eventData.item_ids) {
                if (Array.isArray(eventData.item_ids)) {
                    parts.push('items:' + eventData.item_ids.join(','));
                } else {
                    parts.push('items:' + eventData.item_ids);
                }
            }
            
            // For transaction_id
            if (eventData.transaction_id) {
                parts.push('transaction:' + eventData.transaction_id);
            }
            
            // For price
            if (eventData.price) {
                parts.push('price:' + eventData.price);
            }
            
            // For page URL
            var pageUrl = window.location.href.split('?')[0]; // Remove query string
            parts.push('url:' + pageUrl);
        }
        
        // Join the parts and return
        return parts.join('|');
    }
    
    // Also handle any events that might be in the queue already
    $(document).ready(function() {
        if (preventDuplicates && window.snaptr && window.snaptr.queue) {
            // Process any queued events
            var processedQueue = [];
            
            for (var i = 0; i < window.snaptr.queue.length; i++) {
                var queueItem = window.snaptr.queue[i];
                
                // Check if this is a track command
                if (queueItem[0] === 'track') {
                    var eventName = queueItem[1];
                    var eventData = queueItem[2];
                    
                    // Special handling for PAGE_VIEW event
                    if (eventName === 'PAGE_VIEW') {
                        if (!pageViewFired) {
                            pageViewFired = true;
                            processedQueue.push(queueItem);
                        } else {
                            console.log('Prevented duplicate PAGE_VIEW event from queue');
                        }
                    } else {
                        // For other events, check fingerprint
                        var fingerprint = createEventFingerprint(eventName, eventData);
                        
                        if (!firedEvents[fingerprint]) {
                            firedEvents[fingerprint] = true;
                            processedQueue.push(queueItem);
                        } else {
                            console.log('Prevented duplicate event from queue: ' + eventName);
                        }
                    }
                } else {
                    // Non-track commands pass through
                    processedQueue.push(queueItem);
                }
            }
            
            // Replace the queue with our processed version
            window.snaptr.queue = processedQueue;
        }
    });
    
})(jQuery); 