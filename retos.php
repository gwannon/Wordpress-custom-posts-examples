<?php 

//Retos -------------------------
add_action( 'init', 'azti_reto_create_post_type' );
function azti_reto_create_post_type() {
	$labels = array(
		'name'               => __( 'Retos', 'the7mk2' ),
		'singular_name'      => __( 'Reto', 'the7mk2' ),
		'add_new'            => __( 'Añadir nuevo', 'the7mk2' ),
		'add_new_item'       => __( 'Añadir nuevo reto', 'the7mk2' ),
		'edit_item'          => __( 'Editar reto', 'the7mk2' ),
		'new_item'           => __( 'Nuevo reto', 'the7mk2' ),
		'all_items'          => __( 'Todos los retos', 'the7mk2' ),
		'view_item'          => __( 'Ver reto', 'the7mk2' ),
		'search_items'       => __( 'Buscar reto', 'the7mk2' ),
		'not_found'          => __( 'Reto no encontrado', 'the7mk2' ),
		'not_found_in_trash' => __( 'Reto no encontrado en la papelera', 'the7mk2' ),
		'menu_name'          => __( 'Retos', 'the7mk2' ),
	);
	$args = array(
		'labels'        => $labels,
		'description'   => __( 'Añadir nuevo reto', 'the7mk2' ),
		'public'        => true,
		'menu_position' => 7,
		'query_var' 	=> true,
		'supports'      => array( 'title', 'editor', 'thumbnail' ),
		'rewrite'	=> array('slug' => 'reto', 'with_front' => false),
		'query_var'	=> true,
		'has_archive' 	=> false,
		'hierarchical'	=> true,
	);
	register_post_type( 'reto', $args );
}

//CAMPOS personalizados -----------------------------------
function get_retos_custom_fields () {
	$fields = array(
		'estado' => array ('titulo' => "ESTADO", 'tipo' => 'select', "valores" => array("en_proceso" =>  __('En proceso'), "terminado" => __('Terminado'))),

		'tipo' => array ('titulo' => "TIPO", 'tipo' => 'select', "valores" => array("abierto" =>  __('Público'), "cerrado" => __('Privado'))),

		'estadistica_valor_1' => array ('titulo' => "Estadística 1", 'tipo' => 'text'),
		'estadistica_tipo_1' => array ('titulo' => "", 'tipo' => 'select', "valores" => array("porcentaje" =>  __('Porcentaje'), "numero" => __('Número'))),
		'estadistica_texto_1' => array ('titulo' => "", 'tipo' => 'textarea'),

		'estadistica_valor_2' => array ('titulo' => "Estadística 2", 'tipo' => 'text'),
		'estadistica_tipo_2' => array ('titulo' => "", 'tipo' => 'select', "valores" => array("porcentaje" =>  __('Porcentaje'), "numero" => __('Número'))),
		'estadistica_texto_2' => array ('titulo' => "", 'tipo' => 'textarea'),

		'estadistica_valor_3' => array ('titulo' => "Estadística 3", 'tipo' => 'text'),
		'estadistica_tipo_3' => array ('titulo' => "", 'tipo' => 'select', "valores" => array("porcentaje" =>  __('Porcentaje'), "numero" => __('Número'))),
		'estadistica_texto_3' => array ('titulo' => "", 'tipo' => 'textarea'),

		'estadistica_valor_4' => array ('titulo' => "Estadística 4", 'tipo' => 'text'),
		'estadistica_tipo_4' => array ('titulo' => "", 'tipo' => 'select', "valores" => array("porcentaje" =>  __('Porcentaje'), "numero" => __('Número'))),
		'estadistica_texto_4' => array ('titulo' => "", 'tipo' => 'textarea'),

		'estadistica_valor_5' => array ('titulo' => "Estadística 5", 'tipo' => 'text'),
		'estadistica_tipo_5' => array ('titulo' => "", 'tipo' => 'select', "valores" => array("porcentaje" =>  __('Porcentaje'), "numero" => __('Número'))),
		'estadistica_texto_5' => array ('titulo' => "", 'tipo' => 'textarea'),

		'oportunidades' => array ('titulo' => "Oportunidades para la acción", 'tipo' => 'textarea'),
		'oportunidadesimagen' => array ('titulo' => "Imagen para oportunidades para la acción", 'tipo' => 'image'),
		'inspiracion' => array ('titulo' => "Inspiración", 'tipo' => 'textarea'),
		'inspiracionimagen' => array ('titulo' => "Imagen para inspiración", 'tipo' => 'image'),
		'resultados' => array ('titulo' => "Resultados colaborativos", 'tipo' => 'textarea'),
		'resultadosvideo' => array ('titulo' => "Vídeo", 'tipo' => 'text'),
		'descargable_url' => array ('titulo' => "URL del informe descargable", 'tipo' => 'link'),
	);
	return $fields;
}

function wp_retos_add_custom_fields() {
    add_meta_box(
        'box_retos', // $id
        __('Datos'), // $title 
        'wp_retos_show_custom_fields', // $callback
        'reto', // $page
        'normal', // $context
        'high'); // $priority
}
add_action('add_meta_boxes', 'wp_retos_add_custom_fields');

function wp_retos_show_custom_fields() { //Show box
	global $post; 
	$fields = get_retos_custom_fields ();
	foreach ($fields as $field => $datos) { ?>
		<p><b><?php echo $datos['titulo']; ?></b></p>
		<?php if($datos['tipo'] == 'text' || $datos['tipo'] == 'link') { ?>
			<input  type="text" class="_reto_<?php echo $field; ?>" id="_reto_<?php echo $field; ?>" style="width: 100%;" name="_reto_<?php echo $field; ?>" value="<?php echo get_post_meta( $post->ID, '_reto_'.$field, true ); ?>" />
		<?php } else if($datos['tipo'] == 'image') { ?>
			<input type="text" class="_reto_<?php echo $field; ?>" id="input_reto_<?php echo $field; ?>" style="width: 80%;" name="_reto_<?php echo $field; ?>" value='<?php echo get_post_meta( $post->ID, '_reto_'.$field, true ); ?>' />
			<a href="#" id="button_reto_<?php echo $field; ?>" class="button insert-media add_media" data-editor="input_reto_<?php echo $field; ?>" title="<?php _e("Añadir fichero"); ?>"><span class="wp-media-buttons-icon"></span> <?php _e("Añadir fichero"); ?></a>
			<script>
				jQuery(document).ready(function () {			
					jQuery("#input_reto_<?php echo $field; ?>").change(function() {
						a_imgurlar = jQuery(this).val().match(/<a href=\"([^\"]+)\"/);
						img_imgurlar = jQuery(this).val().match(/<img[^>]+src=\"([^\"]+)\"/);
						if(img_imgurlar !==null ) jQuery(this).val(img_imgurlar[1]);
						else  jQuery(this).val(a_imgurlar[1]);
					});
				});
			</script>
		<?php } else if($datos['tipo'] == 'text') { ?>
			<input  type="text" class="_reto_<?php echo $field; ?>" id="_reto_<?php echo $field; ?>" style="width: 100%;" name="_reto_<?php echo $field; ?>" value="<?php echo get_post_meta( $post->ID, '_reto_'.$field, true ); ?>" />
		<?php } else if($datos['tipo'] == 'textarea') { ?>
			<?php $settings = array( 'media_buttons' => true, 'quicktags' => true, 'textarea_rows' => 5 ); ?>
			<?php wp_editor( get_post_meta( $post->ID, '_reto_'.$field, true ), '_reto_'.$field, $settings ); ?>
		<?php } else if ($datos['tipo'] == 'select') { ?>
          	<select name="_reto_<?php echo $field; ?>">
          	<?php foreach($datos['valores'] as $key => $value) { ?>
            	<option value="<?php echo $key; ?>"<?php if ($key == get_post_meta( $post->ID, '_reto_'.$field, true )) echo " selected='selected'"; ?>><?php echo $value; ?></option>
        	<?php } ?>	
			</select>
        <?php }  ?>
		<br/><br/>
	<?php }
}

function wp_retos_save_custom_fields( $post_id ) { //Save changes
	global $wpdb;
	$fields = get_retos_custom_fields ();
	foreach ($fields as $field => $datos) {
		$label = '_reto_'.$field;
		if (isset($_POST[$label])) update_post_meta( $post_id, $label, $_POST[$label]);
	}
}
add_action( 'save_post', 'wp_retos_save_custom_fields' );

//Socios relacionados --------------------------------------------------
function  azti_reto_add_socios() {
    add_meta_box(
        'socios', // $id
        __('Socios relacionados', 'the7mk2'), // $title 
        'azti_reto_show_socios', // $callback
        'reto', // $page
        'normal', // $context
        'high'); // $priority
}
add_action('add_meta_boxes', 'azti_reto_add_socios');

function azti_reto_show_socios() { //Show box
	global $post;
	$args = array(
		'role'    => 'subscriber',
		'orderby' => 'user_nicename',
		'order'   => 'ASC'
	);
	$socios = get_users( $args );
	
	foreach ($socios as $socio) {
		?>
		<div style="width: 33%; float: left;">
		<input type="checkbox" name="_reto_socios[]" <?php if (in_array($socio->ID, json_decode(get_post_meta( $post->ID, '_reto_socios', true ), true))) echo " checked='checked'"; ?>value="<?php echo $socio->ID; ?>"> <?php echo $socio->user_login; ?>
		</div>
		<?php
	}
	?><div style="clear: both"></div><?php
}

function azti_reto_save_socios( $post_id ) { //Save changes
	if (isset($_POST['_reto_socios']) && count($_POST['_reto_socios']) > 0) update_post_meta( $post_id, '_reto_socios', json_encode($_POST['_reto_socios']) );
	else update_post_meta( $post_id, '_reto_socios', "");
}

add_action( 'save_post', 'azti_reto_save_socios' );

//Eventos relacionados --------------------------------------------------
function  azti_reto_add_eventos() {
  add_meta_box(
      'eventos', // $id
      __('Eventos relacionados', 'the7mk2'), // $title 
      'azti_reto_show_eventos', // $callback
      'reto', // $page
      'normal', // $context
      'high'); // $priority
}
add_action('add_meta_boxes', 'azti_reto_add_eventos');

function azti_reto_show_eventos() { //Show box
  global $post;
  $eventos = get_posts(array( 'order' => 'ASC', 'orderby' => 'title', 'post_type' => 'post', 'category' => EVENTOS_CAT, 'post_status' => 'publish', 'posts_per_page'   => -1)); 


  foreach ($eventos as $evento) {
    ?>
    <div style="width: 33%; float: left;">
    <input type="checkbox" name="_reto_eventos[]" <?php if (in_array($evento->ID, json_decode(get_post_meta( $post->ID, '_reto_eventos', true ), true))) echo " checked='checked'"; ?>value="<?php echo $evento->ID; ?>"> <?php echo apply_filters("the_title", $evento->post_title); ?>
    </div>
    <?php
  }
  ?><div style="clear: both"></div><?php
}

function azti_reto_save_eventos( $post_id ) { //Save changes
  if (isset($_POST['_reto_eventos']) && count($_POST['_reto_eventos']) > 0) update_post_meta( $post_id, '_reto_eventos', json_encode($_POST['_reto_eventos']) );
  else update_post_meta( $post_id, '_reto_eventos', "");
}

add_action( 'save_post', 'azti_reto_save_eventos' );
