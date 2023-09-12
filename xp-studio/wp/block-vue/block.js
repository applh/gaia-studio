(function (blocks, element) {
    let block_name = 'xps/vue';

    console.log('block.js', block_name);

    var el = element.createElement;

    // warning: block name must be unique and the same as block name in block.json
    // namespace/block-name (lowercase and not weird characters...)
    blocks.registerBlockType(block_name, {
        edit: function (props) {
            console.log('edit', props);

            // useSelect
            // https://developer.wordpress.org/block-editor/reference-guides/packages/packages-data/#useselect
            let { custom } = wp.data.useSelect(
                async (select) => {
                    // wait for edit to be called by Gutenberg
                    // WILL IMPORT ONLY ONCE (ESM)
                    let block_vue = await import("./block-vue.js");
                    return {
                        custom: {},
                    }
                },
                []
            );

            // BUILD HTML FOR EDITOR
            // will let WP add selection and toolbar on block in editor
            let bps = wp.blockEditor.useBlockProps({
                className: 'block-basic ' + (props.attributes.className ?? ''),
            });

            // add tag xce-control-1
            let xce1 = el('xce-control-1', {});
            return el('div', bps, xce1, 'Hello World (from the editor).');
        },
        save: function (props) {
            let saved = wp.blockEditor.useBlockProps.save();
            return el('div', saved, 'Hola mundo (from the frontend).');
        },
    });

    let init = async function () {
        // import ./block-vue.js
        // let block_vue = await import("./block-vue.js");

        console.log(block_name, 'init / wp', wp);

        var el = wp.element.createElement;

        var withInspectorControls = wp.compose.createHigherOrderComponent(function (
            BlockEdit
        ) {
            return function (props) {
                let name = props.name;
                if (name !== block_name) {
                    // keeps the current inspector controls
                    return el(BlockEdit, props);
                }
                else {
                    return el(
                        wp.element.Fragment,
                        {},
                        el(BlockEdit, props),
                        el(
                            wp.blockEditor.InspectorControls,
                            {},
                            el(wp.components.PanelBody, {}, block_name),
                            el('xce-control-2', {}),
                        )
                    );
                }
            };
        },
            'withInspectorControls');

        wp.hooks.addFilter(
            'editor.BlockEdit',
            'my-plugin/with-inspector-controls',
            withInspectorControls
        );
    }

    init();
})(window.wp.blocks, window.wp.element);


// other



