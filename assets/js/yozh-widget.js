( function ($) {
	
	//
	
	
	
	$( document ).on( 'change', '.yozh-widget-active-filter', function () {
		this.form.submit();
	} );
	
	$( document ).on( 'keyup', 'input[name="filter_search"].yozh-widget-active-filter', function () {
		if ( $( this ).val().length > 3 || $( this ).val().length == 0 ) {
			this.form.submit();
		}
	} );
	
	$( document ).on( 'change', 'select.yozh-widget-nested-select', function () {
		
		//var _wrapperClass = '.yozh-widget-nested-select-nested-group';
		var _$target = $( this );
		
		if ( _$target.val() ) {
			
			var _ajaxConfig = {
				url : _$target.data( 'url' ),
				data : {
					value : _$target.val()
				},
				//method : _method || 'get',
			};
			
			$.ajax( _ajaxConfig )
				
				.done( function ( _response, status, xhr ) {
					
					_$target.triggerHandler( 'yozh.ActiveNestedSelect.done', [ _response, status, xhr, _$target ] );
					
					$( _$target.data( 'selector' ) ).html( _response );
					//_$target.parents( _wrapperClass ).children( _wrapperClass ).removeClass( 'hide' );
					
					var _callback = _$target.data( 'done' );
					
					if ( typeof _callback == 'function' ) {
						return call_user_func( _callback, null, _response, status, xhr, _$target );
					}
					
				} )
				
				.fail( function ( _response, status, xhr ) {
					
					_$target.triggerHandler( 'yozh.ActiveNestedSelect.fail', [ _response, status, xhr, _$target ] );
					
					var _callback = _$target.data( 'fail' );
					
					if ( typeof _callback == 'function' ) {
						return call_user_func( _callback, null, _response, status, xhr, _$target );
					}
					
				} )
			;
			
		}
		else {
			_$target.parents( _wrapperClass ).children( _wrapperClass ).addClass( 'hide' );
		}
		
	} );
	
} )(jQuery);

/*
function search(_search, _url, _$container) {
	
	if (_search.length > 3 || _search.length === 0) {
		$.ajax({
			url: _url,
			dataType: 'json',
			data: {
				'search': _search,
			},
			cache: false,
			success: function ( _response ) {
				_$container.html( _response.result.html );
			}
		});
		
	}
	return false;
}
*/