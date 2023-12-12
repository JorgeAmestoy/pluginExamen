<?php

/*
Plugin Name: Uppercase Plugin
Plugin URI: http://wordpress.org/plugins/hello-words/
Description: Este plugin convierte el texto de los posts y títulos a mayúsculas. Además, almacena cada palabra en mayúsculas en una tabla de la base de datos.
Author: Jorge Amestoy
Version: 1.0.0
Author URI: http://mjorge.amestoy/
*/
function crear_tabla(){
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
add_action('plugins_loaded', 'crear_tabla');

/**
 * Funcion que inserta datos en la tabla
 */
function convertir_mayusculas($text){
    global $wpdb;
    $table_name = $wpdb->prefix . 'mayusculas';

    // Verificar si el texto es un borrador automático o está vacío
    if (empty($text) || strpos($text, 'Borrador automático') !== false) {
        return $text;
    }
    // Verificar si la palabra ya existe en la tabla
    $palabraExistente = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE originalText = %s", $text));

    // Si la palabra no existe, insertarla en la tabla
    if (!$palabraExistente) {
        // Obtener el texto sin las etiquetas HTML
        $textoSinEtiquetas = strip_tags($text);
        $wpdb->insert(
            $table_name,
            array(
                'originalText'  => $textoSinEtiquetas,
                'uppercaseText' => strtoupper($textoSinEtiquetas)
            )
        );
    }
    // Devolver el texto en mayúsculas
    return strtoupper($text);
}

add_filter( 'the_content', 'convertir_mayusculas');
add_filter( 'the_title', 'convertir_mayusculas');

