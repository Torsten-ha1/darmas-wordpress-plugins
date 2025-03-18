/**
 * Darmas Carmarket Plugin JavaScript
 */

(function ($) {
  'use strict';

  // Initialize when document is ready
  $(document).ready(function () {
    // Initialize the carmarket functionality
    initDarmasCarmarket();
  });

  /**
   * Initialize the carmarket functionality
   */
  function initDarmasCarmarket() {
    // Add click event for "View Details" buttons
    $('.darmas-btn-primary').on('click', function (e) {
      // e.preventDefault();

      // Get the card element
      var $card = $(this).closest('.darmas-card');

      // Toggle additional details or perform other actions
      // This is a placeholder for future functionality

      console.log('Vehicle card clicked');
    });

    // Add lazy loading for images
    if ('loading' in HTMLImageElement.prototype) {
      // Browser supports native lazy loading
      document.querySelectorAll('.darmas-card-img').forEach(function (img) {
        img.setAttribute('loading', 'lazy');
      });
    } else {
      // Fallback for browsers that don't support lazy loading
      // You could add a lazy loading library here if needed
    }

    // Initialize any responsive behaviors
    handleResponsiveLayout();
    $(window).on('resize', handleResponsiveLayout);
  }

  /**
   * Handle responsive layout adjustments
   */
  function handleResponsiveLayout() {
    // This function can be expanded to handle specific responsive behaviors
    // For example, adjusting card heights to be uniform in each row

    // Reset card body heights
    $('.darmas-card-body').css('height', 'auto');

    // Only equalize heights on larger screens
    if ($(window).width() > 768) {
      // Group cards by rows and equalize heights
      equalizeCardHeights();
    }
  }

  /**
   * Equalize card heights in each row
   */
  function equalizeCardHeights() {
    // Find all cards
    var $cards = $('.darmas-card');

    // Reset heights
    $cards.find('.darmas-card-body').css('height', 'auto');

    // Group cards by their position (row)
    var rows = {};
    $cards.each(function () {
      var $this = $(this);
      var top = $this.offset().top;

      if (!rows[top]) {
        rows[top] = [];
      }

      rows[top].push($this);
    });

    // For each row, find the tallest card body and set all cards in that row to that height
    $.each(rows, function (top, cards) {
      var tallest = 0;

      // Find the tallest card body
      $(cards).each(function () {
        var bodyHeight = $(this).find('.darmas-card-body').outerHeight();
        tallest = Math.max(tallest, bodyHeight);
      });

      // Set all card bodies in this row to the tallest height
      $(cards).each(function () {
        $(this)
          .find('.darmas-card-body')
          .css('height', tallest + 'px');
      });
    });
  }
})(jQuery);
