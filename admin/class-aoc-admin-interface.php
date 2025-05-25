<?php
class AOC_Admin_Interface {
    
    public function __construct() {
        // Hook into WordPress admin menu and init actions
        add_action('admin_menu', [$this, 'create_menu']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    // Creates a new admin menu item for Odds Settings
    public function create_menu() {
        add_menu_page(
            'Odds Settings',            // Page title
            'Odds Settings',            // Menu title
            'manage_options',           // Capability required to access
            'aoc-settings',             // Menu slug
            [$this, 'settings_page']    // Callback to render the settings page
        );
    }

    // Registers settings so they can be saved via options.php
    public function register_settings() {
        register_setting('aoc-settings-group', 'aoc_api_key');
        register_setting('aoc-settings-group', 'aoc_region');
        register_setting('aoc-settings-group', 'aoc_selected_bookmakers');
        register_setting('aoc-settings-group', 'aoc_selected_markets');
        register_setting('aoc-settings-group', 'aoc_bookmaker_links');
        register_setting('aoc-settings-group', 'aoc_odds_format');

    }

    // Renders the admin settings page UI
    public function settings_page() {
        // Retrieve stored option values or use defaults
        $api_key = get_option('aoc_api_key', '');
        $selected_bookmakers = (array) get_option('aoc_selected_bookmakers', []);
        $selected_markets = (array) get_option('aoc_selected_markets', []);
        $bookmaker_links = get_option('aoc_bookmaker_links', []);
        

        // Define available options
        $bookmakers = ['Bet365', 'William Hill', 'Ladbrokes', 'Unibet'];
        $markets = ['h2h', 'spreads', 'totals'];
        ?>
        <div class="wrap">
            <h1>Odds Comparison Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('aoc-settings-group');   // Output security fields for the settings form
                do_settings_sections('aoc-settings-group'); // Output registered sections (none used here)
                ?>
                <table class="form-table">

                    <!-- Region Dropdown -->
                    <tr>
                        <th scope="row">Select Region</th>
                        <td>
                            <select name="aoc_region">
                                <option value="us" <?php selected(get_option('aoc_region', 'us'), 'us'); ?>>United States (US)</option>
                                <option value="uk" <?php selected(get_option('aoc_region'), 'uk'); ?>>United Kingdom (UK)</option>
                                <option value="eu" <?php selected(get_option('aoc_region'), 'eu'); ?>>Europe (EU)</option>
                                <option value="au" <?php selected(get_option('aoc_region'), 'au'); ?>>Australia (AU)</option>
                            </select>
                        </td>
                    </tr>

                    <!-- API Key Field -->
                    <tr>
                        <th scope="row">API Key</th>
                        <td>
                            <input type="text" name="aoc_api_key" value="<?= esc_attr($api_key) ?>" class="regular-text"/>
                        </td>
                    </tr>

                    <!-- Bookmaker Checkbox List -->
                    <tr>
                        <th scope="row">Select Bookmakers</th>
                        <td>
                            <?php foreach ($bookmakers as $bookmaker): ?>
                                <label>
                                    <input type="checkbox" name="aoc_selected_bookmakers[]" value="<?= $bookmaker ?>" 
                                    <?= in_array($bookmaker, $selected_bookmakers) ? 'checked' : '' ?>/>
                                    <?= $bookmaker ?>
                                </label><br/>
                            <?php endforeach; ?>
                        </td>
                    </tr>

                    <!-- Market Checkbox List -->
                    <tr>
                        <th scope="row">Select Markets</th>
                        <td>
                            <?php foreach ($markets as $market): ?>
                                <label>
                                    <input type="checkbox" name="aoc_selected_markets[]" value="<?= $market ?>" 
                                    <?= in_array($market, $selected_markets) ? 'checked' : '' ?>/>
                                    <?= $market ?>
                                </label><br/>
                            <?php endforeach; ?>
                        </td>
                    </tr>

                    <!-- Bookmaker Link Inputs -->
                    <tr>
                        <th scope="row">Bookmaker Links</th>
                        <td>
                            <?php foreach ($bookmakers as $bookmaker): ?>
                                <label>
                                    <?= $bookmaker ?> Link:
                                    <input type="text" name="aoc_bookmaker_links[<?= $bookmaker ?>]" 
                                    value="<?= esc_attr($bookmaker_links[$bookmaker] ?? '') ?>" class="regular-text"/>
                                </label><br/>
                            <?php endforeach; ?>
                        </td>
                    </tr>

                    <!-- Odds Format Dropdown -->
                    <tr>
                        <th scope="row">Select Odds Format</th>
                        <td>
                            <select name="aoc_odds_format">
                                <?php $format = get_option('aoc_odds_format', 'decimal'); ?>
                                <option value="decimal" <?php selected($format, 'decimal'); ?>>Decimal</option>
                                <option value="fractional" <?php selected($format, 'fractional'); ?>>Fractional</option>
                                <option value="american" <?php selected($format, 'american'); ?>>American</option>
                            </select>
                        </td>
                    </tr>



                </table>
                <?php submit_button(); // Renders a "Save Changes" button ?>
            </form>
        </div>
        <?php
    }
}
