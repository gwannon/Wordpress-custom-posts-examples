<?php 

// Socios ---------------------------------------
// ------------------------------------------------
add_action( 'init', 'mlcluster_socio_create_post_type' );
function mlcluster_socio_create_post_type() {
	$labels = array(
		'name'               => __( 'Socios', 'the7mk2' ),
		'singular_name'      => __( 'Socio', 'the7mk2' ),
		'add_new'            => __( 'Añadir nuevo', 'the7mk2' ),
		'add_new_item'       => __( 'Añadir nuevo Socio', 'the7mk2' ),
		'edit_item'          => __( 'Editar Socio', 'the7mk2' ),
		'new_item'           => __( 'Nuevo Socio', 'the7mk2' ),
		'all_items'          => __( 'Todas las socios', 'the7mk2' ),
		'view_item'          => __( 'Ver socio', 'the7mk2' ),
		'search_items'       => __( 'Buscar socio', 'the7mk2' ),
		'not_found'          => __( 'Socio no encontrado', 'the7mk2' ),
		'not_found_in_trash' => __( 'Socio no encontrado en la papelera', 'the7mk2' ),
		'menu_name'          => __( 'Socios', 'the7mk2' ),
	);
	$args = array(
		'labels'        => $labels,
		'description'   => __( 'Añadir nuevo socio', 'the7mk2' ),
		'public'        => true,
		'menu_position' => 7,
		'query_var' 	=> true,
		'supports'      => array( 'title', 'editor'/*, 'thumbnail', 'page-attributes'*/ ),
		'rewrite'	=> array('slug' => 'socio', 'with_front' => false),
		'query_var'	=> true,
		'has_archive' 	=> false,
		'hierarchical'	=> true,
	);
	register_post_type( 'socio', $args );
}

//CAMPOS personalizados ---------------------------
// ------------------------------------------------
function get_mlcluster_socios_custom_fields () {
	$fields = array(
		/*'nombre_comercial' => array ('titulo' => "Nombre comercial", 'tipo' => 'text'),*/
		/*'logotipo' => array ('titulo' => "Logotipo", 'tipo' => 'image'),*/
		'web' => array ('titulo' => "Web", 'tipo' => 'link'),
		'mercado' => array ('titulo' => "Mercado", 'tipo' => 'textarea'),
		'areas_cluster' => array ('titulo' => "Areas del cluster", 'tipo' => 'select', "valores" => array(
			"1" =>  __('Cargadores', 'the7mk2'), 
			"2" =>  __('Infraestructuras', 'the7mk2'), 
			"3" =>  __('Operadores', 'the7mk2'), 
			"4" =>  __('Productos y Servicios', 'the7mk2'), 
			"5" =>  __('Administración y otros', 'the7mk2'), 
		)),
		'capacidades' => array ('titulo' => "Capacidades", 'tipo' => 'text'),
		'presencia_internacional' => array ('titulo' => "Presencia internacional", 'tipo' => 'checkbox', "valores" => array(
			"africa" =>  __('África', 'the7mk2'), 
			"america" => __('América', 'the7mk2'), 
			"asia" => __('Asia', 'the7mk2'), 
			"europa" => __('Europa', 'the7mk2'),  
			"oceania" => __('Oceanía', 'the7mk2')
		)),
		'latitud' => array ('titulo' => "Latitud", 'tipo' => 'text'),
		'longitud' => array ('titulo' => "Longitud", 'tipo' => 'text'),
		'razon_social' => array ('titulo' => "Razón social", 'tipo' => 'text'),
		'calle' => array ('titulo' => "Calle", 'tipo' => 'text'),
		'codigo_postal' => array ('titulo' => "Código postal", 'tipo' => 'text'),
		'ciudad' => array ('titulo' => "Ciudad", 'tipo' => 'text'),
		'provincia' => array ('titulo' => "Provincia", 'tipo' => 'text'),
		'telefono' => array ('titulo' => "Teléfono", 'tipo' => 'text'),
		'email' => array ('titulo' => "Email", 'tipo' => 'text'),
		'id_externa' => array ('titulo' => "ID en el ERP", 'tipo' => 'hidden'), 
		/*'select' => array ('titulo' => "Select", 'tipo' => 'select', "valores" => array("en_proceso" =>  'En proceso', "terminado" => 'Terminado')),
		'textarea' => array ('titulo' => "Textarea", 'tipo' => 'textarea'),
		'imagen' => array ('titulo' => "Imagen", 'tipo' => 'image'),
		'enlace' => array ('titulo' => "Enlace", 'tipo' => 'link'),*/
	);
	return $fields;
}

function mlcluster_socios_add_custom_fields() {
    add_meta_box(
        'box_socios', // $id
        __('Datos del socio', 'the7mk2'), // $title 
        'mlcluster_socios_show_custom_fields', // $callback
        'socio', // $page
        'normal', // $context
        'high'); // $priority
}
add_action('add_meta_boxes', 'mlcluster_socios_add_custom_fields');

function mlcluster_socios_show_custom_fields() { //Show box
	global $post; 
	$fields = get_mlcluster_socios_custom_fields (); ?>
		<div>
		<?php foreach ($fields as $field => $datos) { ?>
			<div style="width: calc(50% - 10px); float: left; padding: 5px;">
				<p><b><?php echo $datos['titulo']; ?></b></p>
				<?php if($datos['tipo'] == 'text' || $datos['tipo'] == 'link') { ?>
					<input  type="text" class="_socio_<?php echo $field; ?>" id="_socio_<?php echo $field; ?>" style="width: 100%;" name="_socio_<?php echo $field; ?>" value="<?php echo get_post_meta( $post->ID, '_socio_'.$field, true ); ?>" />
				<?php } else if($datos['tipo'] == 'date') { ?>
					<input type="date" class="_socio_<?php echo $field; ?>" id="_socio_<?php echo $field; ?>" style="width: 50%;" name="_socio_<?php echo $field; ?>" value="<?php echo get_post_meta( $post->ID, '_socio_'.$field, true ); ?>" />
				<?php } else if($datos['tipo'] == 'hidden') { ?>
					<input disabled="disabled" type="text" class="_socio_<?php echo $field; ?>" id="_socio_<?php echo $field; ?>" style="width: 50%;" name="_socio_<?php echo $field; ?>" value="<?php echo get_post_meta( $post->ID, '_socio_'.$field, true ); ?>" />
				<?php } else if($datos['tipo'] == 'image') { ?>
					<input type="text" class="_socio_<?php echo $field; ?>" id="input_socio_<?php echo $field; ?>" style="width: 80%;" name="_socio_<?php echo $field; ?>" value='<?php echo get_post_meta( $post->ID, '_socio_'.$field, true ); ?>' />
					<a href="#" id="button_socio_<?php echo $field; ?>" class="button insert-media add_media" data-editor="input_socio_<?php echo $field; ?>" title="<?php _e("Añadir fichero", 'the7mk2'); ?>"><span class="wp-media-buttons-icon"></span> <?php _e("Añadir fichero", 'the7mk2'); ?></a>
					<script>
						jQuery(document).ready(function () {			
							jQuery("#input_socio_<?php echo $field; ?>").change(function() {
								a_imgurlar = jQuery(this).val().match(/<a href=\"([^\"]+)\"/);
								img_imgurlar = jQuery(this).val().match(/<img[^>]+src=\"([^\"]+)\"/);
								if(img_imgurlar !==null ) jQuery(this).val(img_imgurlar[1]);
								else  jQuery(this).val(a_imgurlar[1]);
							});
						});
					</script>
				<?php } else if($datos['tipo'] == 'text') { ?>
					<input  type="text" class="_socio_<?php echo $field; ?>" id="_socio_<?php echo $field; ?>" style="width: 100%;" name="_socio_<?php echo $field; ?>" value="<?php echo get_post_meta( $post->ID, '_socio_'.$field, true ); ?>" />
				<?php } else if($datos['tipo'] == 'textarea') { ?>
					<?php $settings = array( 'media_buttons' => true, 'quicktags' => true, 'textarea_rows' => 5 ); ?>
					<?php wp_editor( get_post_meta( $post->ID, '_socio_'.$field, true ), '_socio_'.$field, $settings ); ?>
				<?php } else if ($datos['tipo'] == 'select') { ?>
					<select name="_socio_<?php echo $field; ?>">
						<?php foreach($datos['valores'] as $key => $value) { ?>
							<option value="<?php echo $key; ?>"<?php if ($key == get_post_meta( $post->ID, '_socio_'.$field, true )) echo " selected='selected'"; ?>><?php echo $value; ?></option>
						<?php } ?>	
					</select>
				<?php }  else if ($datos['tipo'] == 'checkbox') { ?>
							<?php $results = get_post_meta( $post->ID, '_socio_'.$field, true ); ?>
							<?php foreach($datos['valores'] as $key => $value) { ?>
								<input type="checkbox" class="_socio_<?php echo $field; ?>" id="_socio_<?php echo $field; ?>" name="_socio_<?php echo $field; ?>[]" value="<?php echo $key; ?>" <?php if(is_array($results) && in_array($key, $results)) { echo "checked='checked'"; } ?> /> <?php echo $value; ?><br/>
						<?php } ?>	
				<?php }  ?>
			</div>
	<?php } ?>
	<div style="clear: both;"></div>
	</div> <?php
}

function mlcluster_socios_save_custom_fields( $post_id ) { //Save changes
	global $wpdb;
	$fields = get_mlcluster_socios_custom_fields ();
	foreach ($fields as $field => $datos) {
		$label = '_socio_'.$field;
		if (isset($_POST[$label])) update_post_meta( $post_id, $label, $_POST[$label]);
		else if (!isset($_POST[$label]) && $datos['tipo'] == 'checkbox') delete_post_meta( $post_id, $label);
	}
}
add_action('save_post', 'mlcluster_socios_save_custom_fields' );

// Código corto -----------------------------------
// ------------------------------------------------
function shortcode_mlcluster_socios ($params = array(), $content = null) {
    global $post;
    $html = "";
    $args = array(
      'post_type' => 'socio',
      'posts_per_page' => -1,
      'post_status' => 'publish',
      'order' => 'ASC',
      'orderby' => 'menu_order'
    );

    $the_query = new WP_Query( $args);
    if ($the_query->have_posts() ) {
        while ( $the_query->have_posts() ) { 
		$the_query->the_post();
		$html .= "<div>";
		if(get_post_meta($post->ID, '_socio_logo', true ) != '') $html .= "<img src='".get_post_meta($post->ID, '_socio_logo', true )."' alt='".get_the_title($post->ID)."' style='max-width: 200px' /><br/>"; //Imagen principal
		$html .= get_the_title($post->ID)."<br/>"; //Nombre
		$html .= get_post_meta($post->ID, '_socio_calle', true )."<br/>"; //Dirección
		if(count(get_post_meta($post->ID, '_socio_areas_cluster', true )) > 0) {
			$html .= "<ul>";
			foreach(get_post_meta($post->ID, '_socio_areas_cluster', true ) as $value)  {
				$html .= "<li>".translateAreasCluster($value)."</li>";
			}
			$html .= "</ul>";
		}
		if(count(get_post_meta($post->ID, '_socio_presencia_internacional', true )) > 0) {
			$html .= "<ul>";
			foreach(get_post_meta($post->ID, '_socio_presencia_internacional', true ) as $value)  {
				$html .= "<li>".translatePresenciaInternacional($value)."</li>";
			}
			$html .= "</ul>";
		}
		$html .= get_the_content($post->ID); //Descripción general
		$html .= "</div><hr/>";        
        }
    } wp_reset_query();
    return $html; 
}
add_shortcode('socios', 'shortcode_mlcluster_socios');

