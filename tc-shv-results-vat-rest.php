<?php
function authorization_headers()
{
	$options = get_option('tc_shv_results_options');
	if (isset($options)) {
		$username = $options['vat_user'];
		$password = $options['vat_password'];

		return array(
			'headers' => array(
				'Authorization' => 'Basic ' . base64_encode("$username:$password"),
			)
		);
	} else {
		return array();
	}
}

function shv_url($url)
{
	// TODO allow to use the test api too!
	return 'https://clubapi.handball.ch/rest/' . $url;
}

function execute_request($url)
{
	$http_url = shv_url($url);

	$result = wp_remote_request($http_url, authorization_headers());

	$response_code = wp_remote_retrieve_response_code($result);
	if ($response_code === 200) {
		return json_decode(wp_remote_retrieve_body($result));
	} else {
		error_log('Response Code from SHV VAT: ' . $response_code);
		return false;
	}
}

function request_response_code($url)
{
	$http_url = shv_url($url);

	$result = wp_remote_request($http_url, authorization_headers());

	return wp_remote_retrieve_response_code($result);
}

function club_id()
{
	$options = get_option('tc_shv_results_options');
	if (isset($options)) {
		return $options['club_id'];
	}
}

function retrieve_teams()
{
	return execute_request('v1/clubs/' . club_id() . '/teams');
}

function test_retrieve_club_info() {
	return request_response_code('v1/clubs/' . club_id());
}
?>
