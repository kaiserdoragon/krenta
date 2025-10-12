(function () {
    var el = wp.element.createElement,
        blocks = wp.blocks,
        InnerBlocks = wp.editor.InnerBlocks;

    blocks.registerBlockType('custom/section', {
        title: 'sectionタグ囲み',
        icon: 'welcome-widgets-menus',
        category: 'layout',
        supports: {
            anchor: true
        },
        keywords: [],
        attributes: {
            content: {
                type: 'array',
                source: 'children',
                selector: 'div',
            },
        },
        edit: function edit(_ref) {
            var attributes = _ref.attributes,
                className = _ref.className;

            return wp.element.createElement(
                'div',
                { className: className },
                wp.element.createElement(InnerBlocks, { templateLock: false })
            );
        },
        save: function (_ref2) {
            var className = _ref2.className;
            return wp.element.createElement(
                'section',
                { className: className },
                wp.element.createElement(InnerBlocks.Content, null)
            );
        },
    });


    blocks.registerBlockType('custom/div', {
        title: 'divタグ囲み',
        icon: 'welcome-widgets-menus',
        category: 'layout',
        supports: {
            anchor: true
        },
        keywords: [],
        attributes: {
            content: {
                type: 'array',
                source: 'children',
                selector: 'div',
            },
        },
        edit: function edit(_ref) {
            var attributes = _ref.attributes,
                className = _ref.className;

            return wp.element.createElement(
                'div',
                { className: className },
                wp.element.createElement(InnerBlocks, { templateLock: false })
            );
        },
        save: function (_ref2) {
            var className = _ref2.className;
            return wp.element.createElement(
                'div',
                { className: className },
                wp.element.createElement(InnerBlocks.Content, null)
            );
        },
    });
})();