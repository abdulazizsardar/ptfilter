(function($) {
    "use strict";  

    if ($('.grid').length) {
        $('.grid').imagesLoaded(function() {
            $('.portfolio-filter').on('click', 'button', function() {
                var filterValue = $(this).attr('data-filter');
                $grid.isotope({
                    filter: filterValue
                });
            });
            var $grid = $('.grid').isotope({

                animationOptions: {
                 duration: 750,
                 easing: 'linear',
                 queue: false
               },

                itemSelector: '.grid-item',
                percentPosition: true,
                masonry: {
                    columnWidth: '.grid-item',
                }
            });
        });
    }
})(jQuery);