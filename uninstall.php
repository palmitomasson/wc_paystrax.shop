<?php

/**
 * Paystrax Uninstall
 *
 * Uninstalling Paystrax deletes Plugin data.
 *
 */
defined('WP_UNINSTALL_PLUGIN') || exit;
global $wpdb;
$table_name = $wpdb->prefix . 'webhookData';
$wpdb->query("DROP TABLE IF EXISTS $table_name");
