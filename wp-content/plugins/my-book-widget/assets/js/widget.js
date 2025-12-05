jQuery(window).on('elementor/frontend/init', function() {
    elementorFrontend.hooks.addAction('frontend/element_ready/my_book_widget.default', function($scope, $) {
        var $container = $scope.find('.mbw-book-widget-container');
        var $grid = $container.find('.mbw-book-grid');
        var $searchInput = $container.find('.mbw-search-input');
        var $sortSelect = $container.find('.mbw-sort-select');
        // Convert NodeList/jQuery object to a stable array for sorting later if needed, 
        // but we can just query children each time if list is dynamic. 
        // Since it's static after render, getting them once is fine, but re-querying ensures we sort what's there.
        
        function filterItems() {
            var query = $searchInput.val().toLowerCase();
            var $items = $grid.find('.mbw-book-item');
            
            $items.each(function() {
                var $item = $(this);
                // Ensure data is string
                var name = String($item.data('name') || '');
                var author = String($item.data('author') || '');
                
                if (name.includes(query) || author.includes(query)) {
                    $item.show();
                } else {
                    $item.hide();
                }
            });
        }

        // Search Event
        $searchInput.on('input', filterItems);

        // Sort Event
        $sortSelect.on('change', function() {
            var sortBy = $(this).val();
            if (!sortBy || sortBy === 'default') return;

            var $items = $grid.find('.mbw-book-item');
            var itemsArray = $items.get();
            
            itemsArray.sort(function(a, b) {
                var valA = $(a).data(sortBy);
                var valB = $(b).data(sortBy);
                
                // Handle empty values
                if (valA === undefined) valA = '';
                if (valB === undefined) valB = '';

                // Date sorting
                if (sortBy === 'date') {
                    // Create date objects. If invalid, treat as 0 (epoch).
                    var dateA = new Date(valA).getTime() || 0;
                    var dateB = new Date(valB).getTime() || 0;
                    return dateA - dateB;
                }
                
                // String sorting
                valA = String(valA).toLowerCase();
                valB = String(valB).toLowerCase();

                if (valA < valB) return -1;
                if (valA > valB) return 1;
                return 0;
            });
            
            // Re-append sorted items
            $.each(itemsArray, function(idx, item) {
                $grid.append(item);
            });
        });
    });
});
