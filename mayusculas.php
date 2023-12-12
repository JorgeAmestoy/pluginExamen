<?php

/*
Plugin Name: Uppercase Plugin
Plugin URI: http://wordpress.org/plugins/hello-words/
Description: Este plugin convierte el texto de los posts y títulos a mayúsculas. Además, almacena cada palabra en mayúsculas en una tabla de la base de datos.
Author: Jorge Amestoy
Version: 1.0.0
Author URI: http://mjorge.amestoy/
*/
function crearTabla(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'mayusculas';
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
       id mediumint(9) NOT NULL AUTO_INCREMENT,
       originalText text NOT NULL,
       uppercaseText text NOT NULL,
       PRIMARY KEY  (id)
   ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
add_action('plugins_loaded', 'crearTabla');
