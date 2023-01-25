<?php

add_action('admin_menu', 'the_edit_form_function');
function the_edit_form_function()
{
    add_menu_page('Webhookdata', 'Webhookdata', 'manage_options', 'edit_form', 'edit_data');
}

function edit_data()
{
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'webhookData';
    $results = $wpdb->get_results("SELECT * FROM $table_name");
    $type = '';
    $id = '';

    echo "<table class='table  table-striped'>
    <tr>
    <th>S.No</th>
    <th>Transaction Id</th>
    <th>PaymentType</th>
    <th>Status Code</th>
    <th>Description</th>
    <th>Timestamp</th>
    <th>Customer Email</th>
    <th>Action</th>
    </tr>";
    $i = 0;
    if (count($results) > 0) {
        foreach ($results as $v) {
            $i++;
            echo "<tr>
            <td>$i</td>
            <td>" . esc_html($v->transactionID) . "</td>
            <td>" . esc_html($v->paymenttype) . "</td>
            <td>" . esc_html($v->statuscode) . "</td>
            <td>" . esc_html($v->description) . "</td>
            <td>" . esc_html($v->timestamp) . "</td>
            <td>" . esc_html($v->CustomerEmail) . "</td>
            <td><button class='post btn btn-danger'><a href='admin.php?type=delete&id=" . $v->id . "'>delete</a></button></td>
            </tr>";
        }
    }
    echo "</table>";
}

register_activation_hook(__FILE__,  'activate');

function activate()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'webhookData';
    $sql = "DROP TABLE IF EXISTS $table_name;
            CREATE TABLE $table_name(
            id int(10) NOT NULL AUTO_INCREMENT,
            transactionID varchar(50) NOT NULL,
            paymenttype varchar(250) NOT NULL,
            statuscode varchar(250) NOT NULL,
            description varchar (250) NOT NULL,
            timestamp varchar (250) NOT NULL,
            CustomerEmail varchar (250),
            PRIMARY KEY id(id)
        ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

global $wpdb;
$charset_collate = $wpdb->get_charset_collate();
$table_name = $wpdb->prefix . 'webhookData';
if (isset($_GET['type'], $_GET['id'])) {
    $type = $_GET['type'];
    if ($type == 'delete') {
        $id = $_GET['id'];
        $wpdb->query("DELETE FROM $table_name WHERE id='$id'");
        echo "<script>window.location.href = 'admin.php?page=edit_form'</script>";
    }
}







