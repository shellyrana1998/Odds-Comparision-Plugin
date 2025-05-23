<h2>🎯 AOC Odds Settings Plugin</h2>

<p>This WordPress plugin provides an admin interface to configure API settings, bookmakers, markets, and regional preferences for an odds comparison system.</p>

<h2>🚀 Features</h2>
<ul>
  <li>Admin menu for Odds Settings</li>
  <li>Region selector (US, UK, EU, AU)</li>
  <li>Enter and save API key securely</li>
  <li>Select preferred bookmakers and markets</li>
  <li>Assign custom affiliate links to each bookmaker</li>
</ul>

<h2>🛠️ Installation</h2>
<ol>
  <li>Download or clone the repository.</li>
  <li>Upload the plugin folder to the <code>/wp-content/plugins/</code> directory.</li>
  <li>Activate the plugin through the WordPress admin dashboard.</li>
  <li>Navigate to <strong>Odds Settings</strong> from the admin sidebar menu.</li>
</ol>

<h2>⚙️ Configuration</h2>
<p>Fill out the following fields under <strong>Odds Settings</strong>:</p>
<ul>
  <li><strong>API Key</strong> – Enter your sports data API key.</li>
  <li><strong>Region</strong> – Select your operational region (e.g., US, UK).</li>
  <li><strong>Bookmakers</strong> – Choose which bookmakers to display odds for.</li>
  <li><strong>Markets</strong> – Select market types like h2h, spreads, or totals.</li>
  <li><strong>Bookmaker Links</strong> – Provide your custom affiliate URLs for each bookmaker.</li>
</ul>

<h2>📁 File Structure</h2>
<pre>
/aoc-odds-settings
│
├── aoc-admin-interface.php    ← Main class handling admin settings
├── readme.md                  ← This file
└── ... other plugin files
</pre>

<h2>📌 Notes</h2>
<ul>
  <li>Ensure you have the required capability <code>manage_options</code> to access the settings.</li>
  <li>Use <code>esc_attr()</code> and <code>selected()</code> for secure output in HTML forms.</li>
</ul>

