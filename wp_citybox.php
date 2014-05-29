<?php
/**
 * Plugin Name: WP CityBox
 * Plugin URI:
 * Description: Post City Custom field.
 * Version: 1.0
 * Author: Mohamed KEITA
 * Author URI: 
 * License: Free
 */

/* Calls the class on the post edit screen. */
function cityBox() {
    new mycityBox();
}
//add the meta box to our post screen would look like this:
if ( is_admin() ) {
    add_action( 'load-post.php', 'cityBox' );
    add_action( 'load-post-new.php', 'cityBox' );
}

class mycityBox {

	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'addMetabox' ) );
		add_action( 'save_post', array( $this, 'saveMetabox' ) );
	}

	public function addMetabox( $post_type ) {
            $post_types = array('post', 'page');
            if ( in_array( $post_type, $post_types )) {
				add_meta_box(
					'meta_citybox'
					,__( 'Votre ville')
					,array( $this, 'MetaCitybox' )
					,$post_type
					,'advanced'
					,'high'
				);
            }
	}

	/* Rendering the Meta Box */
	public function MetaCitybox( $post ) {
		$city = get_post_meta( $post->ID, '_city_box', true );
		echo '<input type="text" id="city_box" name="city_box" value="' . esc_attr( $city ) . '" />';
		// echo '<label for="city_box">Selectionnez votre ville : </label>';
		// echo '<select name="city_box">';
		// echo '<option ' . selected( 'paris', $value, false ) . ' value="paris">Paris</option>';
		// echo '<option ' . selected( 'marseille', $value, false ) . ' value="marseille">Marseille</option>';
		// echo '<option ' . selected( 'lille', $value, false ) . ' value="lille">Lille</option>';
		// echo '</select>';

	}

	/* Saving the meta data. */
	public function saveMetabox($post_id){
		// If this is an autosave, our form has not been submitted,
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return $post_id;

		// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) )
				return $post_id;
		
		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) )
				return $post_id;
		}
		// Update the meta field.
		if(isset($_POST['city_box']))
		  update_post_meta($post_id, '_city_box', $_POST['city_box']);

	}
}
// shortcode hook : get city value
add_shortcode('city', 'cityShortcode');
function cityShortcode($atts, $content = null) {
	global $post;
	$city = get_post_meta( $post->ID, '_city_box', true );
	return $city;
}
?>

