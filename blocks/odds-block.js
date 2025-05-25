(function (blocks, element) {
    var el = element.createElement;

    blocks.registerBlockType('aoc/odds-comparison', {
        title: 'Odds Comparison',
        icon: 'chart-line',
        category: 'widgets',

        edit: function () {
            return el("div", {},
                el("p", {}, "Odds Comparison block is active.")
            );
        },

        save: function () {
            return null; // PHP will render the front-end via dynamic block
        }
    });
})(
    window.wp.blocks,
    window.wp.element
);
