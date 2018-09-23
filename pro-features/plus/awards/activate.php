<?php

defined('ABSPATH') or die("Jog on!");

define( 'WE_LS_MYSQL_AWARDS', 'WS_LS_AWARDS' );
define( 'WE_LS_MYSQL_AWARDS_GIVEN', 'WS_LS_AWARDS_GIVEN' );

/**
 * Create the relevant database tables required to support meta fields
 */
function ws_ls_awards_create_mysql_tables() {

    global $wpdb;

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    $table_name = $wpdb->prefix . WE_LS_MYSQL_AWARDS;

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                title varchar(40) NOT NULL,
                category varchar(20) NOT NULL,
                gain_loss varchar(5) NULL DEFAULT NULL,
                badge int NULL DEFAULT 0,
                custom_message varchar(200) NULL,
                max_awards int NOT NULL DEFAULT 1,
                enabled int DEFAULT 1,
                send_email BIT DEFAULT 1,
                apply_to_update BIT DEFAULT 1,
                apply_to_add int NOT NULL,
                sort int DEFAULT 100,
                UNIQUE KEY id (id)
            ) $charset_collate;";

    dbDelta( $sql );

    $table_name = $wpdb->prefix . WE_LS_MYSQL_AWARDS_GIVEN;

    $sql = "CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                user_id int NOT NULL,
                award_id int NOT NULL,
                timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY id (id)              
            ) $charset_collate;";

    dbDelta( $sql );
}
add_action('ws-ls-rebuild-database-tables', 'ws_ls_awards_create_mysql_tables');

/**
 *  Activate Meta Fields feature
 */
function ws_ls_awards_activate() {

    // Only run this when the plugin version has changed
    if( true === update_option('ws-ls-awards-db-number', WE_LS_DB_VERSION )) {
        ws_ls_awards_create_mysql_tables();
    }
}
add_action( 'admin_init', 'ws_ls_awards_activate' );

