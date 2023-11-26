(function($) {
    $(document).ready(function() {
        
        //// *=====* Ajax Filtering Code Here *======* ////
            var container = $('#ajax-filter-container');
            var categoryContainer = $('#ajax-category-container');

            $(document).on('click', '.filter-button', function(e) {
                var selectedCategory = $(this).data('category');
                loadContent(selectedCategory);
            });

            $(document).on('click', '.filter-all', function(e) {
                loadContent('all');
            });

            function loadContent(category) {
                var data = {
                    action: 'ajax_filter',
                    category: category,
                };
                $.ajax({
                    type: 'POST',
                    url: ajax_filter_params.ajax_url,
                    data: data,
                    success: function(response) {
                        container.html(response);
                    },
                    error: function(error) {
                        console.error('Ajax request for loading content failed:', error);
                    }
                });
            }


        //// *=====* Ajax Loadmore Button Code Here *======* ////
        var page = 2; 
            $('#load-more-button').on('click', function () {
                var data = {
                    action: 'ajax_load_more_portfolio',
                    page: page,
                };

                $.ajax({
                    type: 'POST',
                    url: ajax_filter_params.ajax_url,
                    data: data,
                    success: function (response) {
                        if (response) {
                            $('.load-more-container').before(response);
                            page++;
                        } else {
                            $('#load-more-button').remove(); 
                            $('.load-more-container').text("No more Post!");
                        }
                    },
                    error: function (error) {
                        console.error('Ajax request for loading more portfolio items failed:', error);
                    },
                });
            });
        //// *=====* Ajax Filtering and  loadmor button Code Here *======* ////

        




        
    }); //end document ready
})(jQuery);
