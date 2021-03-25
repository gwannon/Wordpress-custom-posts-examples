<?php 

//Informes -------------------------
add_action( 'init', 'azti_informe_create_post_type' );
function azti_informe_create_post_type() {
	$labels = array(
		'name'               => __( 'Informes', 'the7mk2' ),
		'singular_name'      => __( 'Informe', 'the7mk2' ),
		'add_new'            => __( 'Añadir nuevo', 'the7mk2' ),
		'add_new_item'       => __( 'Añadir nuevo informe', 'the7mk2' ),
		'edit_item'          => __( 'Editar informe', 'the7mk2' ),
		'new_item'           => __( 'Nuevo informe', 'the7mk2' ),
		'all_items'          => __( 'Todos los informes', 'the7mk2' ),
		'view_item'          => __( 'Ver informe', 'the7mk2' ),
		'search_items'       => __( 'Buscar informe', 'the7mk2' ),
		'not_found'          => __( 'Informe no encontrado', 'the7mk2' ),
		'not_found_in_trash' => __( 'Informe no encontrado en la papelera', 'the7mk2' ),
		'menu_name'          => __( 'Informes', 'the7mk2' ),
	);
	$args = array(
		'labels'        => $labels,
		'description'   => __( 'Añadir nuevo informe', 'the7mk2' ),
		'public'        => true,
		'menu_position' => 6,
		'query_var' 	=> true,
		'supports'      => array( 'title'/*, 'editor'*/, 'thumbnail' ),
		'rewrite'	=> array('slug' => 'informe', 'with_front' => false),
		'query_var'	=> true,
		'has_archive' 	=> false,
		'hierarchical'	=> true,
	);
	register_post_type( 'informe', $args );
}

//CAMPOS personalizados -----------------------------------
function get_informes_custom_fields () {
	$fields = array(
		'subtitulo' => array ('titulo' => "Subtítulo", 'tipo' => 'textarea'),
		'columna1' => array ('titulo' => "Texto columna 1", 'tipo' => 'textarea'),
		'columna2' => array ('titulo' => "Texto columna 2", 'tipo' => 'textarea'),
		'slogan' => array ('titulo' => "Slogan columna 2", 'tipo' => 'text'),
		'youtube' => array ('titulo' => "Youtube", 'tipo' => 'text'),

		'tab1_titulo' => array ('titulo' => "Título del tab 1", 'tipo' => 'text'),
		'tab1_texto' => array ('titulo' => "Texto del tab 1", 'tipo' => 'textarea'),
		'tab1_pdf' => array ('titulo' => "PDF del tab 1", 'tipo' => 'image'),

		'tab2_titulo' => array ('titulo' => "Título del tab 2", 'tipo' => 'text'),
		'tab2_texto' => array ('titulo' => "Texto del tab 2", 'tipo' => 'textarea'),
		'tab2_pdf' => array ('titulo' => "PDF del tab 2", 'tipo' => 'image'),

		'tab3_titulo' => array ('titulo' => "Título del tab 3", 'tipo' => 'text'),
		'tab3_texto' => array ('titulo' => "Texto del tab 3", 'tipo' => 'textarea'),
		'tab3_pdf' => array ('titulo' => "PDF del tab 3", 'tipo' => 'image'),

		'tab4_titulo' => array ('titulo' => "Título del tab 4", 'tipo' => 'text'),
		'tab4_texto' => array ('titulo' => "Texto del tab 4", 'tipo' => 'textarea'),
		'tab4_pdf' => array ('titulo' => "PDF del tab 4", 'tipo' => 'image'),

		'tab5_titulo' => array ('titulo' => "Título del tab 5", 'tipo' => 'text'),
		'tab5_texto' => array ('titulo' => "Texto del tab 5", 'tipo' => 'textarea'),
		'tab5_pdf' => array ('titulo' => "PDF del tab 5", 'tipo' => 'image'),
		
		'tab6_titulo' => array ('titulo' => "Título del tab 6", 'tipo' => 'text'),
		'tab6_texto' => array ('titulo' => "Texto del tab 6", 'tipo' => 'textarea'),
		'tab6_pdf' => array ('titulo' => "PDF del tab 6", 'tipo' => 'image'),
		
		'tab7_titulo' => array ('titulo' => "Título del tab 7", 'tipo' => 'text'),
		'tab7_texto' => array ('titulo' => "Texto del tab 7", 'tipo' => 'textarea'),
		'tab7_pdf' => array ('titulo' => "PDF del tab 7", 'tipo' => 'image'),
		
		'tab8_titulo' => array ('titulo' => "Título del tab 8", 'tipo' => 'text'),
		'tab8_texto' => array ('titulo' => "Texto del tab 8", 'tipo' => 'textarea'),
		'tab8_pdf' => array ('titulo' => "PDF del tab 8", 'tipo' => 'image'),
		
		'tab9_titulo' => array ('titulo' => "Título del tab 9", 'tipo' => 'text'),
		'tab9_texto' => array ('titulo' => "Texto del tab 9", 'tipo' => 'textarea'),
		'tab9_pdf' => array ('titulo' => "PDF del tab 9", 'tipo' => 'image'),
		
		'tab10_titulo' => array ('titulo' => "Título del tab 10", 'tipo' => 'text'),
		'tab10_texto' => array ('titulo' => "Texto del tab 10", 'tipo' => 'textarea'),
		'tab10_pdf' => array ('titulo' => "PDF del tab 10", 'tipo' => 'image'),
		
		'descargable_url' => array ('titulo' => "URL del informe descargable", 'tipo' => 'image'),
	);
	return $fields;
}

function wp_informes_add_custom_fields() {
    add_meta_box(
        'box_informes', // $id
        __('Datos'), // $title 
        'wp_informes_show_custom_fields', // $callback
        'informe', // $page
        'normal', // $context
        'high'); // $priority
}
add_action('add_meta_boxes', 'wp_informes_add_custom_fields');

function wp_informes_show_custom_fields() { //Show box
	global $post; 
	$fields = get_informes_custom_fields ();
	foreach ($fields as $field => $datos) { ?>
		<b><?php echo $datos['titulo']; ?></b><br/> 
		<?php if($datos['tipo'] == 'link' || $datos['tipo'] == 'text') { ?>
			<input type="text" class="_informe_<?php echo $field; ?>" id="_informe_<?php echo $field; ?>" style="width: 100%;" name="_informe_<?php echo $field; ?>" value="<?php echo get_post_meta( $post->ID, '_informe_'.$field, true ); ?>" />	
			
		<?php } else if($datos['tipo'] == 'image') { ?>
			<input type="text" class="_informe_<?php echo $field; ?>" id="input_informe_<?php echo $field; ?>" style="width: 80%;" name="_informe_<?php echo $field; ?>" value='<?php echo get_post_meta( $post->ID, '_informe_'.$field, true ); ?>' />
			<a href="#" id="button_informe_<?php echo $field; ?>" class="button insert-media add_media" data-editor="input_informe_<?php echo $field; ?>" title="<?php _e("Añadir fichero"); ?>"><span class="wp-media-buttons-icon"></span> <?php _e("Añadir fichero"); ?></a>
			<script>
				jQuery(document).ready(function () {			
					jQuery("#input_informe_<?php echo $field; ?>").change(function() {
						a_imgurlar = jQuery(this).val().match(/<a href=\"([^\"]+)\"/);
						img_imgurlar = jQuery(this).val().match(/<img[^>]+src=\"([^\"]+)\"/);
						if(img_imgurlar !==null ) jQuery(this).val(img_imgurlar[1]);
						else  jQuery(this).val(a_imgurlar[1]);
					});
				});
			</script>			
		<?php } else if($datos['tipo'] == 'textarea') { ?>
			<?php $settings = array( 'media_buttons' => true, 'quicktags' => true, 'textarea_rows' => 5 ); ?>
			<?php wp_editor( get_post_meta( $post->ID, '_informe_'.$field, true ), '_informe_'.$field, $settings ); ?>
		<?php } else if ($datos['tipo'] == 'select') { ?>
          	<select name="_informe_<?php echo $field; ?>">
          	<?php foreach($datos['valores'] as $key => $value) { ?>
            	<option value="<?php echo $key; ?>"<?php if ($key == get_post_meta( $post->ID, '_informe_'.$field, true )) echo " selected='selected'"; ?>><?php echo $value; ?></option>
        	<?php } ?>	
			</select>
        <?php }  ?>
		<br/><br/>
	<?php }
}

function wp_informes_save_custom_fields( $post_id ) { //Save changes
	global $wpdb;
	$fields = get_informes_custom_fields ();
	foreach ($fields as $field => $datos) {
		$label = '_informe_'.$field;
		//echo $label."\n";
		if (isset($_POST[$label])) update_post_meta( $post_id, $label, $_POST[$label]);
	}
}
add_action( 'save_post', 'wp_informes_save_custom_fields' );

//Retos relacionados --------------------------------------------------
function  azti_informe_add_retos() {
    add_meta_box(
        'retos', // $id
        __('Retos relacionados', 'the7mk2'), // $title 
        'azti_informe_show_retos', // $callback
        'informe', // $page
        'normal', // $context
        'high'); // $priority
}
add_action('add_meta_boxes', 'azti_informe_add_retos');

function azti_informe_show_retos() { //Show box
	global $post;
	$retos = get_posts(array( 'order' => 'ASC', 'orderby' => 'title', 'post_type' => 'reto', 'post_status' => 'publish', 'posts_per_page'   => -1)); 
	

	foreach ($retos as $reto) {
		?>
		<div style="width: 33%; float: left;">
		<input type="checkbox" name="_informe_retos[]" <?php if (in_array($reto->ID, json_decode(get_post_meta( $post->ID, '_informe_retos', true ), true))) echo " checked='checked'"; ?>value="<?php echo $reto->ID; ?>"> <?php echo apply_filters("the_title", $reto->post_title); ?>
		</div>
		<?php
	}
	?><div style="clear: both"></div><?php
}

function azti_informe_save_retos( $post_id ) { //Save changes
	if (isset($_POST['_informe_retos']) && count($_POST['_informe_retos']) > 0) update_post_meta( $post_id, '_informe_retos', json_encode($_POST['_informe_retos']) );
	else update_post_meta( $post_id, '_informe_retos', "");
}

add_action( 'save_post', 'azti_informe_save_retos' );
