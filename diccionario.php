<?php 

/**
 * Plugin Name: Diccionario
 * Plugin URI:  https://www.enutt.net/
 * Description: Generador de diccionario
 * Version:     1.0
 * Author:      Enutt S.L.
 * Author URI:  https://www.enutt.net/
 * License:     GNU General Public License v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: diccionario
 *
 * PHP 7.3
 * WordPress 5.6
 */
 
flush_rewrite_rules(true);

// Palabras ---------------------------------------
// ----------------------------------------------------
add_action( 'init', 'enedenorte_diccionario_create_post_type' );
function enedenorte_diccionario_create_post_type() {
	$labels = array(
		'name'               => __( 'Palabras', 'palabras' ),
		'singular_name'      => __( 'Palabra', 'palabras' ),
		'add_new'            => __( 'Añadir nueva', 'palabras' ),
		'add_new_item'       => __( 'Añadir nueva palabra', 'palabras' ),
		'edit_item'          => __( 'Editar palabra', 'palabras' ),
		'new_item'           => __( 'Nueva palabra', 'palabras' ),
		'all_items'          => __( 'Todas las palabras', 'palabras' ),
		'view_item'          => __( 'Ver palabra', 'palabras' ),
		'search_items'       => __( 'Buscar palabra', 'palabras' ),
		'not_found'          => __( 'Palabra no encontrado', 'palabras' ),
		'not_found_in_trash' => __( 'Palabra no encontrado en la papelera', 'palabras' ),
		'menu_name'          => __( 'Palabras', 'palabras' ),
	);
	$args = array(
		'labels'        => $labels,
		'description'   => __( 'Añadir nueva palabra', 'palabras' ),
		'public'        => true,
		'menu_position' => 7,
		'query_var' 	=> true,
		'supports'      => array( 'title', 'editor' ),
		'rewrite'	=> array('slug' => 'que-es', 'with_front' => false),
		'query_var'	=> true,
		'hierarchical'	=> true,
	);
	register_post_type( 'palabra', $args );
}

//Hooks --------------------------------------

add_filter( 'the_title', 'enedenorte_diccionario_title_filter' );

function enedenorte_diccionario_title_filter( $title ) {
	global $id, $post;
    	if ( in_the_loop() && is_singular('palabra')) return "¿Qué es ".get_post_meta( $post->ID, '_palabra_articulo', true )." ".$title."?";
	return $title;
}


//Campos personalizados ----------------------
function enedenorte_diccionario_custom_fields () {
	$fields = array(
		'articulo' => array ('titulo' => "Articulo", 'tipo' => 'select', 'valores' => array("el" => "el", "la" => "la", "un" => "un", "una" => "una", "los" => "los", "las" => "las", "unos" => "unos", "unas" => "unas")),
	);
	return $fields;
}

function enedenorte_diccionario_add_custom_fields() {
    add_meta_box(
        'box_palabras', // $id
        __('Datos'), // $title 
        'enedenorte_diccionario_show_custom_fields', // $callback
        'palabra', // $page
        'normal', // $context
        'high'); // $priority
}
add_action('add_meta_boxes', 'enedenorte_diccionario_add_custom_fields');

function enedenorte_diccionario_show_custom_fields() { //Show box
	global $post; 
	$fields = enedenorte_diccionario_custom_fields ();
	foreach ($fields as $field => $datos) { ?>
		<b><?php echo $datos['titulo']; ?></b><br/> 
		<?php if($datos['tipo'] == 'link' || $datos['tipo'] == 'text') { ?>
			<input type="text" class="_palabra_<?php echo $field; ?>" id="_palabra_<?php echo $field; ?>" style="width: 100%;" name="_palabra_<?php echo $field; ?>" value="<?php echo get_post_meta( $post->ID, '_palabra_'.$field, true ); ?>" />	
			
		<?php } else if($datos['tipo'] == 'image') { ?>
			<input type="text" class="_palabra_<?php echo $field; ?>" id="input_palabra_<?php echo $field; ?>" style="width: 80%;" name="_palabra_<?php echo $field; ?>" value='<?php echo get_post_meta( $post->ID, '_palabra_'.$field, true ); ?>' />
			<a href="#" id="button_palabra_<?php echo $field; ?>" class="button insert-media add_media" data-editor="input_palabra_<?php echo $field; ?>" title="<?php _e("Añadir fichero"); ?>"><span class="wp-media-buttons-icon"></span> <?php _e("Añadir fichero"); ?></a>
			<script>
				jQuery(document).ready(function () {			
					jQuery("#input_palabra_<?php echo $field; ?>").change(function() {
						a_imgurlar = jQuery(this).val().match(/<a href=\"([^\"]+)\"/);
						img_imgurlar = jQuery(this).val().match(/<img[^>]+src=\"([^\"]+)\"/);
						if(img_imgurlar !==null ) jQuery(this).val(img_imgurlar[1]);
						else  jQuery(this).val(a_imgurlar[1]);
					});
				});
			</script>			
		<?php } else if($datos['tipo'] == 'textarea') { ?>
			<?php $settings = array( 'media_buttons' => true, 'quicktags' => true, 'textarea_rows' => 5 ); ?>
			<?php wp_editor( get_post_meta( $post->ID, '_palabra_'.$field, true ), '_palabra_'.$field, $settings ); ?>
		<?php } else if ($datos['tipo'] == 'select') { ?>
          	<select name="_palabra_<?php echo $field; ?>">
          	<?php foreach($datos['valores'] as $key => $value) { ?>
            	<option value="<?php echo $key; ?>"<?php if ($key == get_post_meta( $post->ID, '_palabra_'.$field, true )) echo " selected='selected'"; ?>><?php echo $value; ?></option>
        	<?php } ?>	
			</select>
        <?php }  ?>
		<br/><br/>
	<?php }
}

function enedenorte_diccionario_save_custom_fields( $post_id ) { //Save changes
	global $wpdb;
	$fields = enedenorte_diccionario_custom_fields ();
	foreach ($fields as $field => $datos) {
		$label = '_palabra_'.$field;
		//echo $label."\n";
		if (isset($_POST[$label])) update_post_meta( $post_id, $label, $_POST[$label]);
	}
}
add_action( 'save_post', 'enedenorte_diccionario_save_custom_fields' );


