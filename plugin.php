<?php
/**
 * Plugin Name: Advanced Odds Comparison
 * Description: Fetch and display live odds from multiple bookmakers with Gutenberg support.
 * Version: 1.0
 * Author: Shelly Rana
 */

// This stops the file from running directly (for security)
if (!defined('ABSPATH')) exit;

// These files help in creating admin settings and fetching data from the API
require_once plugin_dir_path(__FILE__) . 'admin/class-aoc-admin-interface.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-aoc-scraper.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-aoc-odds-converter.php';

// Create the admin settings page
new AOC_Admin_Interface();

// This function registers the Gutenberg block so it appears in the editor
function aoc_register_block() {
    wp_register_script(
        'aoc-odds-block', // Unique name for the script
        plugins_url('blocks/odds-block.js', __FILE__), // Path to the JavaScript file
        array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-data'), // Needed WordPress scripts
        filemtime(plugin_dir_path(__FILE__) . 'blocks/odds-block.js') // This makes sure browser gets the latest file
    );

    // Register the block so we can use it in editor and frontend
    register_block_type('aoc/odds-comparison', array(
        'editor_script' => 'aoc-odds-block', // Script used in the editor
        'render_callback' => 'aoc_render_odds_block' // Function that shows data on the website
    ));
}
add_action('init', 'aoc_register_block');

// Load the block's editor script when editing a post or page
add_action('enqueue_block_editor_assets', 'aoc_editor_assets');
function aoc_editor_assets() {
    wp_enqueue_script(
        'aoc-odds-block',
        plugins_url('blocks/odds-block.js', __FILE__),
        array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components'),
        filemtime(plugin_dir_path(__FILE__) . 'blocks/odds-block.js')
    );
}

// This function creates the odds table on the frontend of the website
function aoc_render_odds_block($attributes) {
    $odds = AOC_Scraper::fetch_odds(); // Get odds from API
    ob_start(); // Start storing HTML output

    // Some simple styles for the table
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

    // Show each row if we have odds
    if (!empty($odds)) {
        foreach ($odds as $entry) {
            echo '<tr>';
            echo '<td>' . esc_html($entry['bookmaker']) . '</td>'; // Bookmaker name
            echo '<td>' . esc_html($entry['market']) . '</td>'; // Market name
            echo '<td>' . esc_html($entry['team']) . '</td>'; // Team name
            echo '<td>' . esc_html($entry['odds']) . '</td>'; // Odds number

            // Show link if it's there, otherwise show a dash
            if (!empty($entry['link']) && $entry['link'] !== '#') {
                echo '<td><a href="' . esc_url($entry['link']) . '" target="_blank">Visit</a></td>';
            } else {
                echo '<td>–</td>';
            }

            echo '</tr>';
        }
    } else {
        // If no odds found, show this message
        echo '<tr><td colspan="5" style="text-align:center;color:red;">No matching odds found for selected filters.</td></tr>';
    }

    echo '</tbody></table></div>';

    return ob_get_clean(); // Send the HTML to show on the site
}
