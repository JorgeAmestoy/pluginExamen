# EXAMEN PLUGINS

## UPPERCASE PLUGIN

Este plugin convierte el texto de los posts y títulos a mayúsculas almacenando las palabras 
en mayúsculas en una tabla de la base de datos.

### CREAR_TABLA()
```
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
```
Creo una tabla llamada **mayusculas** que va a contener tres columnas:
- el identificador de la fila (**id**)
- el texto original (**originalText**)
- el texto en mayúsculas (**uppercaseText**)

----------------------------------


### CONVERTIR_MAYUSCULAS()
En esta función desarrollo la lógica del plugin. A diferencia del que escribí en el folio,
lo comprimo todo en una sola función eliminando los métodos innecesarios como *filtrarContenido()*.<br>

----------------------------------
```
global $wpdb;
$table_name = $wpdb->prefix . 'mayusculas';
```
Creo una variable que almacena el nombre de la tabla de la base de datos
para poder utilizarla en las consultas posteriores.<br>

---------------------------------

```
 if (empty($text) || strpos($text, 'Borrador automático') !== false) {
        return $text;
    }
```
Al trabajar directamente con el contenido de los posts, es necesario comprobar si el texto está vacío o si es un borrador automático
para que no se vea reflejado en la tabla de la base de datos.<br>
Con `empty()` verifico que el texto está vacío y por otro lado, `strpos()` es una función de PHP
que en este caso busca la cadena 'Borrador automático' en el texto. 

---------------------------------

```
$palabraExistente = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE originalText = %s", $text));
```
En esta línea compruebo si el texto ya existe en la tabla para no volver a insertarlo.<br>

---------------------------------
```
if (!$palabraExistente({
       $textoSinEtiquetas = strip_tags($text);
       $wpdb->insert(
            $table_name,
            array(
                'originalText'  => $textoSinEtiquetas,
                'uppercaseText' => strtoupper($textoSinEtiquetas)
            )
        );
    }
```
Si la palabra no existe, la inserto en la tabla en las columnas correspondientes.
Uso `strip_tags($text)` para eliminar las etiquetas HTML del texto y que estas 
no se inserten en la tabla y `strtoupper()` para convertir el texto a mayúsculas.<br>
Esta función devuelve el texto cambiado para mostrarlo en los posts.<br>

---------------------------------
Finalmente añado los **filtros**:
```
add_filter( 'the_content', 'convertir_mayusculas' );
add_filter( 'the_title', 'convertir_mayusculas' );
```
