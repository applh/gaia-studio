<?php
header('Content-Type: application/javascript');
$block_shortname = $post->post_name;
$block_name = "xps-block/$block_shortname";
$block_title = $post->post_title;

// get infos on block type from registry
$block_type = WP_Block_Type_registry::get_instance()->get_registered("$block_name");

?>
/*
<?php print_r($block_type); ?>
*/
console.log('xps-block.php', '<?php echo $block_name ?>', '<?php echo $block_title ?>');

// WARNING: can't be async
// loading modules will fail block registration
( function ( blocks, element ) {
    var el = element.createElement;

    const scriptUrl = document.currentScript.src;
    console.log('current url', scriptUrl);
        
    // warning: block name must be unique and the same as block name in block.json
    // namespace/block-name (lowercase and not weird characters...)
    blocks.registerBlockType( '<?php echo $block_name ?>', {
        // WARNING: can't be async
        edit: function (props) {
            // will let WP add selection and toolbar on block in editor
            let bps = window.wp.blockEditor.useBlockProps({
                className: '<?php echo $block_shortname ?>' + (props.attributes.className ?? ''),
            });

            // WANING: will be called for each refresh and block in editor
            (async function () {
                let vue = await import('/assets/vue-esm-prod-334.js');
                let block_common = await import('/assets/wp/block-common.js');
                console.log('current url after async', scriptUrl, bps);
            
            })();

            // add inner blocks
            // let innerBlocks = wp.blockEditor.InnerBlocks;
            let ib = el( wp.blockEditor.InnerBlocks );

            return el( 'div', bps, '<?php echo $block_title ?>', ib );
        },
        // WARNING: can't be async
        save: function (props) {
            let saved = wp.blockEditor.useBlockProps.save();
            // save inner blocks
            let ib = el( wp.blockEditor.InnerBlocks.Content );
            return el( 'div', saved, '<?php echo $block_title ?>', ib );
        },
    } );

    // load modules async
    // can access to variables in current scope
    //(async function () {
    //    let vue = await import('/assets/vue-esm-prod-334.js');
    //    let block_common = await import('/assets/wp/block-common.js');
    //    console.log('current url after async', scriptUrl);
    //
    //})()

} )( window.wp.blocks, window.wp.element );

// load modules async
// can't access to variables in current scope
//(async function () {
//    let vue = await import('/assets/vue-esm-prod-334.js');
//    let block_common = await import('/assets/wp/block-common.js');
//})()
