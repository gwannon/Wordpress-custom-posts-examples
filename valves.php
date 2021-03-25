<?php 

//Valvulas -------------------------
add_action( 'init', 'wp_valve_create_post_type' );
function wp_valve_create_post_type() {

	$labels = array(
		'name'               => __( 'Valvulas', 'valvulas' ),
		'singular_name'      => __( 'Valvula', 'valvulas' ),
		'add_new'            => __( 'Añadir nueva', 'valvulas' ),
		'add_new_item'       => __( 'Añadir nueva valvula', 'valvulas' ),
		'edit_item'          => __( 'Editar valvula', 'valvulas' ),
		'new_item'           => __( 'Nueva valvula', 'valvulas' ),
		'all_items'          => __( 'Todas las valvulas', 'valvulas' ),
		'view_item'          => __( 'Ver valvula', 'valvulas' ),
		'search_items'       => __( 'Buscar valvula', 'valvulas' ),
		'not_found'          => __( 'Valvula no encontrada', 'valvulas' ),
		'not_found_in_trash' => __( 'Valvula no encontrada en la papelera', 'valvulas' ),
		'menu_name'          => __( 'Valvulas', 'valvulas' ),
	);
	$args = array(
		'labels'        => $labels,
		'description'   => __( 'Añadir nueva valvula', 'valvulas' ),
		'public'        => true,
		'menu_position' => 6,
		'taxonomies' 	=> array('type', 'application', 'serie'),
		'query_var' 	=> true,
		'supports'      => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
		//'rewrite'	=> array('slug' => 'valvulas/%tipos%/%aplicacion%', 'with_front' => false),
		'rewrite'	=> array('slug' => 'valvulas', 'with_front' => false),
		'query_var'	=> true,
		'has_archive' 	=> 'type',
		'hierarchical'	=> true,
	);
	register_post_type( 'valve', $args );
}

//Tipo -------------------------
add_action( 'init', 'wp_valve_create_type' );
function wp_valve_create_type() {
	$labels = array(
		'name'              => __( 'Tipos', 'valvulas' ),
		'singular_name'     => __( 'Tipo', 'valvulas' ),
		'search_items'      => __( 'Buscar tipo', 'valvulas' ),
		'all_items'         => __( 'Todos los tipos', 'valvulas' ),
		'parent_item'       => __( 'Pariente tipo', 'valvulas' ),
		'parent_item_colon' => __( 'Pariente tipo', 'valvulas' ).":",
		'edit_item'         => __( 'Editar tipo', 'valvulas' ),
		'update_item'       => __( 'Actualizar tipo', 'valvulas' ),
		'add_new_item'      => __( 'Añadir tipo', 'valvulas' ),
		'new_item_name'     => __( 'Nuevo tipo', 'valvulas' ),
		'menu_name'         => __( 'Tipos', 'valvulas' ),
	);
	$args = array(
		'labels' 		=> $labels,
		'hierarchical' 		=> true,
		//'public'		=> true,
		'query_var'		=> true,
		'show_in_nav_menus'	=> true,
		'rewrite'		=>  array('slug' => 'tipo-valvulas', 'with_front' => false ),
		//'_builtin'		=> false,
	);
	register_taxonomy( 'type', 'valve', $args );
}

add_filter('post_link', 'wp_valve_permalink', 1, 3);
add_filter('post_type_link', 'wp_valve_permalink', 1, 3);
function wp_valve_permalink($permalink, $post_id) {
	if (strpos($permalink, '%tipos%') === FALSE) return $permalink;

	$post = get_post($post_id);
	if (!$post) return $permalink;

	$terms = wp_get_object_terms($post->ID, 'type');
	if (!is_wp_error($terms) && !empty($terms) && is_object($terms[0])) $type_slug = $terms[0]->slug;
	else $type_slug = 'general';

	$terms = wp_get_object_terms($post->ID, 'application');
	if (!is_wp_error($terms) && !empty($terms) && is_object($terms[0])) $application_slug = $terms[0]->slug;
	else $application_slug = 'general';

	return str_replace('%tipos%', $type_slug, str_replace('%aplicacion%', $application_slug, $permalink));
	return $permalink;
}

function wp_valve_extra_type_fields($tag) {
	//check for existing taxonomy meta for term ID
	$t_id = $tag->term_id;
	$term_meta = get_option( "taxonomy_$t_id");
	?>
	<tr class="form-field">
		<th scope="row" valign="top"><?php _e('URL thumbnail', "valvulas"); ?></th>
		<td>
			<input type="text" name="term_meta[imagen]" id="term_meta[imagen]" size="3" style="width:60%;" value="<?php echo $term_meta['imagen'] ? $term_meta['imagen'] : ''; ?>">
		</td>
	</tr>
	<?php
}

add_action( 'type_edit_form_fields', 'wp_valve_extra_type_fields', 10, 2);
 
function wp_valve_save_extra_type_fields( $term_id ) {
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

add_action( 'edited_type', 'wp_valve_save_extra_type_fields', 10, 2);

//Aplicacion -------------------------
add_action( 'init', 'wp_valve_create_application' );
function wp_valve_create_application() {
	$labels = array(
		'name'              => __( 'Aplicaciones', 'valvulas' ),
		'singular_name'     => __( 'Aplicación', 'valvulas' ),
		'search_items'      => __( 'Buscar aplicación', 'valvulas' ),
		'all_items'         => __( 'Todas las aplicaciones', 'valvulas' ),
		'parent_item'       => __( 'Pariente aplicación', 'valvulas' ),
		'parent_item_colon' => __( 'Pariente aplicación', 'valvulas' ).":",
		'edit_item'         => __( 'Editar aplicación', 'valvulas' ),
		'update_item'       => __( 'Actualizar aplicación', 'valvulas' ),
		'add_new_item'      => __( 'Añadir aplicación', 'valvulas' ),
		'new_item_name'     => __( 'Nueva aplicación', 'valvulas' ),
		'menu_name'         => __( 'Aplicaciones', 'valvulas' ),
	);
	$args = array(
		'labels' => $labels,
		'hierarchical' 	=> true,
		//'public'		=> true,
		'query_var'		=> true,
		'show_in_nav_menus'   => true,
		'rewrite'		=>  array('slug' => 'aplicacion-valvulas','with_front' => true ),
		//'_builtin'		=> false,
	);
	register_taxonomy( 'application', 'valve', $args );
}

function wp_valve_extra_application_fields($tag) {
	//check for existing taxonomy meta for term ID
	$t_id = $tag->term_id;
	$term_meta = get_option( "taxonomy_$t_id");
	?>
	<tr class="form-field">
		<th scope="row" valign="top"><?php _e('URL thumbnail', "valvulas"); ?></th>
		<td>
			<input type="text" name="term_meta[imagen]" id="term_meta[imagen]" size="3" style="width:60%;" value="<?php echo $term_meta['imagen'] ? $term_meta['imagen'] : ''; ?>">
		</td>
	</tr>
	<?php
}

add_action( 'application_edit_form_fields', 'wp_valve_extra_application_fields', 10, 2);
 
function wp_valve_save_extra_application_fields( $term_id ) {
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

add_action( 'edited_application', 'wp_valve_save_extra_application_fields', 10, 2);

//Serie -------------------------
add_action( 'init', 'wp_valve_create_serie' );
function wp_valve_create_serie() {
	$labels = array(
		'name'              => __( 'Series', 'valvulas' ),
		'singular_name'     => __( 'Serie', 'valvulas' ),
		'search_items'      => __( 'Buscar serie', 'valvulas' ),
		'all_items'         => __( 'Todas las series', 'valvulas' ),
		'parent_item'       => __( 'Pariente serie', 'valvulas' ),
		'parent_item_colon' => __( 'Pariente serie', 'valvulas' ).":",
		'edit_item'         => __( 'Editar serie', 'valvulas' ),
		'update_item'       => __( 'Actualizar serie', 'valvulas' ),
		'add_new_item'      => __( 'Añadir serie', 'valvulas' ),
		'new_item_name'     => __( 'Nueva serie', 'valvulas' ),
		'menu_name'         => __( 'Series', 'valvulas' ),
	);
	$args = array(
		'labels' => $labels,
		'hierarchical' 	=> true,
		//'public'		=> true,
		'query_var'		=> true,
		'show_in_nav_menus'   => true,
		'rewrite'		=>  array('slug' => 'serie-valvulas', 'with_front' => true ),
		//'_builtin'		=> false,
	);
	register_taxonomy( 'serie', 'valve', $args );
}

//Catálogo ---------------------------------------------------
function wp_valve_add_catalogo() {
    add_meta_box(
        'box_catalogo', // $id
        __('Catálogo', 'valvulas'), // $title 
        'wp_valve_show_catalogo', // $callback
        'valve', // $page
        'normal', // $context
        'high'); // $priority
}
add_action('add_meta_boxes', 'wp_valve_add_catalogo');

function wp_valve_show_catalogo() { //Show box
	global $post;
	?>
	<select name="catalogo">
		<option value="1"<?php if (get_post_meta( $post->ID, 'catalogo', true ) == 1) echo " selected='selected'"; ?>><?php _e("Catálogo CMO", "valvulas"); ?></option>
		<option value="2"<?php if (get_post_meta( $post->ID, 'catalogo', true ) == 2) echo " selected='selected'"; ?>><?php _e("Catálogo Water supplies", "valvulas"); ?></option>

	</select>
	<?php
}

function wp_valve_save_catalogo( $post_id ) { //Save changes
	if (isset($_POST['catalogo'])) update_post_meta( $post_id, 'catalogo', $_POST['catalogo']);
}
add_action( 'save_post', 'wp_valve_save_catalogo' );

//Utilizacion ---------------------------------------------------
function wp_valve_add_utilizacion() {
    add_meta_box(
        'box_utilizacion', // $id
        __('Utilización', 'valvulas'), // $title 
        'wp_valve_show_utilizacion', // $callback
        'valve', // $page
        'normal', // $context
        'high'); // $priority
}
add_action('add_meta_boxes', 'wp_valve_add_utilizacion');

function wp_valve_show_utilizacion() { //Show box
	global $post;
	echo "<br/>";
	$settings = array( 'media_buttons' => false, 'quicktags' => false, 'textarea_rows' => 5 );
        wp_editor( get_post_meta( $post->ID, 'utilizacion', true ), 'utilizacion', $settings ); 
}

function wp_valve_save_utilizacion( $post_id ) { //Save changes
	if (isset($_POST['utilizacion'])) update_post_meta( $post_id, 'utilizacion', $_POST['utilizacion']);
}
add_action( 'save_post', 'wp_valve_save_utilizacion' );

//Dimensiones ---------------------------------------------------
function wp_valve_add_dimensiones() {
    add_meta_box(
        'box_dimensiones', // $id
        __('Dimensiones', 'valvulas'), // $title 
        'wp_valve_show_dimensiones', // $callback
        'valve', // $page
        'normal', // $context
        'high'); // $priority
}
add_action('add_meta_boxes', 'wp_valve_add_dimensiones');

function wp_valve_show_dimensiones() { //Show box
	global $post;
	echo "<br/>";
	$settings = array( 'media_buttons' => false, 'quicktags' => false, 'textarea_rows' => 5 );
        wp_editor( get_post_meta( $post->ID, 'dimensiones', true ), 'dimensiones', $settings ); 
}

function wp_valve_save_dimensiones( $post_id ) { //Save changes
	if (isset($_POST['dimensiones'])) update_post_meta( $post_id, 'dimensiones', $_POST['dimensiones']);
}
add_action( 'save_post', 'wp_valve_save_dimensiones' );

//Presiones de trabajo ---------------------------------------------------
function wp_valve_add_presiones() {
    add_meta_box(
        'box_presiones', // $id
        __('Presiones de trabajo', 'valvulas'), // $title 
        'wp_valve_show_presiones', // $callback
        'valve', // $page
        'normal', // $context
        'high'); // $priority
}
add_action('add_meta_boxes', 'wp_valve_add_presiones');

function wp_valve_show_presiones() { //Show box
	global $post;
	echo "<br/>";
	$settings = array( 'media_buttons' => false, 'quicktags' => false, 'textarea_rows' => 5 );
        wp_editor( get_post_meta( $post->ID, 'presiones', true ), 'presiones', $settings ); 
}

function wp_valve_save_presiones( $post_id ) { //Save changes
	if (isset($_POST['presiones'])) update_post_meta( $post_id, 'presiones', $_POST['presiones']);
}
add_action( 'save_post', 'wp_valve_save_presiones' );


//Imagen 360 -----------------------
function wp_valve_add_imagen360() {
    add_meta_box(
        'box_3d', // $id
        __('Imagen 360', 'valvulas'), // $title 
        'wp_valve_show_imagen360', // $callback
        'valve', // $page
        'normal', // $context
        'high'); // $priority
}
add_action('add_meta_boxes', 'wp_valve_add_imagen360');

function wp_valve_show_imagen360() { //Show box
	global $post; ?>
		<input class="imagen360" style="width: 100%;" id="imagen360" name="imagen360" value='<?php echo get_post_meta( $post->ID, 'imagen360', true ); ?>' />
	<?php
}

function wp_valve_save_imagen360( $post_id ) { //Save changes
	if (isset($_POST['imagen360'])) update_post_meta( $post_id, 'imagen360', $_POST['imagen360']);
}
add_action( 'save_post', 'wp_valve_save_imagen360' );

//Destacado en portada ---------------------------------------------------
function wp_valve_add_featured() {
    add_meta_box(
        'box_featured', // $id
        __('Destacado en portada', 'valvulas'), // $title 
        'wp_valve_show_featured', // $callback
        'valve', // $page
        'normal', // $context
        'high'); // $priority
}
add_action('add_meta_boxes', 'wp_valve_add_featured');

function wp_valve_show_featured() { //Show box
	global $post; ?>
	<select name="featured">
		<option value="0"<?php if (get_post_meta( $post->ID, 'featured', true ) == 0) echo " selected='selected'"; ?>><?php _e("No", "valvulas"); ?></option>
		<option value="1"<?php if (get_post_meta( $post->ID, 'featured', true ) == 1) echo " selected='selected'"; ?>><?php _e("Sí", "valvulas"); ?></option>
	</select>
	<br/><br/>
	<?php 
	$settings = array( 'media_buttons' => false, 'quicktags' => false, 'textarea_rows' => 5 );
        wp_editor( get_post_meta( $post->ID, 'featuredtext', true ), 'featuredtext', $settings ); 
}

function wp_valve_save_featured( $post_id ) { //Save changes
	if (isset($_POST['featured'])) update_post_meta( $post_id, 'featured', $_POST['featured']);
	if (isset($_POST['featuredtext'])) update_post_meta( $post_id, 'featuredtext', $_POST['featuredtext']);
}
add_action( 'save_post', 'wp_valve_save_featured' );



//Descargables -----------------------
function wp_valve_add_descarga() {
    add_meta_box(
        'box_descarga', // $id
        __('Descargables', 'valvulas'), // $title 
        'wp_valve_show_descarga', // $callback
        'valve', // $page
        'normal', // $context
        'high'); // $priority
}
add_action('add_meta_boxes', 'wp_valve_add_descarga');

function wp_valve_show_descarga() { //Show box
	global $post, $valves_langs; ?>
	<h4><?php _e("Catálogo de producto", "sanmames"); ?></h4>
	<?php foreach ($valves_langs as $key => $lang) { ?>
		<a class="pnbuttondescarga1 qtranslate_lang_div" id="a1_<?php echo $lang; ?>" style="padding: 5px 10px;<?php if($key > 0) echo ' background-color: #BBB;'; ?>"><img src="/wp-content/plugins/qtranslate-x/flags/<?php echo $lang; ?>.png" title="<?php echo $lang; ?>" alt="<?php echo $lang; ?>"></a>
	<?php } 
	foreach ($valves_langs as $key => $lang) { $label = 'descarga1_'.$lang; ?>
		<input id="input_<?php echo $label; ?>" class="input_descarga1 checker" style="width: 80%;<?php if($key > 0) echo ' display: none;'; ?>" name="<?php echo $label; ?>" value='<?php echo get_post_meta( $post->ID, $label, true ); ?>' />
		<a href="#" id="button_<?php echo $label; ?>" class="button_descarga1 button insert-media add_media" data-editor="input_<?php echo $label; ?>" style="<?php if($key > 0) echo ' display: none;'; ?>" title="<?php _e("Añadir fichero", "sanmames"); ?>"><span class="wp-media-buttons-icon"></span> <?php _e("Añadir fichero", "sanmames"); ?></a>
	<?php } ?>


	<h4><?php _e("Ficha de producto", "sanmames"); ?></h4>
	<?php foreach ($valves_langs as $key => $lang) { ?>
		<a class="pnbuttondescarga2 qtranslate_lang_div" id="a2_<?php echo $lang; ?>" style="padding: 5px 10px;<?php if($key > 0) echo ' background-color: #BBB;'; ?>"><img src="/wp-content/plugins/qtranslate-x/flags/<?php echo $lang; ?>.png" title="<?php echo $lang; ?>" alt="<?php echo $lang; ?>"></a>
	<?php } 
	foreach ($valves_langs as $key => $lang) { $label = 'descarga2_'.$lang; ?>
		<input id="input_<?php echo $label; ?>" class="input_descarga2 checker" style="width: 80%;<?php if($key > 0) echo ' display: none;'; ?>" name="<?php echo $label; ?>" value='<?php echo get_post_meta( $post->ID, $label, true ); ?>' />
		<a href="#" id="button_<?php echo $label; ?>" class="button_descarga2 button insert-media add_media" data-editor="input_<?php echo $label; ?>" style="<?php if($key > 0) echo ' display: none;'; ?>" title="<?php _e("Añadir fichero", "sanmames"); ?>"><span class="wp-media-buttons-icon"></span> <?php _e("Añadir fichero", "sanmames"); ?></a>
	<?php }?>


	<h4><?php _e("Manual de mantenimiento", "sanmames"); ?></h4>
	<?php foreach ($valves_langs as $key => $lang) { ?>
		<a class="pnbuttondescarga3 qtranslate_lang_div" id="a3_<?php echo $lang; ?>" style="padding: 5px 10px;<?php if($key > 0) echo ' background-color: #BBB;'; ?>"><img src="/wp-content/plugins/qtranslate-x/flags/<?php echo $lang; ?>.png" title="<?php echo $lang; ?>" alt="<?php echo $lang; ?>"></a>
	<?php } 
	foreach ($valves_langs as $key => $lang) { $label = 'descarga3_'.$lang; ?>
		<input id="input_<?php echo $label; ?>" class="input_descarga3 checker" style="width: 80%;<?php if($key > 0) echo ' display: none;'; ?>" name="<?php echo $label; ?>" value='<?php echo get_post_meta( $post->ID, $label, true ); ?>' />
		<a href="#" id="button_<?php echo $label; ?>" class="button_descarga3 button insert-media add_media" data-editor="input_<?php echo $label; ?>" style="<?php if($key > 0) echo ' display: none;'; ?>" title="<?php _e("Añadir fichero", "sanmames"); ?>"><span class="wp-media-buttons-icon"></span> <?php _e("Añadir fichero", "sanmames"); ?></a>
	<?php } ?>
	<script>

		var inputid;

		jQuery(document).ready(function () {


			jQuery("a.button.insert-media.add_media").click(function() {
				inputid = jQuery(this).attr("id").replace("button_", "input_");
				console.log(inputid);

			});


		    window.original_send_to_editor = window.send_to_editor;
		    window.send_to_editor = function( html ) {
				var imgurlar = html.match(/<a[^>]+href=\"([^\"]+)\"/);
				var imgurl = imgurlar[1];

				//html is returning only title of the media
				//alert(html);

				//image
				if( imgurl.length ){
				    jQuery("#"+inputid).val(imgurl);
				}
				//other types of files
				else{
				    var fileurl = jQuery(html);
				}
		    };






			jQuery (".pnbuttondescarga1").click(function(){
				jQuery(".pnbuttondescarga1").css("background-color", "#BBB");
				jQuery(".input_descarga1").hide();	
				jQuery(".button_descarga1").hide();
				jQuery(this).css("background-color", "#FFF");
				jQuery("#"+jQuery(this).attr('id').replace('a1_', 'input_descarga1_')).show();
				jQuery("#"+jQuery(this).attr('id').replace('a1_', 'button_descarga1_')).show();
			});


			jQuery (".pnbuttondescarga2").click(function(){
				jQuery(".pnbuttondescarga2").css("background-color", "#BBB");
				jQuery(".input_descarga2").hide();
				jQuery(".button_descarga2").hide();	
				jQuery(this).css("background-color", "#FFF");
				jQuery("#"+jQuery(this).attr('id').replace('a2_', 'input_descarga2_')).show();
				jQuery("#"+jQuery(this).attr('id').replace('a2_', 'button_descarga2_')).show();
			});

			jQuery (".pnbuttondescarga3").click(function(){
				jQuery(".pnbuttondescarga3").css("background-color", "#BBB");
				jQuery(".input_descarga3").hide();
				jQuery(".button_descarga3").hide();
				jQuery(this).css("background-color", "#FFF");
				jQuery("#"+jQuery(this).attr('id').replace('a3_', 'input_descarga3_')).show();
				jQuery("#"+jQuery(this).attr('id').replace('a3_', 'button_descarga3_')).show();
			});

		});
	</script>
	<?php 
}

// add necessary scripts
add_action('plugins_loaded', function(){ 
    if($GLOBALS['pagenow']=='post.php'){
        add_action('admin_print_scripts', 'my_admin_scripts');
        add_action('admin_print_styles',  'my_admin_styles');
    }
});

function my_admin_scripts() { wp_enqueue_script('jquery');    wp_enqueue_script('media-upload');   wp_enqueue_script('thickbox'); }   //  //wp_register_script('my-upload', WP_PLUGIN_URL.'/my-script.js', array('jquery','media-upload','thickbox'));  wp_enqueue_script('my-upload');
function my_admin_styles()  { wp_enqueue_style('thickbox'); }





function wp_valve_save_descarga( $post_id ) { //Save changes
	global $valves_langs;
	foreach ($valves_langs as $lang) {
		$label = 'descarga1_'.$lang;
		if (isset($_POST[$label])) update_post_meta( $post_id, $label, $_POST[$label]);
	}
	foreach ($valves_langs as $lang) {
		$label = 'descarga2_'.$lang;
		if (isset($_POST[$label])) update_post_meta( $post_id, $label, $_POST[$label]);
	}
	foreach ($valves_langs as $lang) {
		$label = 'descarga3_'.$lang;
		if (isset($_POST[$label])) update_post_meta( $post_id, $label, $_POST[$label]);
	}
}
add_action( 'save_post', 'wp_valve_save_descarga' );

//Códigos cortos --------------------------------------------------
function valvulas_featured_products($params = array(), $content = null) {
	$html = "<article class='container'>
	<div class='row'>
	<div class='col-md-10 col-md-offset-1'>
	<div class='row productos-inicio'>";
	$args = array(
		'post_type' => 'valve',
		'posts_per_page' => 4,
		'post_status' => 'publish',
		'order' => 'ASC',
		'orderby' => 'name',
		'meta_query' => array(
	                array(
	                    'key' => 'featured',
	                    'value' => '1',
	                    'compare' => '=='
	                ),
		),
	);
	$the_query = new WP_Query( $args);
	while ( $the_query->have_posts() ) { $the_query->the_post();

                  $html .= "<div class='col-md-3 col-sm-6'>\n";
                  $html .= "<a href='".get_the_permalink()."'>\n";
                  $html .= "<div class='img-product-ini'>".get_the_post_thumbnail()."</div>\n";
                  $html .= apply_filters("the_content", get_post_meta( get_the_id(), 'featuredtext', true ));
                  $html .= "</a>\n";
                  $html .= "</div>\n";
	}
	wp_reset_query();
	$html .= "</div><div class='row'>
	<div class='col-md-12 vertodas'>
	<a href='".get_the_permalink(CATALOG_ID)."' class='hvr-sweep-to-bottom'>".__("Ver todas", "valvulas")."</a>
	</div>
	</div>
	</div>
	</div>
	</article>";
	return $html; 
}
add_shortcode('destacados', 'valvulas_featured_products');


?>
