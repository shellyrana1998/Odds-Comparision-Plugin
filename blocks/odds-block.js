(function (blocks, editor, components, element) {
    var el = element.createElement; // Shortcut to create elements
    var InspectorControls = editor.InspectorControls; // Panel area in the editor sidebar
    var PanelBody = components.PanelBody; // A collapsible panel component
    var CheckboxControl = components.CheckboxControl; // Checkbox UI component

    // Registering a custom Gutenberg block
    blocks.registerBlockType('aoc/odds-comparison', {
        title: 'Odds Comparison',        // Block title shown in the block inserter
        icon: 'chart-line',             // Dashicon or custom icon
        category: 'widgets',            // Category in which block appears
        attributes: {
            selectedBookmakers: {
                type: 'array',          // Data type for storing selected bookmakers
                default: []             // Default is an empty array
            }
        },

        // Edit function for block editor UI
        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;

            // Static list of available bookmakers
            var bookmakers = ['Bet365', 'William Hill', 'Ladbrokes', 'Unibet'];

            // Return JSX-like virtual DOM
            return el("div", {},
                el(InspectorControls, {}, // Sidebar inspector controls
                    el(PanelBody, { title: "Select Bookmakers" },
                        bookmakers.map(function (name) {
                            return el(CheckboxControl, {
                                label: name,
                                checked: attributes.selectedBookmakers.includes(name),
                                onChange: function (checked) {
                                    // Update state when a checkbox is toggled
                                    var updated = checked
                                        ? attributes.selectedBookmakers.concat(name)
                                        : attributes.selectedBookmakers.filter(function (b) { return b !== name; });

                                    setAttributes({ selectedBookmakers: updated }); // Save updated selection
                                }
                            });
                        })
                    )
                ),

                // Display selected bookmakers inside the editor
                el("p", {}, "Selected bookmakers: " + attributes.selectedBookmakers.join(', '))
            );
        },

        // Save function is null — indicating a dynamic block rendered with PHP
        save: function () {
            return null;
        }
    });
})(
    window.wp.blocks,
    window.wp.blockEditor || window.wp.editor, // Compatibility for older WP versions
    window.wp.components,
    window.wp.element
);
