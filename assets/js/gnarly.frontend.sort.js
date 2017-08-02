(function($){

	var sort_container	=	$( gnarly_elements.container );
	var sort_item		=	gnarly_elements.container_children;

		sort_container.sortable({

		update: function( event, ui ){			
			
			// reset / create array
			var sort_post_ids = [];

			$(this).find( sort_item ).each( function(index, val ) {

				$(val).attr('data-gnarly-order', index);			

				var new_order = $(val).attr('data-gnarly-order');
				var post_id = $(val).attr('data-gnarly-id');			
				
				sort_post_ids.push({
					order	: new_order,
					id		: post_id
				});

			});
			
			
			//console.log(sort_post_ids);
			
			$.ajax({				
				type		: "post",
				dataType	: "json",
				url			: gnarly.ajaxurl,
				data		: { action: "gnarly_sort", posts: sort_post_ids },     
				beforeSend	: function(){
					sort_container.animate( { opacity: '0.5' } );
				},  
				success		: function( response ) {
					sort_container.animate( { opacity: '1' } );

					if( response.gnarly ){

					}

				} 

			});		
			
			
			
		}

	});

	$( "[data-gnarly-sort]" ).disableSelection();
	
})(jQuery);