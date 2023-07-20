<?php

function tc_shv_results_settings_init()
{
	register_setting('tc_shv_results', 'tc_shv_results_options');

	register_setting('tc_shv_results', 'tc_shv_club_id', array('type' => 'string', 'description' => 'Club ID für den REST API Call'));
	register_setting('tc_shv_results', 'tc_shv_vat_user', array('type' => 'string', 'description' => 'Benutzer für den VAT API Rest Call'));
	register_setting('tc_shv_results', 'tc_shv_vat_password', array('type' => 'string', 'description' => 'Passwort für den VAT API Rest Call'));

	add_settings_section('tc_shv_results_section', __('VAT Results Settings', 'tc-shv-results'), 'tc_shv_results_section_callback', 'tc_shv_results');

	add_settings_field(
		'tc_shv_results_club_id',
		__('Club ID', 'tc-shv-results'),
		'tc_shv_results_text',
		'tc_shv_results',
		'tc_shv_results_section',
		array(
			'label_for' => 'tc_shv_results_club_id',
			'type' => 'text'
		)
	);

	add_settings_field(
		'tc_shv_results_username',
		__('Username VAT', 'tc-shv-results'),
		'tc_shv_results_text',
		'tc_shv_results',
		'tc_shv_results_section',
		array(
			'label_for' => 'tc_shv_results_username',
			'type' => 'text'
		)
	);

	add_settings_field(
		'tc_shv_results_password',
		__('Password VAT', 'tc-shv-results'),
		'tc_shv_results_text',
		'tc_shv_results',
		'tc_shv_results_section',
		array(
			'label_for' => 'tc_shv_results_password',
			'type' => 'password'
		)
	);
}

/**
 * Register our wporg_settings_init to the admin_init action hook.
 */
add_action('admin_init', 'tc_shv_results_settings_init');

function tc_shv_results_section_callback($args)
{
	?>
	<p id="<?php echo esc_attr($args['id']); ?>">

	<p id="<?php echo esc_attr($args['id']); ?>">
		<?php __('<p>You need to configure the plugin, all information can be found in <a href="https://vat.handball.ch/">VAT</a> - assuming you have the rights else someone else in the club can give them.', 'tc-shv-results'); ?>

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
function tc_shv_results_text($args)
{
	// Get the value of the setting we've registered with register_setting()
	$options = get_option('tc_shv_results_options');
	?>
	<input id="<?php echo esc_attr($args['label_for']); ?>" type="<?php echo esc_attr($args['type']); ?>"
		name="tc_shv_results_options[<?php echo esc_attr($args['label_for']); ?>]"
		value="<?php echo isset( $options[ $args['label_for']]) ? esc_attr($options[$args['label_for']]) : '' ?>" />
	<?php
}

/**
 * Add the top level menu page.
 */
function tc_shv_results_options_page()
{
	add_menu_page(
		__ ('SHV Result Options', 'tc_shv_results'),
		__ ('SHV Results Options', 'tc_shv_results'),
		'manage_options',
		'tc_shv_results',
		'tc_shv_results_options_page_html'
	);
}

add_action('admin_menu', 'tc_shv_results_options_page');


/**
 * Top level menu callback function
 */
function tc_shv_results_options_page_html()
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
		// TODO: do I need to replace here something?
		add_settings_error('wporg_messages', 'wporg_message', __('Settings Saved', 'wporg'), 'updated');
	}

	// show error/update messages
	settings_errors('wporg_messages');
	?>
	<div class="wrap">
		<h1>
			<?php echo esc_html(get_admin_page_title()); ?>
		</h1>
		<form action="options.php" method="post">
			<?php
			// output security fields for the registered setting "wporg"
			settings_fields('tc_shv_results');
			// output setting sections and their fields
			// (sections are registered for "wporg", each field is registered to a specific section)
			do_settings_sections('tc_shv_results');
			// output save settings button
			submit_button(__('Save Settings', 'wporg'));
			?>
		</form>
	</div>
	<?php
}
