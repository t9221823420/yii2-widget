$( function () {
	
	var _confirm = function ( _data, _$context ) {
		
		if ( typeof window[ _data ] === 'function' ) {
			return window[ _data ]( _$context );
		}
		
		return true;
		
	}
	
	
	$( document ).on( 'click', '.yozh-active-button', function ( e ) {
		
		e.stopPropagation();
		e.preventDefault();
		
		var _method;
		var _confirmResult = true;
		
		var _$context = $( this );
		
		if ( _$context.attr( 'confirm' ) ) {
			
			_$context.triggerHandler( 'yozh.ActiveButton.beforeConfirm', [ _$context.attr( 'confirm' ), _$context ] );
			
			_confirmResult = _confirm( _$context.attr( 'confirm' ), _$context );
			
			_$context.triggerHandler( 'yozh.ActiveButton.afterConfirm', [ _confirmResult, _$context ] );
			
		}
		
		if ( _confirmResult ) {
			
			if ( _$context.attr( 'url' ) ) {
				
				if ( _$context.attr( 'method' ) ) {
					_method = _$context.attr( 'method' );
				}
				else {
					_method = 'get';
				}
				
				_$context.triggerHandler( 'yozh.ActiveButton.beforeSend', [ _$context ] );
				
				$.ajax( {
						url : _$context.attr( 'url' ),
						data : _$context.data(),
						method : _method
					} )
					.done( function ( _response, status, xhr ) {
						
						_$context.triggerHandler( 'yozh.ActiveButton.done', [ _response, status, xhr, _$context ] );
						
						var _callback = _$context.attr( 'done' );
						
						if ( typeof _callback === 'function' ) {
							_callback( _response, _$context );
						}
						else if ( typeof window[ _callback ] === 'function' ) {
							window[ _callback ]( _response, _$context );
						}
						else {
							try {
								eval( _callback );
							}
							catch ( error ) {
								console.log( error );
							}
						}
						
						
					} )
					.fail( function ( _response, status, xhr ) {
						
						_$context.triggerHandler( 'yozh.ActiveButton.fail', [ _response, status, xhr, _$context ] );
						
						var _callback = _$context.attr( 'fail' );
						
						if ( typeof _callback === 'function' ) {
							_callback( _response, _$context );
						}
						else if ( typeof window[ _callback ] === 'function' ) {
							window[ _callback ]( _response, _$context );
						}
						else {
							try {
								eval( _callback );
							}
							catch ( error ) {
								console.log( error );
							}
						}
						
						
					} )
				;
			}
			else if ( _$context.attr( 'action' ) ) {
				
				var _callback = _$context.attr( 'action' );
				
				_$context.triggerHandler( 'yozh.ActiveButton.beforeAction', [ _callback, _$context ] );
				
				if ( typeof _callback === 'function' ) {
					_callback( _$context );
				}
				else if ( typeof window[ _callback ] === 'function' ) {
					window[ _callback ]( _$context );
				}
				else {
					try {
						eval( _callback );
					}
					catch ( error ) {
						console.log( error );
					}
				}
				
				_$context.triggerHandler( 'yozh.ActiveButton.afterAction', [ _callback, _$context ] );
				
			}
		}
		
	} );
	
} );