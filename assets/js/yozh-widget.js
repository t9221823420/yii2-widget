$( function () {
	
	//
	
	yozh.ActiveButton = {
		
		TEMPLATE : '<button class="yozh-widget yozh-active-button yozh-active-button-{type} {class}">{label}</button>',
		
		WIDGET_CLASS : 'yozh-active-button',
		
	};
	
	$( document ).on( 'click', '.yozh-active-button', function ( e ) {
		
		e.stopPropagation();
		e.preventDefault();
		
		var _method;
		var _confirmResult = true;
		
		var _$target = $( this );
		
		var _process = function(){
			
			if ( _$target.attr( 'url' ) ) {
				
				if ( _$target.attr( 'method' ) ) {
					_method = _$target.attr( 'method' );
				}
				else {
					_method = 'get';
				}
				
				_$target.triggerHandler( 'yozh.ActiveButton.beforeSend', [ _$target ] );
				
				$.ajax( {
						url : _$target.attr( 'url' ),
						data : _$target.data(),
						method : _method
					} )
					.done( function ( _response, status, xhr ) {
						
						_$target.triggerHandler( 'yozh.ActiveButton.done', [ _response, status, xhr, _$target ] );
						
						var _callback = _$target.attr( 'done' );
						
						if( _callback ){
							try {
								call_user_func( _callback, null, _response, status, xhr, _$target );
							}
							catch ( error ) {
								console.log( error );
							}
						}
						
					} )
					.fail( function ( _response, status, xhr ) {
						
						_$target.triggerHandler( 'yozh.ActiveButton.fail', [ _response, status, xhr, _$target ] );
						
						var _callback = _$target.attr( 'fail' );
						
						if( _callback ){
							try {
								call_user_func( _callback, null, _response, status, xhr, _$target );
							}
							catch ( error ) {
								console.log( error );
							}
						}
						
					} )
				;
			}
			
			else if ( _$target.attr( 'action' ) ) {
				
				var _callback = _$target.attr( 'action' );
				var _result;
				
				_$target.triggerHandler( 'yozh.ActiveButton.beforeAction', [ _$target ] );
				
				try {
					_result = call_user_func( _callback, null, _$target );
				}
				catch ( error ) {
					
					console.log( error );
					
				}
				
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
				
				return _result
				
			}
			
		}
		
		if ( _$target.attr( 'confirm' ) ) {
			
			_$target.triggerHandler( 'yozh.ActiveButton.beforeConfirm', [ _$target.attr( 'confirm' ), _$target ] );
			
			try {
				_confirmResult = call_user_func( _$target.attr( 'confirm' ) );
			}
			catch ( error ) {
				
				console.log( error );
				
				_confirmResult = false;
			}
			
			_$target.triggerHandler( 'yozh.ActiveButton.afterConfirm', [ _confirmResult, _$target ] );
			
		}
		
		if ( _confirmResult && $.isFunction(_confirmResult.promise) ) {
			$.when( _confirmResult ).done( _process );
		}
		else if ( _confirmResult === true ) {
			_process();
		}
		
	} )
	;
	
} )
;