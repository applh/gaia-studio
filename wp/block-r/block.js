( function ( blocks, element ) {
    var el = element.createElement;

    blocks.registerBlockType( 'xperia/block-r', {
        edit: function () {
            return el( 'p', {}, 'Hello World (from the editor).' );
        },
        save: function () {
            return el( 'p', {}, 'Hola mundo (OUPS).' );
        },
    } );
} )( window.wp.blocks, window.wp.element );
