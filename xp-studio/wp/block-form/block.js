( function ( blocks, element ) {
    var el = element.createElement;

    // warning: block name must be unique and the same as block name in block.json
    // namespace/block-name (lowercase and not weird characters...)
    blocks.registerBlockType( 'xps/form', {
        edit: function (props) {
                    // will let WP add selection and toolbar on block in editor
            let bps = window.wp.blockEditor.useBlockProps({
                className: 'block-form ' + (props.attributes.className ?? ''),
            });

            return el( 'p', bps, '(your contact form)' );
        },
        save: function (props) {
            let saved = wp.blockEditor.useBlockProps.save();
            return el( 'p', saved, 'contact form' );
        },
    } );
} )( window.wp.blocks, window.wp.element );
