# EXAMEN PLUGINS

## UPPERCASE PLUGIN

Este plugin convierte el texto de los posts y títulos a mayúsculas almacenando cada palabra 
en mayúsculas en una tabla de la base de datos.

### CREARTABLA()
```
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
```
Creo una tabla llamada **mayusculas** que va a contener tres columnas:
- el identificador de la fila (**id**)
- el texto original (**originalText**)
- el texto en mayúsculas (**uppercaseText**)

