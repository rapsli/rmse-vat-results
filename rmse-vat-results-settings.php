<?php

function rmse_vat_results_settings_init()
{
	register_setting('rmse_vat_results', 'rmse_vat_results_options');

	register_setting('rmse_vat_results', 'club_id', array('type' => 'string', 'description' => 'Club ID für den REST API Call'));
	register_setting('rmse_vat_results', 'vat_user', array('type' => 'string', 'description' => 'Benutzer für den VAT API Rest Call'));
	register_setting('rmse_vat_results', 'vat_password', array('type' => 'string', 'description' => 'Passwort für den VAT API Rest Call'));
	register_setting('rmse_vat_results', 'logo_url', array('type' => 'string', 'description' => 'Logo URL called with sprintf(..., team_id, club_id, width, height)', 'default' => 'https://www.handball.ch/images/logo/%s.png?fallbackType=club&fallbackId=%s&height=%d&width=%d&rmode=pad&format=png'));

	add_settings_section('rmse_vat_results_section', __('VAT Results Settings', 'rmse-vat-results'), 'rmse_vat_results_section_callback', 'rmse_vat_results');

	add_settings_field(
		'rmse_vat_results_club_id',
		__('Club ID', 'rmse-vat-results'),
		'rmse_vat_results_text',
		'rmse_vat_results',
		'rmse_vat_results_section',
		array(
			'label_for' => 'club_id',
			'type' => 'text',
		)
	);

	add_settings_field(
		'rmse_vat_results_vat_user',
		__('Username VAT', 'rmse-vat-results'),
		'rmse_vat_results_text',
		'rmse_vat_results',
		'rmse_vat_results_section',
		array(
			'label_for' => 'vat_user',
			'type' => 'text',
		)
	);

	add_settings_field(
		'rmse_vat_results_vat_password',
		__('Password VAT', 'rmse-vat-results'),
		'rmse_vat_results_text',
		'rmse_vat_results',
		'rmse_vat_results_section',
		array(
			'label_for' => 'vat_password',
			'type' => 'password',
		)
	);

	add_settings_field(
		'rmse_vat_results_logo_url',
		__('Logo URL', 'rmse-vat-results'),
		'rmse_vat_results_text',
		'rmse_vat_results',
		'rmse_vat_results_section',
		array(
			'label_for' => 'logo_url',
			'type' => 'text',
			'default' => 'https://www.handball.ch/images/logo/%s.png?fallbackType=club&fallbackId=%s&height=%d&width=%d&rmode=pad&format=png'
		)
	);
}

/**
 * Register our wporg_settings_init to the admin_init action hook.
 */
add_action('admin_init', 'rmse_vat_results_settings_init');

function rmse_vat_results_section_callback($args)
{
	?>
	<p id="<?php echo esc_attr($args['id']); ?>">
		<?php esc_html_e('You need to configure the plugin, all information can be found in VAT (https://vat.handball.ch) - assuming you have the rights else someone else in the club can give them.', 'rmse-vat-results'); ?>
	</p>
	<?php
}

/**
 * Fields callback function
 *
 * WordPress has magic interaction with the following keys: label_for, class.
 * - the "label_for" key value is used for the "for" attribute of the <label>.
 * - the "class" key value is used for the "class" attribute of the <tr> containing the field.
 * Note: you can add custom key value pairs to be used inside your callbacks.
 *
 * @param array $args
 */
function rmse_vat_results_text($args)
{
	// Get the value of the setting we've registered with register_setting()
	$options = get_option('rmse_vat_results_options');
	$option_value = isset($options[$args['label_for']]) ? esc_attr($options[$args['label_for']]) : (isset($args['default']) ? $args['default'] : '') ;

?>
	<input id="<?php echo esc_attr($args['label_for']); ?>" type="<?php echo esc_attr($args['type']); ?>"
		size="150"
		name="rmse_vat_results_options[<?php echo esc_attr($args['label_for']); ?>]"
		value="<?php echo $option_value; ?>" />
	<?php
}

/**
 * Add the top level menu page.
 */
function rmse_vat_results_options_page()
{
	add_options_page(
		__('SHV Result Options', 'rmse_vat_results'),
		__('SHV Results Options', 'rmse_vat_results'),
		'manage_options',
		'rmse_vat_results',
		'rmse_vat_results_options_page_html'
	);
}

add_action('admin_menu', 'rmse_vat_results_options_page');

/**
 * Top level menu callback function
 */
function rmse_vat_results_options_page_html()
{
	// check user capabilities
	if (!current_user_can('manage_options')) {
		return;
	}

	// add error/update messages

	// check if the user have submitted the settings
	// WordPress will add the "settings-updated" $_GET parameter to the url
	if (isset($_GET['settings-updated'])) {
		// add settings saved message with the class of "updated"
		// add_settings_error('wporg_messages', 'wporg_message', __('Settings Saved', 'wporg'), 'updated');

		// try to load the teams here

		$response_code = rmse_vat_results_test_retrieve_club_info();

		if ($response_code !== 200) {
			add_settings_error('wporg_messages', 'wporg_message', sprintf(__('Club Info could not be retrieved, response code %s', 'rmse-vat-results'), $response_code));
		} else {
			rmse_vat_results_update_club_teams();
		}
	}

	// show error/update messages
	settings_errors('wporg_messages');
	?>
	<div class="wrap">
		<h1>
			<?php esc_html_e(get_admin_page_title()); ?>
		</h1>
		<form action="options.php" method="post">
			<?php
			// output security fields for the registered setting "wporg"
			settings_fields('rmse_vat_results');
			// output setting sections and their fields
			// (sections are registered for "wporg", each field is registered to a specific section)
			do_settings_sections('rmse_vat_results');
			// output save settings button
			submit_button(__('Save Settings', 'wporg'));
			?>
		</form>

		<hr />

		<h2>Teams</h2>
		<?php
		$teams = rmse_vat_results_get_club_teams();

		if (isset($teams) && is_array($teams)) {
			?>
			<div class="rmse-vat-results-settings-teams">
				<table>
					<thead>
						<tr>
							<th>ID</th>
							<th>Logo</th>
							<th>Name</th>
							<th>Liga</th>
							<th>Gruppe</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($teams as $team) {
							?>
							<tr>
								<td>
									<?php echo $team->teamId ?>
								</td>
								<td>
									<img src="<?php echo rmse_vat_results_team_logo($team->teamId, $team->clubId, 35, 35); ?>" alt="Logo <?php $team->teamName; ?>" width="35" height="35" />
								</td>
								<td>
									<?php echo $team->teamName ?>
								</td>
								<td>
									<?php echo $team->leagueLong . ' (' . $team->leagueShort . ')' ?>
								</td>
								<td>
									<?php echo $team->groupText ?>
								</td>
							</tr>
							<?php
						}

						unset($team);
						?>
					</tbody>
				</table>
			</div>
			<?php
		} else {
			echo '<div>' . esc_html_e('No teams loaded yet', 'rmse-vat-results') . '</div>';
		}
		?>
	</div>
	<?php
}
?>
