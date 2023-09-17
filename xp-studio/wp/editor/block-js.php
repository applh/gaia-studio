<?php
$block_name = $_GET["bn"] ?? 'xps/block-dynamic';
$block_title = $_GET["bt"] ?? $block_name;
?>

( function ( blocks, element ) {
    var el = element.createElement;

    // warning: block name must be unique and the same as block name in block.json
    // namespace/block-name (lowercase and not weird characters...)
    blocks.registerBlockType( 'xps/<?php echo $block_name ?>', {
        'title': '<?php echo $block_title ?>',
        'category': 'text',
        edit: function (props) {
            // will let WP add selection and toolbar on block in editor
            let bps = window.wp.blockEditor.useBlockProps({
                className: 'block-basic ' + (props.attributes.className ?? ''),
            });

            return el( 'p', bps, '<?php echo $block_title ?>' );
        },
        save: function (props) {
            let saved = wp.blockEditor.useBlockProps.save();
            return el( 'p', saved, '<?php echo $block_title ?>' );
        },
    } );
} )( window.wp.blocks, window.wp.element );
