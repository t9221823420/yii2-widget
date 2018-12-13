(function($) {
	
	$('.yozh-widget-tree-view i').on('click', function( e ){
		
		e.stopImmediatePropagation();
		
		$nested = $(this).siblings('.nested');
		
		if( $nested.hasClass('yozh-on') ){
			$nested.removeClass('yozh-on').addClass('yozh-off');
			$(this).removeClass('glyphicon-minus').addClass('glyphicon-plus');
		}
		else{
			$nested.removeClass('yozh-off').addClass('yozh-on');
			$(this).removeClass('glyphicon-plus').addClass('glyphicon-minus');
		}
		
	});

})(jQuery);