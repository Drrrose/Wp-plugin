(function($) {
    'use strict';

    /**
     * Initialize the Book Widget functionality
     */
    var MyBookWidget = function($scope, $) {
        var $container = $scope.find('.mbw-book-widget-container');
        if (!$container.length) return;

        var $grid = $container.find('.mbw-book-grid');
        var $searchInput = $container.find('.mbw-search-input');
        var $sortSelect = $container.find('.mbw-sort-select');

        // Add simple fade animation class to items
        $grid.find('.mbw-book-item').css({
            'opacity': 0,
            'transform': 'translateY(20px)',
            'transition': 'opacity 0.4s ease-out, transform 0.4s ease-out'
        });

        // Staggered fade-in on load
        setTimeout(function() {
            $grid.find('.mbw-book-item').each(function(i) {
                var $el = $(this);
                setTimeout(function() {
                    $el.css({ 'opacity': 1, 'transform': 'translateY(0)' });
                }, i * 50);
            });
        }, 100);

        // Filter Function
        function filterItems() {
            var query = $searchInput.val().toLowerCase();
            var $items = $grid.find('.mbw-book-item');
            
            $items.each(function() {
                var $item = $(this);
                // Use .attr() for safer string retrieval
                var name = ($item.attr('data-name') || '').toLowerCase();
                var author = ($item.attr('data-author') || '').toLowerCase();
                
                if (name.indexOf(query) > -1 || author.indexOf(query) > -1) {
                    $item.stop(true, true).fadeIn(300).css('display', 'flex'); 
                } else {
                    $item.stop(true, true).fadeOut(200);
                }
            });
        }

        // Debounce Search
        var searchTimeout;
        $searchInput.off('input').on('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(filterItems, 300);
        });

        // Sort Event
        $sortSelect.off('change').on('change', function() {
            var sortBy = $(this).val();
            if (!sortBy || sortBy === 'default') return;

            var $items = $grid.find('.mbw-book-item');
            var itemsArray = $items.get();
            
            itemsArray.sort(function(a, b) {
                var valA = $(a).attr('data-' + sortBy);
                var valB = $(b).attr('data-' + sortBy);
                
                if (valA === undefined || valA === null) valA = '';
                if (valB === undefined || valB === null) valB = '';

                if (sortBy === 'date') {
                    var dateA = new Date(valA).getTime() || 0;
                    var dateB = new Date(valB).getTime() || 0;
                    return dateA - dateB;
                }
                
                valA = valA.toLowerCase();
                valB = valB.toLowerCase();

                if (valA < valB) return -1;
                if (valA > valB) return 1;
                return 0;
            });
            
            // Animate sorting
            $grid.css('opacity', 0.5);
            setTimeout(function() {
                $.each(itemsArray, function(idx, item) {
                    $grid.append(item);
                });
                $grid.animate({ opacity: 1 }, 300);
            }, 200);
        });
    };

    // Hook into Elementor JS
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/my_book_widget.default', function($scope) {
            MyBookWidget($scope, $);
            $scope.find('.mbw-book-widget-container').attr('data-mbw-init', 'true');
        });
    });

    // Fallback: Manual Init if Elementor hook didn't fire or crashed
    $(document).ready(function() {
        // Check for any uninitialized widgets on the page
        $('.elementor-widget-my_book_widget').each(function() {
            var $widget = $(this);
            var $container = $widget.find('.mbw-book-widget-container');
            
            // If container exists but hasn't been marked as initialized
            if ($container.length && $container.attr('data-mbw-init') !== 'true') {
                MyBookWidget($widget, $);
                $container.attr('data-mbw-init', 'true');
            }
        });
    });

})(jQuery);