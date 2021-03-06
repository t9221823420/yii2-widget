( function ( $ ) {
	
	yozh.ActiveButton = {
		
		TEMPLATE : '<button class="yozh-widget yozh-widget-active-button yozh-widget-active-button-{type} {class}">{label}</button>',
		
		WIDGET_CLASS : 'yozh-widget-active-button',
		
	};
	
	$( document ).on( 'click', '.yozh-widget-active-button', function ( e ) {
		
		e.stopPropagation();
		e.preventDefault();
		
		var _method;
		var _confirmResult = true;
		
		var _$target = $( this );
		
		var _process = function () {
			
			var _result = true;
			
			if ( _$target.attr( 'action' ) ) {
				
				var _callback = _$target.attr( 'action' );
				
				_$target.triggerHandler( 'yozh.ActiveButton.beforeAction', [ _$target ] );
				
				//try {
				_result = call_user_func( _callback, null, _$target );
				/*}
				catch ( error ) {
					
					console.log( error );
					
				}*/
				
				/*
				if ( typeof _callback === 'function' ) {
					_callback( _$target );
				}
				else if ( typeof window[ _callback ] === 'function' ) {
					window[ _callback ]( _$target );
				}
				else {
					try {
						eval( _callback );
					}
					catch ( error ) {
						console.log( error );
					}
				}
				*/
				
				_$target.triggerHandler( 'yozh.ActiveButton.afterAction', [ _$target ] );
				
			}
			
			if ( _result && _$target.attr( 'url' ) ) {
				
				if ( _$target.attr( 'method' ) ) {
					_method = _$target.attr( 'method' );
				}
				else {
					_method = 'get';
				}
				
				var _ajaxConfig = {
					url : _$target.attr( 'url' ),
					data : _$target.data(),
					method : _method
				};
				
				_$target.triggerHandler( 'yozh.ActiveButton.beforeSend', [ _ajaxConfig, _$target ] );
				
				$.ajax( _ajaxConfig )
					
					.done( function ( _response, status, xhr ) {
						
						_$target.triggerHandler( 'yozh.ActiveButton.done', [ _response, status, xhr, _$target ] );
						
						var _callback = _$target.attr( 'done' );
						
						if ( typeof _callback == 'function' ) {
							//try {
							return call_user_func( _callback, null, _response, status, xhr, _$target );
							/*}
							catch ( error ) {
								console.log( error );
							}*/
						}
						
					} )
					
					.fail( function ( _response, status, xhr ) {
						
						if ( _response.status < 400 ) {
							return;
						}
						
						_$target.triggerHandler( 'yozh.ActiveButton.fail', [ _response, status, xhr, _$target ] );
						
						var _callback = _$target.attr( 'fail' );
						
						if ( typeof _callback == 'function' ) {
							//try {
							return call_user_func( _callback, null, _response, status, xhr, _$target );
							/*}
							catch ( error ) {
								console.log( error );
							}*/
						}
						
					} )
				;
			}
			
			return _result;
		}
		
		if ( _$target.attr( 'confirm' ) ) {
			
			var _confirmOptions = _$target.attr( 'confirmOptions' );
			
			_$target.triggerHandler( 'yozh.ActiveButton.beforeConfirm', [ _$target.attr( 'confirm' ), _$target ] );
			
			//try {
			_confirmResult = call_user_func( _$target.attr( 'confirm' ), null, _$target, _confirmOptions );
			/*}
			catch ( error ) {
				
				console.log( error );
				
				_confirmResult = false;
			}*/
			
			_$target.triggerHandler( 'yozh.ActiveButton.afterConfirm', [ _confirmResult, _$target ] );
			
		}
		
		if ( _confirmResult && $.isFunction( _confirmResult.promise ) ) {
			return $.when( _confirmResult ).done( _process );
		}
		else if ( _confirmResult === true ) {
			// by default it always run on any ActionButtons
			// inside _process cheks if ActionButton has url or action
			// for example^ button NO always run _process, but by default it has not url nor action
			return _process();
		}
		
	} )
	;
	
} )( jQuery );