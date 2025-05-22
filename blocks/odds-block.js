(function (blocks, editor, components, element) {
    var el = element.createElement;
    var InspectorControls = editor.InspectorControls;
    var PanelBody = components.PanelBody;
    var CheckboxControl = components.CheckboxControl;

    blocks.registerBlockType('aoc/odds-comparison', {
        title: 'Odds Comparison',
        icon: 'chart-line',
        category: 'widgets',
        attributes: {
            selectedBookmakers: {
                type: 'array',
                default: []
            }
        },
        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;
            var bookmakers = ['Bet365', 'William Hill', 'Ladbrokes', 'Unibet'];

            return el("div", {},
                el(InspectorControls, {},
                    el(PanelBody, { title: "Select Bookmakers" },
                        bookmakers.map(function (name) {
                            return el(CheckboxControl, {
                                label: name,
                                checked: attributes.selectedBookmakers.includes(name),
                                onChange: function (checked) {
                                    var updated = checked
                                        ? attributes.selectedBookmakers.concat(name)
                                        : attributes.selectedBookmakers.filter(function (b) { return b !== name; });
                                    setAttributes({ selectedBookmakers: updated });
                                }
                            });
                        })
                    )
                ),
                el("p", {}, "Selected bookmakers: " + attributes.selectedBookmakers.join(', '))
            );
        },
        save: function () {
            return null;
        }
    });
})(
    window.wp.blocks,
    window.wp.blockEditor || window.wp.editor,
    window.wp.components,
    window.wp.element
);
