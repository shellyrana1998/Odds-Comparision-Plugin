<?php
/**
 * Plugin Name: Advanced Odds Comparison
 * Description: Fetch and display live odds from multiple bookmakers with Gutenberg support.
 * Version: 1.0
 * Author: Shelly Rana
 */

if (!defined('ABSPATH')) exit;

require_once plugin_dir_path(__FILE__) . 'admin/class-aoc-admin-interface.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-aoc-scraper.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-aoc-odds-converter.php';

new AOC_Admin_Interface();

function aoc_register_block() {
    wp_register_script(
        'aoc-odds-block',
        plugins_url('blocks/odds-block.js', __FILE__),
        array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-data'),
        filemtime(plugin_dir_path(__FILE__) . 'blocks/odds-block.js')
    );

    register_block_type('aoc/odds-comparison', array(
        'editor_script' => 'aoc-odds-block',
        'render_callback' => 'aoc_render_odds_block'
    ));
}
add_action('init', 'aoc_register_block');
add_action('enqueue_block_editor_assets', 'aoc_editor_assets');
function aoc_editor_assets() {
    wp_enqueue_script(
        'aoc-odds-block',
        plugins_url('blocks/odds-block.js', __FILE__),
        array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components'),
        filemtime(plugin_dir_path(__FILE__) . 'blocks/odds-block.js')
    );
}

function aoc_render_odds_block($attributes) {
    $odds = AOC_Scraper::fetch_odds();
    ob_start();

    echo '<style>
        .aoc-odds-table table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .aoc-odds-table th, .aoc-odds-table td {
            border: 1px solid #ccc;
            padding: 8px 12px;
            text-align: left;
        }
        .aoc-odds-table th {
            background: #f4f4f4;
        }
    </style>';

    echo '<div class="aoc-odds-table">';
    echo '<table><thead><tr><th>Bookmaker</th><th>Market</th><th>Team</th><th>Odds</th><th>Link</th></tr></thead><tbody>';

    // Loop through each fetched odd entry
    if (!empty($odds)) {
        foreach ($odds as $entry) {
            echo '<tr>';
            echo '<td>' . esc_html($entry['bookmaker']) . '</td>';
            echo '<td>' . esc_html($entry['market']) . '</td>';
            echo '<td>' . esc_html($entry['team']) . '</td>';
            echo '<td>' . esc_html($entry['odds']) . '</td>';

            // Only show link if it's not empty
            if (!empty($entry['link']) && $entry['link'] !== '#') {
                echo '<td><a href="' . esc_url($entry['link']) . '" target="_blank">Visit</a></td>';
            } else {
                echo '<td>–</td>';
            }

            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="5" style="text-align:center;color:red;">No matching odds found for selected filters.</td></tr>';
    }

    echo '</tbody></table></div>';

    return ob_get_clean();
}
