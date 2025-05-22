<?php
class AOC_Scraper {
    public static function fetch_odds() {
        $api_key = get_option('aoc_api_key');
        $selected_bookmakers = get_option('aoc_selected_bookmakers', []);
$selected_markets = get_option('aoc_selected_markets', []);
if (!is_array($selected_bookmakers)) $selected_bookmakers = [];
if (!is_array($selected_markets)) $selected_markets = [];

        $bookmaker_links = get_option('aoc_bookmaker_links', []);
        $region = get_option('aoc_region', 'us');

        if (!$api_key) return [];

        $market_param = count($selected_markets) > 0 ? implode(',', $selected_markets) : '';
        $url = "https://api.the-odds-api.com/v4/sports/upcoming/odds/?regions={$region}&markets={$market_param}&oddsFormat=american&apiKey={$api_key}";

        $response = wp_remote_get($url);
        if (is_wp_error($response)) {
            return [['bookmaker' => 'Error', 'odds' => 'API fetch failed']];
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        $odds = [];
        if (is_array($data)) {
            foreach ($data as $event) {
                foreach ($event['bookmakers'] ?? [] as $bookmaker) {
                    $name = $bookmaker['title'];
                    if (is_array($selected_bookmakers) && count($selected_bookmakers) > 0 && !in_array($name, $selected_bookmakers)) continue;

                    foreach ($bookmaker['markets'] ?? [] as $market_data) {
                        if (is_array($selected_markets) && count($selected_markets) > 0 && !in_array($market_data['key'], $selected_markets)) continue;

                        foreach ($market_data['outcomes'] ?? [] as $outcome) {
                            $odds[] = [
                                'bookmaker' => $name,
                                'market' => $market_data['key'],
                                'team' => $outcome['name'],
                                'odds' => $outcome['price'],
                                'link' => $bookmaker_links[$name] ?? '#'
                            ];
                        }
                    }
                }
            }
        }

        return $odds;
    }
}
