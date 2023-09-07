console.log('HELLO from my block.js');


let looper = null;

async function my_api(props) {
    // try fetching the content from the server
    // with CORS enabled, this works
    let url = "http://gaia.test:4321/api";
    // url = "/my-api"; 
    // url = "http://localhost:8666/api/scraps";

    let response = await fetch(url,
        {
            cache: 'no-cache',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        }
    );
    let json = await response.json();
    console.log('json', json);
    console.log('props', props);
    // props.setAttributes({ hello_text: Math.floor(1000 * Math.random()) + ' AJAX' });
    props.setAttributes({ my_test: json.now });

    // set a counter to update the block every second
    let counter = 0;
    if (looper == null) {
        console.log('starting looper');
        looper = setInterval(function () {
            // props.setAttributes({ hello_text: Math.floor(1000 * Math.random()) + ' AJAX' });
            // props.setAttributes({ my_test: counter++ });
        }, 1000);

    }

}

function register_my_block(blocks, element, data) {
    console.log('register_my_block');
    let
        useSelect = data.useSelect,
        registerBlockType = blocks.registerBlockType,
        el = element.createElement;


    // WARNING: this must be the same as the block name in PHP
    registerBlockType('xperia/block-d', {
        // WE CAN OVERLOAD THE DEFAULTS LOADED FROM PHP
        // apiVersion: 3,
        // title: 'XP DYNAMITE',
        // icon: 'megaphone',
        // category: 'widgets',
        // WARNING: edit is called to render the block in the editor
        // gutenberg will call this function on any change to the block
        edit: function (props) {
            console.log('edit / props', props);

            var content;
            // React can't handle promises, so we need to use a state variable
            // console.log('props', props);

            content = 'Hello World (from the editor).' + (props.attributes?.className ?? '');

            // try to change the value of the attribute
            // props.attributes.hello_text = Math.floor(1000 * Math.random()) + ' Hello World (from the editor).';

            // create a textarea with the content
            let textarea = el('textarea', {
                defaultValue: props.attributes.hello_text,
                onChange: function (event) {
                    // console.log('event', event);
                    props.setAttributes({ hello_text: event.target.value });
                }
            });
            let input = el('input', {
                className: 'my_meta',
                defaultValue: props.attributes.my_meta,
                onChange: function (event) {
                    // console.log('event', event);
                    props.setAttributes({ my_meta: event.target.value });
                }
            });

            // create a div.ajax
            // WARNING: edit will be called each time a change occurs to the block
            let ajax = el('div', { className: 'ajax' }, props.attributes.my_test);
            // FIXME: the select callback called each time a change occurs to the block ?!
            // try to avoid calling the API multiple times
            if (looper === null) {
                console.log('looper', looper);
                // fetch data from the server /my-api with useSelect
                const { data } = useSelect(
                    function (select) {
                        console.log('select', select);
                        my_api(props);
                        return {
                            data: select('core/editor').getEditedPostAttribute('content')
                        };
                    },
                    []
                );
                // console.log('data', data);
            }

            return el(
                'div',
                {},
                content,
                input,
                textarea,
                ajax
            );
        },
        // WARNING: save is called each time props.setAttributes is called
        // save: function (props) {
        //     console.log('save / props', props);
        //     // not working
        //     // let saved = props.save();
        //     // console.log('saved', saved);

        //     let be = window.wp.blockEditor;
        //     let bp = be.useBlockProps;
        //     let saved = bp.save();

        //     // console.log('be', be);
        //     // console.log('bp', bp);
        //     // console.log('saved', saved);
        //     return el('p', saved, props.attributes.hello_text);
        // },
        // supports: { color: { gradients: true, link: true }, align: true },
    });
}

register_my_block(
    window.wp.blocks,
    window.wp.element,
    window.wp.data
    // NOT WORKING: window.wp.blockEditor is undefined when called
    // window.wp.blockEditor
);

// add a inspector control 
// https://developer.wordpress.org/block-editor/reference-guides/filters/block-filters/

let el = wp.element.createElement;

let withInspectorControls = wp.compose.createHigherOrderComponent(function (
    BlockEdit
) {
    console.log('withInspectorControls', BlockEdit);

    return function (props) {
        // WARNING: is called each time a block is selected
        // can use props.isSelected to check if the block is selected
        // can use props.name to check the block name
        console.log('withInspectorControls / props', props);
        let name = props.name;
        if (name !== 'xperia/block-d') {
            // keeps the current inspector controls
            return el(BlockEdit, props);
            // WARNING: removes the blocks in the editor
            // return null;

            // we can change the inspector controls and the block edit
            // return el('div', {}, 'not my block');

        }
        else {
            return el(
                wp.element.Fragment,
                {},
                el(BlockEdit, props),
                el(
                    wp.blockEditor.InspectorControls,
                    {},
                    el(wp.components.PanelBody, {}, 
                        el('h3', {}, 'My custom control'),
                        el(wp.components.TextControl, {
                            label: 'My custom control',
                            // sync ok when value is changed elsewhere
                            value: props.attributes.my_meta,
                            onChange: function (value) {
                                props.setAttributes({ my_meta: value });
                                // WARNING: need also to refresh the block
                                
                            }
                        }),
                        el(wp.components.TextControl, {
                            label: 'My custom control 2',
                            // sync ok when value is changed elsewhere
                            value: props.attributes.hello_text,
                            onChange: function (value) {
                                props.setAttributes({ hello_text: value });
                                // WARNING: need also to refresh the block
                                
                            }
                        })
                    )
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