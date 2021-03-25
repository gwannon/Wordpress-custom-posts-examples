<?php 

// Tratamientos ---------------------------------------
// ----------------------------------------------------
add_action( 'init', 'linares_tratamiento_create_post_type' );
function linares_tratamiento_create_post_type() {
	$labels = array(
		'name'               => __( 'Tratamientos', 'the7mk2' ),
		'singular_name'      => __( 'Tratamiento', 'the7mk2' ),
		'add_new'            => __( 'Añadir nuevo', 'the7mk2' ),
		'add_new_item'       => __( 'Añadir nuevo Tratamiento', 'the7mk2' ),
		'edit_item'          => __( 'Editar Tratamiento', 'the7mk2' ),
		'new_item'           => __( 'Nuevo Tratamiento', 'the7mk2' ),
		'all_items'          => __( 'Todas las tratamientos', 'the7mk2' ),
		'view_item'          => __( 'Ver tratamiento', 'the7mk2' ),
		'search_items'       => __( 'Buscar tratamiento', 'the7mk2' ),
		'not_found'          => __( 'Tratamiento no encontrado', 'the7mk2' ),
		'not_found_in_trash' => __( 'Tratamiento no encontrado en la papelera', 'the7mk2' ),
		'menu_name'          => __( 'Tratamientos', 'the7mk2' ),
	);
	$args = array(
		'labels'        => $labels,
		'description'   => __( 'Añadir nuevo tratamiento', 'the7mk2' ),
		'public'        => true,
		'menu_position' => 7,
		'query_var' 	=> true,
		'supports'      => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
		'rewrite'	=> array('slug' => 'tratamiento', 'with_front' => false),
		'query_var'	=> true,
		'has_archive' 	=> false,
		'hierarchical'	=> true,
	);
	register_post_type( 'tratamiento', $args );
}

//Tipo -------------------------
add_action( 'init', 'linares_tratamiento_create_type' );
function linares_tratamiento_create_type() {
	$labels = array(
		'name'              => __( 'Tipos', 'the7mk2' ),
		'singular_name'     => __( 'Tipo', 'the7mk2' ),
		'search_items'      => __( 'Buscar tipo', 'the7mk2' ),
		'all_items'         => __( 'Todos los tipos', 'the7mk2' ),
		'parent_item'       => __( 'Pariente tipo', 'the7mk2' ),
		'parent_item_colon' => __( 'Pariente tipo', 'the7mk2' ).":",
		'edit_item'         => __( 'Editar tipo', 'the7mk2' ),
		'update_item'       => __( 'Actualizar tipo', 'the7mk2' ),
		'add_new_item'      => __( 'Añadir tipo', tipo ),
		'new_item_name'     => __( 'Nuevo tipo', 'the7mk2' ),
		'menu_name'         => __( 'Tipos', 'the7mk2' ),
	);
	$args = array(
		'labels' 		=> $labels,
		'hierarchical' 		=> true,
		//'public'		=> true,
		'query_var'		=> true,
		'show_in_nav_menus'	=> true,
		'rewrite'		=>  array('slug' => 'tipo', 'with_front' => false ),
		//'_builtin'		=> false,
	);
	register_taxonomy( 'tipo', 'tratamiento', $args );
}



function linares_extra_type_fields($tag) {
	//check for existing taxonomy meta for term ID
	$t_id = $tag->term_id;
	$term_meta = get_option( "taxonomy_$t_id");
	?>
	<tr class="form-field">
		<th scope="row" valign="top"><?php _e('URL imagen', tipo); ?></th>
		<td>
			<input type="text" name="term_meta[imagen]" id="term_meta_imagen" size="3" style="width:60%;" value="<?php echo $term_meta['imagen'] ? $term_meta['imagen'] : ''; ?>">
			
					<a href="#" id="button_term_meta_imagen" class="button insert-media add_media" data-editor="term_meta_imagen" title="<?php _e("Añadir fichero", 'the7mk2'); ?>"><span class="wp-media-buttons-icon"></span> <?php _e("Añadir fichero", 'the7mk2'); ?></a>
					<script>
						jQuery(document).ready(function () {			
							jQuery("#term_meta_imagen").change(function() {
								a_imgurlar = jQuery(this).val().match(/<a href=\"([^\"]+)\"/);
								img_imgurlar = jQuery(this).val().match(/<img[^>]+src=\"([^\"]+)\"/);
								if(img_imgurlar !==null ) {
									jQuery(this).val(img_imgurlar[1]);
									jQuery("#preview_term_meta_imagen").attr("src", img_imgurlar[1]);
									
								} else  jQuery(this).val(a_imgurlar[1]);
							});
						});
					</script>
					<br/><img id="preview_term_meta_imagen" src="<?php echo $term_meta['imagen'] ? $term_meta['imagen'] : ''; ?>" style="max-width: 200px;" />
			
			
		</td>
	</tr>
	<?php
}

add_action( 'tipo_edit_form_fields', 'linares_extra_type_fields', 10, 2);
 
function linares_save_extra_type_fields( $term_id ) {
	if ( isset( $_POST['term_meta'] ) ) {
		$t_id = $term_id;
		$term_meta = get_option( "taxonomy_$t_id");
		$cat_keys = array_keys($_POST['term_meta']);
		foreach ($cat_keys as $key){
			if (isset($_POST['term_meta'][$key])){
				$term_meta[$key] = $_POST['term_meta'][$key];
			}
		}
		update_option( "taxonomy_$t_id", $term_meta );
	}
}

add_action( 'edited_tipo', 'linares_save_extra_type_fields', 10, 2);
