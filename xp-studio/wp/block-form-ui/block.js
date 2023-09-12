(function () {
    let blocks = window.wp.blocks;
    let element = window.wp.element;
    let el = element.createElement;
    // CHANGE ME
    let block_name = 'xps/form-ui';

    console.log('block.js', block_name);

    // warning: block name must be unique and the same as block name in block.json
    // namespace/block-name (lowercase and not weird characters...)
    blocks.registerBlockType(block_name, {
        edit: function (props) {
            // will let WP add selection and toolbar on block in editor
            let bps = window.wp.blockEditor.useBlockProps({
                className: 'block-form ' + (props.attributes.className ?? ''),
            });

            // add inner blocks
            // let innerBlocks = wp.blockEditor.InnerBlocks;
            let ib = el(wp.blockEditor.InnerBlocks);
            let placeholder = '( ' 
            + (props.attributes.ui_label ?? '?')
            + ' | '
            + (props.attributes.ui_type ?? '?') 
            + ' | '
            + (props.attributes.ui_name ?? '?')
            + ' )';
            return el('div', bps, ib, placeholder);
        },
        save: function (props) {
            let saved = wp.blockEditor.useBlockProps.save();
            // save inner blocks
            let ib = el(wp.blockEditor.InnerBlocks.Content);

            return el('div', saved, ib);
        },
    });

    // add inspector block controls
    let init = async function () {
        // import ./block-vue.js
        // let block_vue = await import("./block-vue.js");

        console.log(block_name, 'init / wp', wp);

        let my_controls = function (BlockEdit) {
            // console.log('withInspectorControls', BlockEdit);
    
            return function (props) {
                // WARNING: is called each time a block is selected
                // can use props.isSelected to check if the block is selected
                // can use props.name to check the block name
                // console.log('withInspectorControls / props', props);
                let name = props.name;
                if (name !== block_name) {
                    // keeps the current inspector controls
                    return el(BlockEdit, props);    
                }
                else {
                    // add extra controls to the block inspector
                    let my_nodes = el(wp.blockEditor.InspectorControls, {},
                        el(wp.components.PanelBody, {},
                            el('h3', {}, block_name),
                            el(wp.components.TextControl, {
                                label: 'type',
                                // sync ok when value is changed elsewhere
                                value: props.attributes.ui_type,
                                onChange: function (value) {
                                    props.setAttributes({ ui_type: value });
                                }
                            }),
                            el(wp.components.TextControl, {
                                label: 'label',
                                // sync ok when value is changed elsewhere
                                value: props.attributes.ui_label,
                                onChange: function (value) {
                                    props.setAttributes({ ui_label: value });
                                }
                            }),
                            el(wp.components.TextControl, {
                                label: 'name',
                                // sync ok when value is changed elsewhere
                                value: props.attributes.ui_name,
                                onChange: function (value) {
                                    props.setAttributes({ ui_name: value });
                                }
                            }),
                            el(wp.components.TextControl, {
                                label: 'html',
                                // sync ok when value is changed elsewhere
                                value: props.attributes.ui_html,
                                onChange: function (value) {
                                    props.setAttributes({ ui_html: value });
                                }
                            }),
                        )
                    );
    
                    // mandatory: keep the current blocks
                    return el(wp.element.Fragment, {}, el(BlockEdit, props), my_nodes);
                }
            };
        }
    
        wp.hooks.addFilter(
            'editor.BlockEdit',
            block_name + '-wic',
            wp.compose.createHigherOrderComponent(my_controls, 'withInspectorControls'),
        );
    }

    init();

})();

