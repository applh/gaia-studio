console.log('HELLO from my test-2');

// WARNING: this js code is not loaded as module
// so variables declared here are global
// there can be conflicts between different blocks

// IIFE: use a anonymous function to avoid name conflicts
// IIFE = Immediately Invoked Function Expression
(function () {
    let blocks = window.wp.blocks;
    let element = window.wp.element;
    let my_block_name = 'xps/test-2';
    console.log('register... ' + my_block_name);
    let el = element.createElement;

    let edit = function (props) {
        console.log('edit / props', props);

        var content;
        // React can't handle promises, so we need to use a state variable
        // console.log('props', props);

        content = 'Hello World (from the editor).' + (props.attributes?.className ?? '');

        // try to change the value of the attribute
        // props.attributes.hello_text = Math.floor(1000 * Math.random()) + ' Hello World (from the editor).';

        // create a textarea with the content
        let textarea = el('textarea', {
            // WARNING: 2 ways sync not working with defaultValue and inspectorControls
            // defaultValue: props.attributes.hello_text,
            // https://react.dev/reference/react-dom/components/input#controlling-an-input-with-a-state-variable
            value: props.attributes.hello_text,
            onChange: function (event) {
                // console.log('event', event);
                props.setAttributes({ hello_text: event.target.value });
            }
        });
        let input = el('input', {
            className: 'my_meta',
            // WARNING: 2 ways sync not working with defaultValue and inspectorControls
            // defaultValue: props.attributes.my_meta,
            // https://react.dev/reference/react-dom/components/input#controlling-an-input-with-a-state-variable
            value: props.attributes.my_meta,
            onChange: function (event) {
                // console.log('event', event);
                props.setAttributes({ my_meta: event.target.value });
            }
        });

        // create a div.ajax
        // WARNING: edit will be called each time a change occurs to the block
        let ajax = el('div', { className: 'ajax' }, props.attributes.my_meta);

        // will let WP add selection and toolbar on block in editor
        let bps = window.wp.blockEditor.useBlockProps({
            className: 'my_test ' + (props.attributes.className ?? ''),
        });

        // https://wordpress.github.io/gutenberg/?path=/docs/components-textareacontrol--docs
        // add a TextControl
        let tc = el(wp.components.TextControl, {
            // label: 'My custom control',
            // sync ok when value is changed elsewhere
            value: props.attributes.my_meta,
            onChange: function (value) {
                props.setAttributes({ my_meta: value });
            }
        });
        // add a TextareaControl
        let tac = el(wp.components.TextareaControl, {
            // label: 'My custom control',
            // sync ok when value is changed elsewhere
            value: props.attributes.my_meta,
            onChange: function (value) {
                props.setAttributes({ my_meta: value });
            }
        });

        // add a InnerBlocks
        // https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/nested-blocks-inner-blocks/
        let ib = el(wp.blockEditor.InnerBlocks);
        // add a RichText
        let rt = el(wp.blockEditor.RichText, {
            tagName: 'p',
            value: props.attributes.my_meta,
            onChange: function (value) {
                props.setAttributes({ my_meta: value });
            }
        });

        // useSelect 
        // https://developer.wordpress.org/block-editor/reference-guides/packages/packages-data/#useselect
        let { custom } = wp.data.useSelect(
            async (select) => {
                // WILL IMPORT ONLY ONCE (ESM)
                let app_vue = await import ("./app-vue.js");

                return {
                    custom: {}
                }
            },
            []
        );

        // let vue_app = el('div', { id: 'vue-app' }, 'vue app');

        let xve = el('div', {className: 'to1'}, 'HELLO TO1');
        let xve2 = el('xps-ve', {});
        // build html for editor
        return el(
            'div',  // tag
            bps, // tag attributes
            // children list
            rt,
            // vue_app,
            xve2,
            ib,
            // tc,
            // tac,
            // content,
            // input,
            // textarea,
            // ajax,
        );
    }

    // mandatory with InnerBlocks to save the inner blocks
    // other props are saved automatically as json
    let save = function (props) {
        let saved = wp.blockEditor.useBlockProps.save();

        return el('div', saved, el(wp.blockEditor.InnerBlocks.Content));
    }

    // WARNING: this must be the same as the block name in PHP
    // WE CAN OVERLOAD THE DEFAULTS LOADED FROM PHP
    // apiVersion: 3,
    // title: 'XP DYNAMITE',
    // icon: 'megaphone',
    // category: 'widgets',
    // WARNING: edit is called to render the block in the editor
    // gutenberg will call this function on any change to the block

    blocks.registerBlockType(my_block_name, { edit, save });

    // add a inspector control 
    // https://developer.wordpress.org/block-editor/reference-guides/filters/block-filters/


    let my_controls = function (BlockEdit) {
        // console.log('withInspectorControls', BlockEdit);

        return function (props) {
            // WARNING: is called each time a block is selected
            // can use props.isSelected to check if the block is selected
            // can use props.name to check the block name
            // console.log('withInspectorControls / props', props);
            let name = props.name;
            if (name !== my_block_name) {
                // keeps the current inspector controls
                return el(BlockEdit, props);
                // WARNING: removes the blocks in the editor
                // return null;

                // we can change the inspector controls and the block edit
                // return el('div', {}, 'not my block');

            }
            else {
                // add extra controls to the block inspector
                let my_nodes = el(wp.blockEditor.InspectorControls, {},
                    el(wp.components.PanelBody, {},
                        el('h3', {}, 'My custom control'),
                        el(wp.components.TextControl, {
                            label: 'My custom control',
                            // sync ok when value is changed elsewhere
                            value: props.attributes.my_meta,
                            onChange: function (value) {
                                props.setAttributes({ my_meta: value });

                            }
                        }),
                        el(wp.components.TextControl, {
                            label: 'My custom control 2',
                            // sync ok when value is changed elsewhere
                            value: props.attributes.hello_text,
                            onChange: function (value) {
                                props.setAttributes({ hello_text: value });

                            }
                        })
                    )
                );

                // mandatory: keep the current blocks
                return el(wp.element.Fragment, {}, el(BlockEdit, props), my_nodes);
            }
        };
    }

    wp.hooks.addFilter(
        'editor.BlockEdit',
        'xp-studio/with-inspector-controls',
        wp.compose.createHigherOrderComponent(my_controls, 'withInspectorControls'),
    );
})();

