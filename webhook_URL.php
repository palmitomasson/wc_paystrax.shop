<?php
$path = preg_replace('/wp-content.*$/', '', __DIR__);
include($path . 'wp-load.php');

function maybe_create_table( $table_name, $create_ddl ) {
	global $wpdb;

	$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
//     custom_logs($query);
    error_log('-- create table --' . $query );
	if ( $wpdb->get_var( $query ) === $table_name ) {
		return true;
	}

	// Didn't find it, so try to create it.
	$wpdb->query( $create_ddl );

	// We cannot directly tell that whether this succeeded!
	if ( $wpdb->get_var( $query ) === $table_name ) {
		return true;
	}

	return false;
}

function webhook()
{
	$server_data = $_SERVER;
	$HTTP_X_INITIALIZATION_VECTOR = $server_data['HTTP_X_INITIALIZATION_VECTOR'];
	$HTTP_X_AUTHENTICATION_TAG = $server_data['HTTP_X_AUTHENTICATION_TAG'];
	$woocommerce_paystrax_settings = get_option('woocommerce_paystrax_settings');
	$key_from_configuration = $woocommerce_paystrax_settings['Webhook_Secret_Key'];
	$notification_data =  file_get_contents('php://input');

	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'webhookData';
	$create_ddl = "CREATE TABLE $table_name(
                      id int(10) NOT NULL AUTO_INCREMENT,
                      transactionID varchar(50) NOT NULL,
                      paymenttype varchar(250) NOT NULL,
                      statuscode varchar(250) NOT NULL,
                      description varchar (250) NOT NULL,
                      timestamp varchar (250) NOT NULL,
                      CustomerEmail varchar (250),
                      PRIMARY KEY id(id)
                ) $charset_collate;";
	maybe_create_table( $table_name, $create_ddl );
	if (isset($notification_data)) {
		if (isset($HTTP_X_INITIALIZATION_VECTOR, $HTTP_X_AUTHENTICATION_TAG)) {
			$key = hex2bin($key_from_configuration);
			$iv = hex2bin($HTTP_X_INITIALIZATION_VECTOR);
			$auth_tag = hex2bin($HTTP_X_AUTHENTICATION_TAG);
			$cipher_text = hex2bin($notification_data);
			$result = openssl_decrypt($cipher_text, "aes-256-gcm", $key, OPENSSL_RAW_DATA, $iv, $auth_tag);
			$data = json_decode($result, true);
			custom_logs($data);
			$save_data = array(
				"transactionID" => sanitize_text_field($data['payload']['id']),
				"paymenttype"  => sanitize_text_field($data['payload']['paymentType']),
				"statuscode" => sanitize_text_field($data['payload']['result']['code']),
				"description" => sanitize_text_field($data['payload']['result']['description']),
				"CustomerEmail" => sanitize_email($data['payload']['customer']['email']),
				"timestamp" => sanitize_text_field($data['payload']['timestamp']),
			);

			$id = $wpdb->insert($table_name, $save_data, array('%s', '%s', '%s', '%s', '%s', '%s'));

			if ($id > 0) {
				custom_logs('inserted');
			} else {
				custom_logs($wpdb->last_error);
			}
		}
		echo http_response_code(200);
		exit;
	} else {
		echo http_response_code(404);
		exit;
	}
}
webhook();



function custom_logs($message)
{
	$upload = wp_upload_dir();
	$upload_dir = $upload['basedir'];

	if (is_array($message)) {
		$message = json_encode($message);
	}
	$pluginlog = $upload_dir . '/wc-logs/webhook-' . date('Y-m-d') . '.log';
	$file = fopen($pluginlog, "a");
	fwrite($file, "\n" . date('Y-m-d H:i:s') . " :: " . $message);
	fclose($file);
}
