<?php
header('Content-Type: application/javascript');
$block_name = "xps-block/" . $post->post_name;
$block_title = $post->post_title;

// get infos on block type from registry
$block_type = WP_Block_Type_registry::get_instance()->get_registered("$block_name");

?>
/*
<?php print_r($block_type); ?>
*/
console.log('xps-block.php', '<?php echo $block_name ?>', '<?php echo $block_title ?>');

( function ( blocks, element ) {
    var el = element.createElement;

    // warning: block name must be unique and the same as block name in block.json
    // namespace/block-name (lowercase and not weird characters...)
    blocks.registerBlockType( '<?php echo $block_name ?>', {
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

