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

//add the meta box to our post screen would look like this:
add_action('add_meta_boxes','init_metabox');
function init_metabox(){
  add_meta_box('Metacitybox', 'Your city', 'MetaCitybox', 'post', 'side');
}

/* Rendering the Meta Box */
function MetaCitybox($post){
  $city = get_post_meta($post->ID,'_city_box',true);
  echo '<label for="city_box">City name:</label>';
  echo '<input id="city_box" type="text" name="city_box" value="'.$city.'" />';
}

/* Saving the meta data. */
add_action('save_post','save_metabox');
function save_metabox($post_id){
		//If this is an autosave, our form has not been submitted,
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
if(isset($_POST['city_box']))
  update_post_meta($post_id, '_city_box', esc_html($_POST['city_box']));
}

// shortcode hook : get city value
add_shortcode('city', 'cityShortcode');
function cityShortcode($atts, $content = null) {
	global $post;
	$city = get_post_meta( $post->ID, '_city_box', true );
	return $city;
}

// -- search_filter --
function search_filter($query) {
  if ( !is_admin() && $query->is_search() ) {
  $s = get_search_query();
  global $wpdb;
  $querystr = "
        SELECT $wpdb->posts.*
        FROM $wpdb->posts INNER JOIN $wpdb->postmeta 
        ON ($wpdb->posts.ID = $wpdb->postmeta.post_id) 
        WHERE 1=1 AND ((($wpdb->posts.post_title LIKE '%$s%') 
        OR ($wpdb->posts.post_content LIKE '%$s%'))) 
        AND $wpdb->posts.post_type = 'post' 
        OR ($wpdb->posts.post_status = 'publish') 
        AND ( ($wpdb->postmeta.meta_key = '_city_box' 
        AND CAST($wpdb->postmeta.meta_value AS CHAR) 
        LIKE '%$s%') ) GROUP BY $wpdb->posts.ID 
        ORDER BY $wpdb->posts.post_title 
        LIKE '%$s%' DESC, 
        $wpdb->posts.post_date 
        DESC";
      // echo $querystr;
      $pageposts = $wpdb->get_results($querystr, OBJECT);
      var_dump($pageposts);
      // $query->set('post_type', $pageposts[0]->post_type);

    }
}
add_action('pre_get_posts','search_filter');


?>

