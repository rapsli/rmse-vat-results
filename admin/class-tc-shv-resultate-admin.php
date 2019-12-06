<?php

/**
 * The admin-specific functionality of the plugin.
 */
class TcShvResultateAdmin
{

    /**
     * The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

		}
		
		/**
     * Register the stylesheets for the public-facing side of the site.
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name.'-admin', plugin_dir_url(__FILE__) . 'css/tc-shv-resultate-admin.css', array(), $this->version, 'all');
    }

    public function admin_menu()
    {
        add_options_page('SHV Resultate Optionen', 'SHV Resultate', 'manage_options', 'tc-shv-resultate-options', array(&$this, 'tc_shv_resultate_options'));
        add_menu_page('SHV Resultate Administration', 'SHV Resultate Admin', 'manage_options', 'tc-shv-resultate-admin', array(&$this, 'tc_shv_resultate_admin'), '', 75);
    }

    public function tc_shv_resultate_admin()
    {
        global $wpdb;

        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

				if(isset($_POST['submit'])) {
					?><h3>Starte Update der Clubs und Logos (nur 1x ausführen!)</h1><?php

					self::init_club_update();
				}

        $updated_table_name = $wpdb->prefix . 'tc_shv_updates';

        $sql = "select id, type, param, next_execute from $updated_table_name order by next_execute";

        $tobe_executed = $wpdb->get_results($sql);
				?>
        <h2>Manuelle Operationen</h2>
        <div>
					<form method="post" action="<?php  echo esc_html( admin_url( 'admin.php?page=tc-shv-resultate-admin' ) ); ?>">
      	  <?php wp_nonce_field('tc-shv-resultate-admin', 'init-clubs'); ?>
					<?php submit_button("Aktualisiere Clubs + Logos"); ?>
					</form>
				</div>

				<h2>Nächste Updates</h2>
				<table class="tc-shv-resultate-admin-table">
					<thead>
						<tr>
							<th>ID</th>
							<th>Typ</th>
							<th>Parameter</th>
							<th>Zeit</th>
						</tr>
					</thead>
					<tbody>
				<?php

        foreach ($tobe_executed as $execution) {
            $dt = date_i18n('d.m.Y H:i:s', strtotime($execution->next_execute));
            echo "<tr>
				<td>$execution->id</td>
				<td>$execution->type</td>
				<td>$execution->param</td>
				<td>$dt</td>
			</tr>";
        }

        ?>
				</tbody>
			</table>

				<?php
		}

		private static function init_club_update()
    {
        global $wpdb;

        $updated_table_name = $wpdb->prefix . 'tc_shv_updates';

        $club_id = get_option('tc_shv_club_id');

        $wpdb->insert($updated_table_name,
            array(
                'type' => 'club_update',
                'param' => get_option('tc_shv_club_id'),
                'next_execute' => current_time('mysql'),
            )
        );
        $wpdb->insert($updated_table_name,
            array(
                'type' => 'other_clubs_update',
                'param' => get_option('tc_shv_club_id'),
                'next_execute' => current_time('mysql'),
            )
        );
    }

    public function tc_shv_resultate_options()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        echo '<div class="wrap">
			<h1>SHV Resultate Optionen</h1>
			<form method="post" action="options.php">';

        settings_fields('tc-shv-group');
        do_settings_sections('tc-shv-group');

        submit_button();

        echo '</form></div>';
    }

    public function register_settings()
    {
        register_setting('tc-shv-group', 'tc_shv_club_id', array('type' => 'string', 'description' => 'Club ID für den REST API Call'));
        register_setting('tc-shv-group', 'tc_shv_calls_per_minute', array('type' => 'integer', 'description' => 'Anzahl Aufrufe ins VAT pro Minute'));
        register_setting('tc-shv-group', 'tc_shv_vat_user', array('type' => 'string', 'description' => 'Benutzer für den VAT API Rest Call'));
        register_setting('tc-shv-group', 'tc_shv_vat_password', array('type' => 'string', 'description' => 'Passwort für den VAT API Rest Call'));
        register_setting('tc-shv-group', 'tc_shv_rest_api_base_url', array('type' => 'string', 'description' => 'Basis-URL des SHV VAT Service'));
        register_setting('tc-shv-group', 'tc_shv_wait_time_error', array('type' => 'integer', 'description' => 'Wartezeit (Minuten) bevor nach einem Fehler erneut versucht wird'));
        register_setting('tc-shv-group', 'tc_shv_wait_time_team_group_update', array('type' => 'integer', 'description' => 'Zeit zwischen Ranglisten-Update der Teams.'));
        register_setting('tc-shv-group', 'tc_shv_wait_time_team_games_update', array('type' => 'integer', 'description' => 'Zeit zwischen Spiele-Updates der Teams (selten, da durch aktuelle Resultate / Spiele abgedeckt).'));
        register_setting('tc-shv-group', 'tc_shv_wait_time_last_results', array('type' => 'integer', 'description' => 'Zeit zwischen Abrufen von aktuellen Resultaten des Klubs.'));
        register_setting('tc-shv-group', 'tc_shv_wait_time_next_games', array('type' => 'integer', 'description' => 'Zeit zwischen Abrufen der nächsten Spiele des Vereins.'));
        register_setting('tc-shv-group', 'tc_shv_wait_time_default', array('type' => 'integer', 'description' => 'Wartezeit zwischen allen anderen Aufgaben (selten).'));
        register_setting('tc-shv-group', 'tc_shv_transient_time_report', array('type' => 'integer', 'description' => 'Wie lange soll die Verlinkung zwischen Bericht und Spiel gespeichert werden (lange).'));
        register_setting('tc-shv-group', 'tc_shv_transient_time_preview_actual', array('type' => 'integer', 'description' => 'Wie lange soll die Verlinkung zwischen Vorschau und Spiel gespeichert werden (nicht zu lange).'));
        register_setting('tc-shv-group', 'tc_shv_transient_time_preview_distant', array('type' => 'integer', 'description' => 'Wie lange soll die Verlinkung zwischen Vorschau und Spiel gespeichert werden (lange, 3 Wochen vor oder nach dem Spiel).'));
        register_setting('tc-shv-group', 'tc_shv_transient_time_nothing_actual', array('type' => 'integer', 'description' => 'Wie lange soll die Verlinkung zwischen fehlendem Bericht und Spiel gespeichert werden (nicht zu lange).'));
        register_setting('tc-shv-group', 'tc_shv_transient_time_nothing_distant', array('type' => 'integer', 'description' => 'Wie lange soll die Verlinkung zwischen fehlendem Bericht und Spiel gespeichert werden (lange, 3 Wochen vor oder nach dem Spiel).'));
        register_setting('tc-shv-group', 'tc_shv_home_names', array('type' => 'string', 'description' => 'Namen für den Heimverein.'));

        add_settings_section('tc-shv-group', 'SHV Resultate Einstellungen', array(&$this, 'tc_shv_description'), 'tc-shv-group');
        $this::create_setting('tc_shv_club_id', 'Club ID');
        $this::create_setting('tc_shv_vat_user', 'Benutzername VAT');
        $this::create_setting('tc_shv_vat_password', 'Passwort VAT');
        $this::create_setting('tc_shv_rest_api_base_url', 'Basis-URL VAT');
        $this::create_setting('tc_shv_calls_per_minute', 'Anzahl Aufrufe pro Minute ins API');
        $this::create_setting('tc_shv_wait_time_error', 'Wartezeit Fehlerfall');
        $this::create_setting('tc_shv_wait_time_team_group_update', 'Wartezeit Team-Rangliste');
        $this::create_setting('tc_shv_wait_time_team_games_update', 'Warezeit Teamspiele');
        $this::create_setting('tc_shv_wait_time_last_results', 'Wartezeit letzte Resultate');
        $this::create_setting('tc_shv_wait_time_next_games', 'Wartezeit nächste Spiele');
        $this::create_setting('tc_shv_wait_time_default', 'Wartezeit Andere');
        $this::create_setting('tc_shv_transient_time_report', 'Cache Zeit Bericht vorhanden');
        $this::create_setting('tc_shv_transient_time_preview_actual', 'Cache Vorschau (aktuell)');
        $this::create_setting('tc_shv_transient_time_preview_distant', 'Cache Zeit Vorschau (nicht aktuell)');
        $this::create_setting('tc_shv_transient_time_nothing_actual', 'Cache Zeit leer (aktuell)');
        $this::create_setting('tc_shv_transient_time_nothing_distant', 'Cache Zeit leer (nicht aktuell)');
        $this::create_setting('tc_shv_home_names', 'Namen für Heimverein (komma-separiert)');
    }

    public function tc_shv_transient_last_results()
    {
        return $this::generic_field('tc_shv_transient_last_results', 'number');
    }

    public function tc_shv_home_names()
    {
        return $this::generic_field('tc_shv_home_names');
    }

    public function tc_shv_transient_next_games()
    {
        return $this::generic_field('tc_shv_transient_next_games', 'number');
    }

    public function tc_shv_calls_per_minute()
    {
        return $this::generic_field('tc_shv_calls_per_minute', 'number');
    }

    public function tc_shv_transient_time_nothing_distant()
    {
        return $this::generic_field('tc_shv_transient_time_nothing_distant', 'number');
    }

    public function tc_shv_transient_time_nothing_actual()
    {
        return $this::generic_field('tc_shv_transient_time_nothing_actual', 'number');
    }

    public function tc_shv_transient_time_preview_distant()
    {
        return $this::generic_field('tc_shv_transient_time_preview_distant', 'number');
    }

    public function tc_shv_transient_time_preview_actual()
    {
        return $this::generic_field('tc_shv_transient_time_preview_actual', 'number');
    }

    public function tc_shv_transient_time_report()
    {
        return $this::generic_field('tc_shv_transient_time_report', 'number');
    }

    public function tc_shv_wait_time_default()
    {
        return $this::generic_field('tc_shv_wait_time_default', 'number');
    }

    public function tc_shv_wait_time_next_games()
    {
        return $this::generic_field('tc_shv_wait_time_next_games', 'number');
    }

    public function tc_shv_wait_time_team_games_update()
    {
        return $this::generic_field('tc_shv_wait_time_team_games_update', 'number');
    }

    public function tc_shv_wait_time_last_results()
    {
        return $this::generic_field('tc_shv_wait_time_last_results', 'number');
    }

    public function tc_shv_wait_time_team_group_update()
    {
        return $this::generic_field('tc_shv_wait_time_team_group_update', 'number');
    }

    public function tc_shv_wait_time_error()
    {
        return $this::generic_field('tc_shv_wait_time_error', 'number');
    }

    public function tc_shv_rest_api_base_url()
    {
        return $this::generic_field('tc_shv_rest_api_base_url');
    }

    public function tc_shv_vat_password()
    {
        return $this::generic_field('tc_shv_vat_password');
    }

    public function tc_shv_vat_user()
    {
        return $this::generic_field('tc_shv_vat_user');
    }

    public function tc_shv_club_id()
    {
        return $this::generic_field('tc_shv_club_id');
    }

    private function create_setting($id, $title)
    {
        add_settings_field($id, $title, array(&$this, $id), 'tc-shv-group', 'tc-shv-group');
    }

    public function tc_shv_description()
    {
        echo '<p>Mit den Einstellungen unten kann das SHV Resultate Plugin konfiguriert werden</p>';
    }

    private function generic_field($name, $type = 'text', $size = 30)
    {
        $option = get_option($name);
        echo "<input id='$name' name='$name' size='$size' type='$type' value='{$option}' / />";
    }

}
