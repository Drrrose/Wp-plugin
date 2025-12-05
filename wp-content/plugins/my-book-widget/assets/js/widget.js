jQuery(window).on('elementor/frontend/init', function() {
    elementorFrontend.hooks.addAction('frontend/element_ready/my_book_widget.default', function($scope, $) {
        var $container = $scope.find('.mbw-book-widget-container');
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
        
        function filterItems() {
            var query = $searchInput.val().toLowerCase();
            var $items = $grid.find('.mbw-book-item');
            var hasVisible = false;

            $items.each(function() {
                var $item = $(this);
                var name = String($item.data('name') || '');
                var author = String($item.data('author') || '');
                
                if (name.includes(query) || author.includes(query)) {
                    $item.stop().fadeIn(300).css('display', 'flex'); // Maintain flex layout
                    hasVisible = true;
                } else {
                    $item.stop().fadeOut(200);
                }
            });

            // Optional: Show "No results" message logic could go here
        }

        // Debounce Search
        var searchTimeout;
        $searchInput.on('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(filterItems, 300);
        });

        // Sort Event
        $sortSelect.on('change', function() {
            var sortBy = $(this).val();
            if (!sortBy || sortBy === 'default') return;

            var $items = $grid.find('.mbw-book-item');
            var itemsArray = $items.get();
            
            itemsArray.sort(function(a, b) {
                var valA = $(a).data(sortBy);
                var valB = $(b).data(sortBy);
                
                if (valA === undefined) valA = '';
                if (valB === undefined) valB = '';

                if (sortBy === 'date') {
                    var dateA = new Date(valA).getTime() || 0;
                    var dateB = new Date(valB).getTime() || 0;
                    return dateA - dateB;
                }
                
                valA = String(valA).toLowerCase();
                valB = String(valB).toLowerCase();

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
    });
});