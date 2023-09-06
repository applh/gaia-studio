console.log('HELLO');

(function (blocks, element) {
    var el = element.createElement,
        registerBlockType = blocks.registerBlockType
        ;

    // WARNING: this must be the same as the block name in PHP
    registerBlockType('xperia/block-d', {
        apiVersion: 2,
        title: 'XP DYNAMITE',
        icon: 'megaphone',
        category: 'widgets',
        edit: function () {
            var content;
            content = 'Hello World (from the editor).';
            return el(
                'div', 
                [], 
                content
            );
        },
        // supports: { color: { gradients: true, link: true }, align: true },
    });
})(
    window.wp.blocks,
    window.wp.element
);