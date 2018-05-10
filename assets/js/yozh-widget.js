$(function () {


    $(document).on('click', '.yozh-active-button', function (e) {

        var _method;

        var _$owner = $(this);

        if( _$owner.attr('method') ){
            _method = _$owner.attr('method');
        }
        else{
            _method = 'get';
        }

        $.ajax({
            url: _$owner.attr('action'),
            data: _$owner.data(),
            method: _method
        })
        .done(function ( _response ) {

            var _callback = _$owner.attr('done');

            if ( typeof window[ _callback ] === 'function' ) {
                window[ _callback ]( _response, _$owner );
            }

        })
        .fail(function ( _response ) {

            var _callback = _$owner.attr('fail');

            if ( typeof window[ _callback ] === 'function' ) {
                window[ _callback ]( _response, _$owner );
            }

        })
        ;

    });
        
});