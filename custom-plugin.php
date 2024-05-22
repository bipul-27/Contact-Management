<?php


/*
  * Plugin Name: Contact Management AuthLab
  * Description: This is a Contact Management System
  * Plugin URI: https://sample.com/contact-management
  * Author: Bipul Karmokar
  * Author URI: https://bipul.com
  * Version: 1.0.0
  * Requires at least: 6.3.2
  * Requires PHP: 7.4
*/

define("CONTACT_PLUGIN_PATH", plugin_dir_path(__FILE__));
define("CONTACT_PLUGIN_URL", plugin_dir_url(__FILE__));

// Calling action hook to add menu
add_action("admin_menu","cp_add_admin_menu");


//Add Menu
function cp_add_admin_menu()
{
    add_menu_page("Contact | Contact Management AuthLab", "Contact", "manage_options", 
    "contact-management", "contact_manage_system", "dashicons-phone", 23);

    //Sub Menus
    add_submenu_page("contact-management", "Create Contact", "Create Contact",
    "manage_options", "contact-management", "contact_manage_system");

    add_submenu_page("contact-management", "Contact List", "Contact List",
    "manage_options", "list-contact","list_contact");

}

//Menu Handle Callback
function contact_manage_system()
{

    include_once(CONTACT_PLUGIN_PATH."pages\create-contact.php");
}
// Submenu callback function
function list_contact()
{
    include_once(CONTACT_PLUGIN_PATH."pages\contact-list.php");
}

register_activation_hook(__FILE__,"ems_create_table");

function ems_create_table()
{
    global $wpdb;
    $table_prefix=$wpdb->prefix;
    $sql="
    CREATE TABLE {$table_prefix}ems_form_data (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(120) DEFAULT NULL,
        `phoneNo` varchar(50) DEFAULT NULL,
        `email` varchar(80) DEFAULT NULL,
        `gender` enum('male','female','other','') DEFAULT NULL,
        `designation` varchar(50) DEFAULT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";

    include_once ABSPATH. "wp-admin/includes/upgrade.php";

    dbDelta($sql);
    $pageData = [
        "post_title" => "Contact Management System Page",
        "post_status" => "publish",
        "post_type" => "page",
        "post_content" => "This is a sample content",
        "post_name" => "contact-management-system-page"
    ];

    wp_insert_post($pageData);

}

register_deactivation_hook(__FILE__,"ems_drop_table");

function ems_drop_table()
{
    global $wpdb;
$table_prefix = $wpdb->prefix;
$sql = "DROP TABLE IF EXISTS {$table_prefix}ems_form_data";
$wpdb->query($sql);

// Drop WordPress Page
$pageSlug = "contact-management-system-page";

$pageInfo = get_page_by_path($pageSlug);

if(!empty($pageInfo)){

    $pageId = $pageInfo->ID;

    wp_delete_post($pageId, true);
}

}

add_action("admin_enqueue_scripts", "ems_add_plugin_assests");

function ems_add_plugin_assests()
{
    // CSS
    wp_enqueue_style("ems-bootstrap-css", CONTACT_PLUGIN_URL . "css/bootstrap.min.css", array(), "1.0.0", "all");
    wp_enqueue_style("ems-datatable-css", CONTACT_PLUGIN_URL . "css/dataTables.dataTables.min.css", array(), "1.0.0", "all");
    wp_enqueue_style("ems-custom-css", CONTACT_PLUGIN_URL . "css/custom.css", array(), "1.0.0", "all");

    // JS
    wp_enqueue_script("ems-bootstrap-js", CONTACT_PLUGIN_URL . "js/bootstrap.min.js", array("jquery"), "1.0.0");
    wp_enqueue_script("ems-datatable-js", CONTACT_PLUGIN_URL . "js/dataTables.min.js", array("jquery"), "1.0.0");
    wp_enqueue_script("ems-custom-js", CONTACT_PLUGIN_URL . "js/custom.js", array("jquery"), "1.0.0");

    // Enqueue wp_remote_get for custom.js
    $response = wp_remote_get(CONTACT_PLUGIN_URL . "js/custom.js");
    
    if (!is_wp_error($response)) {
        $body = wp_remote_retrieve_body($response);
        // Enqueue custom.js content
        wp_add_inline_script("ems-custom-js", $body);
    } else {
        // Handle wp_remote_get error
        error_log("Failed to fetch custom.js: " . $response->get_error_message());
    }

    // Enqueue jQuery Validate
    wp_enqueue_script("ems-validate-js", CONTACT_PLUGIN_URL . "js/jquery.validate.min.js", array("jquery"), "1.0.0");
}
