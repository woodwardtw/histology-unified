<?php

// Add Translation Option
load_theme_textdomain( 'wpbootstrap', TEMPLATEPATH.'/languages' );
$locale = get_locale();
$locale_file = TEMPLATEPATH . "/languages/$locale.php";
if ( is_readable( $locale_file ) ) require_once( $locale_file );

// Clean up the WordPress Head
if( !function_exists( "wp_bootstrap_head_cleanup" ) ) {  
  function wp_bootstrap_head_cleanup() {
    // remove header links
    remove_action( 'wp_head', 'feed_links_extra', 3 );                    // Category Feeds
    remove_action( 'wp_head', 'feed_links', 2 );                          // Post and Comment Feeds
    remove_action( 'wp_head', 'rsd_link' );                               // EditURI link
    remove_action( 'wp_head', 'wlwmanifest_link' );                       // Windows Live Writer
    remove_action( 'wp_head', 'index_rel_link' );                         // index link
    remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );            // previous link
    remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );             // start link
    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 ); // Links for Adjacent Posts
    remove_action( 'wp_head', 'wp_generator' );                           // WP version
  }
}
// Launch operation cleanup
add_action( 'init', 'wp_bootstrap_head_cleanup' );

// remove WP version from RSS
if( !function_exists( "wp_bootstrap_rss_version" ) ) {  
  function wp_bootstrap_rss_version() { return ''; }
}
add_filter( 'the_generator', 'wp_bootstrap_rss_version' );

// Remove the […] in a Read More link
if( !function_exists( "wp_bootstrap_excerpt_more" ) ) {  
  function wp_bootstrap_excerpt_more( $more ) {
    global $post;
    return '...  <a href="'. get_permalink($post->ID) . '" class="more-link" title="Read '.get_the_title($post->ID).'">Read more &raquo;</a>';
  }
}
add_filter('excerpt_more', 'wp_bootstrap_excerpt_more');

// Add WP 3+ Functions & Theme Support
if( !function_exists( "wp_bootstrap_theme_support" ) ) {  
  function wp_bootstrap_theme_support() {
    add_theme_support( 'post-thumbnails' );      // wp thumbnails (sizes handled in functions.php)
    set_post_thumbnail_size( 125, 125, true );   // default thumb size
    add_theme_support( 'custom-background' );  // wp custom background
    add_theme_support( 'automatic-feed-links' ); // rss

    // Add post format support - if these are not needed, comment them out
    add_theme_support( 'post-formats',      // post formats
      array( 
        'aside',   // title less blurb
        'gallery', // gallery of images
        'link',    // quick link to other site
        'image',   // an image
        'quote',   // a quick quote
        'status',  // a Facebook like status update
        'video',   // video 
        'audio',   // audio
        'chat'     // chat transcript 
      )
    );  

    add_theme_support( 'menus' );            // wp menus
    
    register_nav_menus(                      // wp3+ menus
      array( 
        'main_nav' => 'The Main Menu',   // main nav in header
        'footer_links' => 'Footer Links' // secondary nav in footer
      )
    );  
  }
}
// launching this stuff after theme setup
add_action( 'after_setup_theme','wp_bootstrap_theme_support' );

function wp_bootstrap_main_nav() {
  // Display the WordPress menu if available
  wp_nav_menu( 
    array( 
      'menu' => 'main_nav', /* menu name */
      'menu_class' => 'nav navbar-nav',
      'theme_location' => 'main_nav', /* where in the theme it's assigned */
      'container' => 'false', /* container class */
      'fallback_cb' => 'wp_bootstrap_main_nav_fallback', /* menu fallback */
      'walker' => new Bootstrap_walker()
    )
  );
}

function wp_bootstrap_footer_links() { 
  // Display the WordPress menu if available
  wp_nav_menu(
    array(
      'menu' => 'footer_links', /* menu name */
      'theme_location' => 'footer_links', /* where in the theme it's assigned */
      'container_class' => 'footer-links clearfix', /* container class */
      'fallback_cb' => 'wp_bootstrap_footer_links_fallback' /* menu fallback */
    )
  );
}

// this is the fallback for header menu
function wp_bootstrap_main_nav_fallback() { 
  /* you can put a default here if you like */ 
}

// this is the fallback for footer menu
function wp_bootstrap_footer_links_fallback() { 
  /* you can put a default here if you like */ 
}

// Shortcodes
require_once('library/shortcodes.php');

// Admin Functions (commented out by default)
// require_once('library/admin.php');         // custom admin functions

// Custom Backend Footer
add_filter('admin_footer_text', 'wp_bootstrap_custom_admin_footer');
function wp_bootstrap_custom_admin_footer() {
	echo '<span id="footer-thankyou">Developed by <a href="http://320press.com" target="_blank">320press</a></span>. Built using <a href="http://themble.com/bones" target="_blank">Bones</a>.';
}

// adding it to the admin area
add_filter('admin_footer_text', 'wp_bootstrap_custom_admin_footer');

// Set content width
if ( ! isset( $content_width ) ) $content_width = 580;

/************* THUMBNAIL SIZE OPTIONS *************/

// Thumbnail sizes
add_image_size( 'wpbs-featured', 780, 300, true );
add_image_size( 'wpbs-featured-home', 970, 311, true);
add_image_size( 'wpbs-featured-carousel', 970, 400, true);

/* 
to add more sizes, simply copy a line from above 
and change the dimensions & name. As long as you
upload a "featured image" as large as the biggest
set width or height, all the other sizes will be
auto-cropped.

To call a different size, simply change the text
inside the thumbnail function.

For example, to call the 300 x 300 sized image, 
we would use the function:
<?php the_post_thumbnail( 'bones-thumb-300' ); ?>
for the 600 x 100 image:
<?php the_post_thumbnail( 'bones-thumb-600' ); ?>

You can change the names and dimensions to whatever
you like. Enjoy!
*/

/************* ACTIVE SIDEBARS ********************/

// Sidebars & Widgetizes Areas
function wp_bootstrap_register_sidebars() {
  register_sidebar(array(
  	'id' => 'sidebar1',
  	'name' => 'Main Sidebar',
  	'description' => 'Used on every page BUT the homepage page template.',
  	'before_widget' => '<div id="%1$s" class="widget %2$s">',
  	'after_widget' => '</div>',
  	'before_title' => '<h4 class="widgettitle">',
  	'after_title' => '</h4>',
  ));
    
  register_sidebar(array(
  	'id' => 'sidebar2',
  	'name' => 'Homepage Sidebar',
  	'description' => 'Used only on the homepage page template.',
  	'before_widget' => '<div id="%1$s" class="widget %2$s">',
  	'after_widget' => '</div>',
  	'before_title' => '<h4 class="widgettitle">',
  	'after_title' => '</h4>',
  ));
    
  register_sidebar(array(
    'id' => 'footer1',
    'name' => 'Footer 1',
    'before_widget' => '<div id="%1$s" class="widget col-sm-4 %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h4 class="widgettitle">',
    'after_title' => '</h4>',
  ));

  register_sidebar(array(
    'id' => 'footer2',
    'name' => 'Footer 2',
    'before_widget' => '<div id="%1$s" class="widget col-sm-4 %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h4 class="widgettitle">',
    'after_title' => '</h4>',
  ));

  register_sidebar(array(
    'id' => 'footer3',
    'name' => 'Footer 3',
    'before_widget' => '<div id="%1$s" class="widget col-sm-4 %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h4 class="widgettitle">',
    'after_title' => '</h4>',
  ));
    
    
  /* 
  to add more sidebars or widgetized areas, just copy
  and edit the above sidebar code. In order to call 
  your new sidebar just use the following code:
  
  Just change the name to whatever your new
  sidebar's id is, for example:
  
  To call the sidebar in your template, you can just copy
  the sidebar.php file and rename it to your sidebar's name.
  So using the above example, it would be:
  sidebar-sidebar2.php
  
  */
} // don't remove this bracket!
add_action( 'widgets_init', 'wp_bootstrap_register_sidebars' );

/************* COMMENT LAYOUT *********************/
		
// Comment Layout
function wp_bootstrap_comments($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?>>
		<article id="comment-<?php comment_ID(); ?>" class="clearfix">
			<div class="comment-author vcard clearfix">
				<div class="avatar col-sm-3">
					<?php echo get_avatar( $comment, $size='75' ); ?>
				</div>
				<div class="col-sm-9 comment-text">
					<?php printf('<h4>%s</h4>', get_comment_author_link()) ?>
					<?php edit_comment_link(__('Edit','wpbootstrap'),'<span class="edit-comment btn btn-sm btn-info"><i class="glyphicon-white glyphicon-pencil"></i>','</span>') ?>
                    
                    <?php if ($comment->comment_approved == '0') : ?>
       					<div class="alert-message success">
          				<p><?php _e('Your comment is awaiting moderation.','wpbootstrap') ?></p>
          				</div>
					<?php endif; ?>
                    
                    <?php comment_text() ?>
                    
                    <time datetime="<?php echo comment_time('Y-m-j'); ?>"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time('F jS, Y'); ?> </a></time>
                    
					<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
                </div>
			</div>
		</article>
    <!-- </li> is added by wordpress automatically -->
<?php
} // don't remove this bracket!

// Display trackbacks/pings callback function
function list_pings($comment, $args, $depth) {
       $GLOBALS['comment'] = $comment;
?>
        <li id="comment-<?php comment_ID(); ?>"><i class="icon icon-share-alt"></i>&nbsp;<?php comment_author_link(); ?>
<?php 

}

/************* SEARCH FORM LAYOUT *****************/

/****************** password protected post form *****/

add_filter( 'the_password_form', 'wp_bootstrap_custom_password_form' );

function wp_bootstrap_custom_password_form() {
	global $post;
	$label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
	$o = '<div class="clearfix"><form class="protected-post-form" action="' . get_option('siteurl') . '/wp-login.php?action=postpass" method="post">
	' . '<p>' . __( "This post is password protected. To view it please enter your password below:" ,'wpbootstrap') . '</p>' . '
	<label for="' . $label . '">' . __( "Password:" ,'wpbootstrap') . ' </label><div class="input-append"><input name="post_password" id="' . $label . '" type="password" size="20" /><input type="submit" name="Submit" class="btn btn-primary" value="' . esc_attr__( "Submit",'wpbootstrap' ) . '" /></div>
	</form></div>
	';
	return $o;
}

/*********** update standard wp tag cloud widget so it looks better ************/

add_filter( 'widget_tag_cloud_args', 'wp_bootstrap_my_widget_tag_cloud_args' );

function wp_bootstrap_my_widget_tag_cloud_args( $args ) {
	$args['number'] = 20; // show less tags
	$args['largest'] = 9.75; // make largest and smallest the same - i don't like the varying font-size look
	$args['smallest'] = 9.75;
	$args['unit'] = 'px';
	return $args;
}

// filter tag clould output so that it can be styled by CSS
function wp_bootstrap_add_tag_class( $taglinks ) {
	if (!is_array($taglinks) ) {
    $tags = explode('</a>', $taglinks);
    $regex = "#(.*tag-link[-])(.*)(' title.*)#e";

    foreach( $tags as $tag ) {
    	$tagn[] = preg_replace($regex, "('$1$2 label tag-'.get_tag($2)->slug.'$3')", $tag );
    }

    $taglinks = implode('</a>', $tagn);

    return $taglinks;
    }
}

add_action( 'wp_tag_cloud', 'wp_bootstrap_add_tag_class' );

add_filter( 'wp_tag_cloud','wp_bootstrap_wp_tag_cloud_filter', 10, 2) ;

function wp_bootstrap_wp_tag_cloud_filter( $return, $args )
{
  return '<div id="tag-cloud">' . $return . '</div>';
}

// Enable shortcodes in widgets
add_filter( 'widget_text', 'do_shortcode' );

// Disable jump in 'read more' link
function wp_bootstrap_remove_more_jump_link( $link ) {
	$offset = strpos($link, '#more-');
	if ( $offset ) {
		$end = strpos( $link, '"',$offset );
	}
	if ( $end ) {
		$link = substr_replace( $link, '', $offset, $end-$offset );
	}
	return $link;
}
add_filter( 'the_content_more_link', 'wp_bootstrap_remove_more_jump_link' );

// ---- removing the removers, this strips out [caption] tags 
// Remove height/width attributes on images so they can be responsive
// add_filter( 'post_thumbnail_html', 'wp_bootstrap_remove_thumbnail_dimensions', 10 );
// add_filter( 'image_send_to_editor', 'wp_bootstrap_remove_thumbnail_dimensions', 10 );

function wp_bootstrap_remove_thumbnail_dimensions( $html ) {
    $html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
    return $html;
}

// Add the Meta Box to the homepage template
function wp_bootstrap_add_homepage_meta_box() {  
	global $post;

	// Only add homepage meta box if template being used is the homepage template
	// $post_id = isset($_GET['post']) ? $_GET['post'] : (isset($_POST['post_ID']) ? $_POST['post_ID'] : "");
	$post_id = $post->ID;
	$template_file = get_post_meta($post_id,'_wp_page_template',TRUE);

	if ( $template_file == 'page-homepage.php' ){
	    add_meta_box(  
	        'homepage_meta_box', // $id  
	        'Optional Homepage Tagline', // $title  
	        'wp_bootstrap_show_homepage_meta_box', // $callback  
	        'page', // $page  
	        'normal', // $context  
	        'high'); // $priority  
    }
}

add_action( 'add_meta_boxes', 'wp_bootstrap_add_homepage_meta_box' );

// Field Array  
$prefix = 'custom_';  
$custom_meta_fields = array(  
    array(  
        'label'=> 'Homepage tagline area',  
        'desc'  => 'Displayed underneath page title. Only used on homepage template. HTML can be used.',  
        'id'    => $prefix.'tagline',  
        'type'  => 'textarea' 
    )  
);  

// The Homepage Meta Box Callback  
function wp_bootstrap_show_homepage_meta_box() {  
  global $custom_meta_fields, $post;

  // Use nonce for verification
  wp_nonce_field( basename( __FILE__ ), 'wpbs_nonce' );
    
  // Begin the field table and loop
  echo '<table class="form-table">';

  foreach ( $custom_meta_fields as $field ) {
      // get value of this field if it exists for this post  
      $meta = get_post_meta($post->ID, $field['id'], true);  
      // begin a table row with  
      echo '<tr> 
              <th><label for="'.$field['id'].'">'.$field['label'].'</label></th> 
              <td>';  
              switch($field['type']) {  
                  // text  
                  case 'text':  
                      echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="60" /> 
                          <br /><span class="description">'.$field['desc'].'</span>';  
                  break;
                  
                  // textarea  
                  case 'textarea':  
                      echo '<textarea name="'.$field['id'].'" id="'.$field['id'].'" cols="80" rows="4">'.$meta.'</textarea> 
                          <br /><span class="description">'.$field['desc'].'</span>';  
                  break;  
              } //end switch  
      echo '</td></tr>';  
  } // end foreach  
  echo '</table>'; // end table  
}  

// Save the Data  
function wp_bootstrap_save_homepage_meta( $post_id ) {  

    global $custom_meta_fields;  
  
    // verify nonce  
    if ( !isset( $_POST['wpbs_nonce'] ) || !wp_verify_nonce($_POST['wpbs_nonce'], basename(__FILE__)) )  
        return $post_id;

    // check autosave
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
        return $post_id;

    // check permissions
    if ( 'page' == $_POST['post_type'] ) {
        if ( !current_user_can( 'edit_page', $post_id ) )
            return $post_id;
        } elseif ( !current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
    }
  
    // loop through fields and save the data  
    foreach ( $custom_meta_fields as $field ) {
        $old = get_post_meta( $post_id, $field['id'], true );
        $new = $_POST[$field['id']];

        if ($new && $new != $old) {
            update_post_meta( $post_id, $field['id'], $new );
        } elseif ( '' == $new && $old ) {
            delete_post_meta( $post_id, $field['id'], $old );
        }
    } // end foreach
}
add_action( 'save_post', 'wp_bootstrap_save_homepage_meta' );

// Add thumbnail class to thumbnail links
function wp_bootstrap_add_class_attachment_link( $html ) {
    $postid = get_the_ID();
    $html = str_replace( '<a','<a class="thumbnail"',$html );
    return $html;
}
add_filter( 'wp_get_attachment_link', 'wp_bootstrap_add_class_attachment_link', 10, 1 );

// Add lead class to first paragraph
function wp_bootstrap_first_paragraph( $content ){
    global $post;

    // if we're on the homepage, don't add the lead class to the first paragraph of text
    if( is_page_template( 'page-homepage.php' ) )
        return $content;
    else
        return preg_replace('/<p([^>]+)?>/', '<p$1 class="lead">', $content, 1);
}
add_filter( 'the_content', 'wp_bootstrap_first_paragraph' );

// Menu output mods
class Bootstrap_walker extends Walker_Nav_Menu{

  function start_el(&$output, $object, $depth = 0, $args = Array(), $current_object_id = 0){

	 global $wp_query;
	 $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
	
	 $class_names = $value = '';
	
		// If the item has children, add the dropdown class for bootstrap
		if ( $args->has_children ) {
			$class_names = "dropdown ";
		}
	
		$classes = empty( $object->classes ) ? array() : (array) $object->classes;
		
		$class_names .= join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $object ) );
		$class_names = ' class="'. esc_attr( $class_names ) . '"';
       
   	$output .= $indent . '<li id="menu-item-'. $object->ID . '"' . $value . $class_names .'>';

   	$attributes  = ! empty( $object->attr_title ) ? ' title="'  . esc_attr( $object->attr_title ) .'"' : '';
   	$attributes .= ! empty( $object->target )     ? ' target="' . esc_attr( $object->target     ) .'"' : '';
   	$attributes .= ! empty( $object->xfn )        ? ' rel="'    . esc_attr( $object->xfn        ) .'"' : '';
   	$attributes .= ! empty( $object->url )        ? ' href="'   . esc_attr( $object->url        ) .'"' : '';

   	// if the item has children add these two attributes to the anchor tag
   	if ( $args->has_children ) {
		  $attributes .= ' class="dropdown-toggle" data-toggle="dropdown"';
    }

    $item_output = $args->before;
    $item_output .= '<a'. $attributes .'>';
    $item_output .= $args->link_before .apply_filters( 'the_title', $object->title, $object->ID );
    $item_output .= $args->link_after;

    // if the item has children add the caret just before closing the anchor tag
    if ( $args->has_children ) {
    	$item_output .= '<b class="caret"></b></a>';
    }
    else {
    	$item_output .= '</a>';
    }

    $item_output .= $args->after;

    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $object, $depth, $args );
  } // end start_el function
        
  function start_lvl(&$output, $depth = 0, $args = Array()) {
    $indent = str_repeat("\t", $depth);
    $output .= "\n$indent<ul class=\"dropdown-menu\">\n";
  }
      
	function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ){
    $id_field = $this->db_fields['id'];
    if ( is_object( $args[0] ) ) {
        $args[0]->has_children = ! empty( $children_elements[$element->$id_field] );
    }
    return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
  }        
}

add_editor_style('editor-style.css');

function wp_bootstrap_add_active_class($classes, $item) {
	if( $item->menu_item_parent == 0 && in_array('current-menu-item', $classes) ) {
    $classes[] = "active";
	}
  
  return $classes;
}

// Add Twitter Bootstrap's standard 'active' class name to the active nav link item
add_filter('nav_menu_css_class', 'wp_bootstrap_add_active_class', 10, 2 );

// enqueue styles
if( !function_exists("wp_bootstrap_theme_styles") ) {  
    function wp_bootstrap_theme_styles() { 
        // This is the compiled css file from LESS - this means you compile the LESS file locally and put it in the appropriate directory if you want to make any changes to the master bootstrap.css.
        wp_register_style( 'wpbs', get_template_directory_uri() . '/library/dist/css/styles.f6413c85.min.css', array(), '1.0', 'all' );
        wp_enqueue_style( 'wpbs' );

        // For child themes
        wp_register_style( 'wpbs-style', get_stylesheet_directory_uri() . '/style.css', array(), '1.0', 'all' );
        wp_enqueue_style( 'wpbs-style' );
    }
}
add_action( 'wp_enqueue_scripts', 'wp_bootstrap_theme_styles' );

// enqueue javascript
if( !function_exists( "wp_bootstrap_theme_js" ) ) {  
  function wp_bootstrap_theme_js(){

    if ( !is_admin() ){
      if ( is_singular() AND comments_open() AND ( get_option( 'thread_comments' ) == 1) ) 
        wp_enqueue_script( 'comment-reply' );
    }

    // This is the full Bootstrap js distribution file. If you only use a few components that require the js files consider loading them individually instead
    wp_register_script( 'bootstrap', 
      get_template_directory_uri() . '/bower_components/bootstrap/dist/js/bootstrap.js', 
      array('jquery'), 
      '1.2' );

    wp_register_script( 'wpbs-js', 
      get_template_directory_uri() . '/library/dist/js/scripts.d1e3d952.min.js',
      array('bootstrap'), 
      '1.2' );
  
    wp_register_script( 'modernizr', 
      get_template_directory_uri() . '/bower_components/modernizer/modernizr.js', 
      array('jquery'), 
      '1.2' );
  
    wp_enqueue_script( 'bootstrap' );
    wp_enqueue_script( 'wpbs-js' );
    wp_enqueue_script( 'modernizr' );
    
  }
}
add_action( 'wp_enqueue_scripts', 'wp_bootstrap_theme_js' );

// Get <head> <title> to behave like other themes
function wp_bootstrap_wp_title( $title, $sep ) {
  global $paged, $page;

  if ( is_feed() ) {
    return $title;
  }

  // Add the site name.
  $title .= get_bloginfo( 'name' );

  // Add the site description for the home/front page.
  $site_description = get_bloginfo( 'description', 'display' );
  if ( $site_description && ( is_home() || is_front_page() ) ) {
    $title = "$title $sep $site_description";
  }

  // Add a page number if necessary.
  if ( $paged >= 2 || $page >= 2 ) {
    $title = "$title $sep " . sprintf( __( 'Page %s', 'wpbootstrap' ), max( $paged, $page ) );
  }

  return $title;
}
add_filter( 'wp_title', 'wp_bootstrap_wp_title', 10, 2 );

// Related Posts Function (call using wp_bootstrap_related_posts(); )
function wp_bootstrap_related_posts() {
  echo '<ul id="bones-related-posts">';
  global $post;
  $tags = wp_get_post_tags($post->ID);
  if($tags) {
    foreach($tags as $tag) { $tag_arr .= $tag->slug . ','; }
        $args = array(
          'tag' => $tag_arr,
          'numberposts' => 5, /* you can change this to show more */
          'post__not_in' => array($post->ID)
      );
        $related_posts = get_posts($args);
        if($related_posts) {
          foreach ($related_posts as $post) : setup_postdata($post); ?>
              <li class="related_post"><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
          <?php endforeach; } 
      else { ?>
            <li class="no_related_post">No Related Posts Yet!</li>
    <?php }
  }
  wp_reset_query();
  echo '</ul>';
}

// Numeric Page Navi (built into the theme by default)
function wp_bootstrap_page_navi($before = '', $after = '') {
  global $wpdb, $wp_query;
  $request = $wp_query->request;
  $posts_per_page = intval(get_query_var('posts_per_page'));
  $paged = intval(get_query_var('paged'));
  $numposts = $wp_query->found_posts;
  $max_page = $wp_query->max_num_pages;
  if ( $numposts <= $posts_per_page ) { return; }
  if(empty($paged) || $paged == 0) {
    $paged = 1;
  }
  $pages_to_show = 7;
  $pages_to_show_minus_1 = $pages_to_show-1;
  $half_page_start = floor($pages_to_show_minus_1/2);
  $half_page_end = ceil($pages_to_show_minus_1/2);
  $start_page = $paged - $half_page_start;
  if($start_page <= 0) {
    $start_page = 1;
  }
  $end_page = $paged + $half_page_end;
  if(($end_page - $start_page) != $pages_to_show_minus_1) {
    $end_page = $start_page + $pages_to_show_minus_1;
  }
  if($end_page > $max_page) {
    $start_page = $max_page - $pages_to_show_minus_1;
    $end_page = $max_page;
  }
  if($start_page <= 0) {
    $start_page = 1;
  }
    
  echo $before.'<ul class="pagination">'."";
  if ($paged > 1) {
    $first_page_text = "&laquo";
    echo '<li class="prev"><a href="'.get_pagenum_link().'" title="' . __('First','wpbootstrap') . '">'.$first_page_text.'</a></li>';
  }
    
  $prevposts = get_previous_posts_link( __('&larr; Previous','wpbootstrap') );
  if($prevposts) { echo '<li>' . $prevposts  . '</li>'; }
  else { echo '<li class="disabled"><a href="#">' . __('&larr; Previous','wpbootstrap') . '</a></li>'; }
  
  for($i = $start_page; $i  <= $end_page; $i++) {
    if($i == $paged) {
      echo '<li class="active"><a href="#">'.$i.'</a></li>';
    } else {
      echo '<li><a href="'.get_pagenum_link($i).'">'.$i.'</a></li>';
    }
  }
  echo '<li class="">';
  next_posts_link( __('Next &rarr;','wpbootstrap') );
  echo '</li>';
  if ($end_page < $max_page) {
    $last_page_text = "&raquo;";
    echo '<li class="next"><a href="'.get_pagenum_link($max_page).'" title="' . __('Last','wpbootstrap') . '">'.$last_page_text.'</a></li>';
  }
  echo '</ul>'.$after."";
}

// Remove <p> tags from around images
function wp_bootstrap_filter_ptags_on_images( $content ){
  return preg_replace( '/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content );
}
add_filter( 'the_content', 'wp_bootstrap_filter_ptags_on_images' );


//CUSTOM STUFF

function theme_enqueue_styles() {

    $parent_style = 'parent-style';

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css', array(), filemtime( get_stylesheet_directory() . '/style.css' ) );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style )
    );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

add_filter('widget_text', 'do_shortcode');

//function to call first uploaded image in functions file
function main_image() {
    $files = get_children('post_parent='.get_the_ID().'&post_type=attachment
    &post_mime_type=image&order=desc');
      if($files) :
        $keys = array_reverse(array_keys($files));
        $j=0;
        $num = $keys[$j];
        $image=wp_get_attachment_image($num, 'large', true);
        $imagepieces = explode('"', $image);
        $imagepath = $imagepieces[1];
        $main=wp_get_attachment_url($num);
            $template=get_template_directory();
            $the_title=get_the_title();
        print "<img src='$main' alt='$the_title' class='frame' />";
      endif;
}

if(!function_exists('load_my_script')){
    function load_my_script() {
         if(!is_page_template('page-chaos-menu.php')) {
            $deps = array('jquery');
            $version= '2.0';
            $in_footer = true;
            wp_enqueue_script('menu-js', get_stylesheet_directory_uri() . '/js/menu.js',$deps, $version, $in_footer);        
            $histology_directory = array( 'data_directory' => get_stylesheet_directory_uri() );
            wp_localize_script( 'menu-js', 'histology_directory', $histology_directory );
        }
    }
}

add_action('wp_enqueue_scripts', 'load_my_script');


if(!function_exists('hist_script')){
    function hist_script() {
         if(!is_page_template('page-chaos-menu.php')) {
     
            $version= '1.2';
            $in_footer = true;
            wp_enqueue_script('histology-script', get_stylesheet_directory_uri() . '/js/extras.js', array('jquery', 'menu-js'), $version, $in_footer);
        }
    }
}

add_action('wp_enqueue_scripts', 'hist_script');


//enqueue testing randomizer menu
//yeah, lots of duplication but it's done
function chaos_menu_enqueue(){
     if(is_page_template('page-chaos-menu.php')) {
                $version = '2.6';
                $in_footer = true;
                wp_enqueue_script('histology-chaos', get_stylesheet_directory_uri() . '/js/chaos_menu.js', array('jquery'), $version, $in_footer);
                $histology_directory = array( 'data_directory' => get_stylesheet_directory_uri() );
            wp_localize_script( 'histology-chaos', 'histology_directory', $histology_directory );
            } 
}
add_action('wp_enqueue_scripts', 'chaos_menu_enqueue');

//enqueue sorter
function sorter_enqueue(){
     if(is_page_template('page-sorter.php')) {
                $version = '1';
                $in_footer = true;
                wp_enqueue_script('jquery-ui', 'https://code.jquery.com/ui/1.13.2/jquery-ui.js', array('jquery'), $version, $in_footer);
                wp_enqueue_script('sorter', get_stylesheet_directory_uri() . '/js/sort.js', array('jquery-ui'), $version, $in_footer);
                wp_localize_script('sorter', 'sorterObj', array(
                  'ajaxUrl' => admin_url('admin-ajax.php'),
                  'nonce' => wp_create_nonce('sorterObj_ajax_nonce'),
                ) );
            } 
}
add_action('wp_enqueue_scripts', 'sorter_enqueue');



// Load Font Awesome
add_action( 'wp_enqueue_scripts', 'enqueue_font_awesome' );
function enqueue_font_awesome() {

    wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css' );

}

// Breadcrumbs via https://www.thewebtaylor.com/articles/wordpress-creating-breadcrumbs-without-a-plugin

function custom_breadcrumbs(){
    global $post;
    if( $post->post_parent ){

                // If child page, get parents
                $anc = get_post_ancestors( $post->ID );

                // Get parents in the right order
                $anc = array_reverse($anc);

                // Parent page loop
                if ( !isset( $parents ) ) $parents = null;
                $parents .= '<span class="item-parent"><a class="bread-parent" href="' . get_site_url() . '">Main Menu</a> &#187; </span> ';
                foreach ( $anc as $ancestor ) {
                    $parents .= '<span class="item-parent item-parent-' . $ancestor . '"><a class="bread-parent bread-parent-' . $ancestor . '" href="' . site_url() .'?menu=menu_' . $ancestor . '" title="' . get_the_title($ancestor) . '">' . get_the_title($ancestor) . '</a></span>';
                    $parents .= '<span class="separator separator-' . $ancestor . '"> &#187; </span>';
                }

                // Display parent pages
                echo $parents;

                // Current page
                echo '<span class="item-current item-' . $post->ID . '"><strong title="' . get_the_title() . '"> ' . get_the_title() . '</strong></span>';
               
            } else {

                // Just display current page if not parents
                echo '<span class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '"> ' . get_the_title() . '</strong></span>';

            }
    }

    //background image setter
function get_post_background_img ($post) {
  if ( $thumbnail_id = get_post_thumbnail_id($post) ) {
        if ( $image_src = wp_get_attachment_image_src( $thumbnail_id, 'full-size' ) )
            printf( ' style="background-image: url(%s);"', $image_src[0] );
    }
}


//next and prev pagination for pages from https://codex.wordpress.org/Next_and_Previous_Links#The_Next_and_Previous_Pages

//gets the ones that have kids out of the mix
function has_kids($pageID) {
    $args = array(
    'post_parent' => $pageID,
    'post_type'   => 'page',
    'numberposts' => 1,
    'post_status' => 'publish',
    'orderby' => 'date_published',
);
    $children = get_children( $args );
    return sizeof($children);
}


//THIS IS THE NEXT SLIDE FUNCTION AT THE BOTTOM OF THE PAGE
function getPrevNext(){
    $post_id = get_the_ID();
    $ancestor_id = get_ancestors($post_id,'page', 'post_type')[0];
    $pagelist = get_pages( array(
     'parent'=> $ancestor_id,
     'sort_order' => 'asc',
     'sort_column'  => 'post_date' //sorting by date created as a way to avoid 01 necessity
      ) );

        $pages = array();
        //remove kids
        foreach ($pagelist as $page) {
            if(has_kids($page->ID)===0){
                    $pages[] += $page->ID;
                }
            }
        $current = array_search(get_the_ID(), $pages);

        $page_num =sizeof($pages);
        if($current >= 1){
            $prevID = $pages[$current-1];
            }
        if ($current+1 < $page_num){
            $nextID = $pages[$current+1];    
        }    

        echo '<div class="navigation col-md-9" id="hist-nav" data-pages="'.$page_num.'">';
        //PREVIOUS
        if(empty($prevID)){
            echo '<div class="col-md-5 nav-arrow-empty" id="nav-arrow-left"></div>';
        } else {
            echo '<a id="previous-link" href="';
                if (!empty($prevID)){
                    echo get_permalink($prevID);
                }
            echo '" ';
            echo 'title="';
                if (!empty($prevID)){
                    echo get_the_title($prevID);
                }
            echo'"><div class="col-md-5 nav-arrow" id="nav-arrow-left"><img src="'.get_stylesheet_directory_uri().'/imgs/arrow_left_ai.svg" alt="Left arrow."> PREVIOUS';
            echo '</div>';
            echo '</a>';
        }
       
       //NEXT

        echo '<div class="total-pages col-md-2">'.($current+1) . ' of ' . $page_num . '</div>';
       
        if(empty($nextID)){
            echo '<div class="col-md-5 nav-arrow-empty" id="nav-arrow-right"></div>';
        } else {
            

            echo '<a id="next-link" href="';
            if (!empty($nextID)){
               echo get_permalink($nextID);
            }
            echo '"';
            echo 'title="';
            echo get_the_title($nextID);
            echo'"><div class="col-md-5 nav-arrow" id="nav-arrow-right">NEXT <img src="'.get_stylesheet_directory_uri().'/imgs/arrow_right_ai.svg" alt="Right arrow." ></div></a>';
        }

        if ($page_num >1){
        echo '<div class="page-slider col-md-12"><input type="range" min="1" max="'.$page_num.'" value="'.($current+1).'" class="slider" id="slide-the-pages"></div>';
    }
}


//true false for slide navigation
function subTrue ($fieldName){
    if (get_sub_field($fieldName)){
        return '<i class="fa fa-angle-double-right" aria-hidden="true"></i>';
    }
}

function remove_my_parent_theme_function() {
    remove_filter('the_content', 'wp_bootstrap_first_paragraph');
}
add_action('wp_loaded', 'remove_my_parent_theme_function');


//main menu construction


function makeMenu($parent = 0, $the_class ='main-header') {
         $args = array(
                        'sort_order' => 'asc',
                        'parent' => $parent,
                        'post_type' => 'page',
                        'post_status' => 'publish',
                        'sort_column'  => 'post_date'
                    );
                    $pages = get_pages($args);
                    $number = sizeof($pages);
                    $i = 0;
                    while ($i < $number){
                        echo '<li class="'.$the_class. ' parent'. $parent .'"><a href="'.$pages[$i]->guid.'">'.$pages[$i]->post_title .'</a>';
                        $parent_id = $pages[$i]->ID;
                        if (get_pages(array('child_of'=> $parent_id))) {
                        echo '<ul class="children parent'.$parent_id.'">';
                        makeMenu($parent_id, 'page-item');
                        echo '</ul>';
                    }
                    echo '</li>';
                        $i++;
                    }

                }

function main_slide_title($post_id){
    if(get_field('main_slide_title', $post_id)){
        return get_field('main_slide_title', $post_id);
    }
}



//PUTS has_children INTO THE API
function children_get_post_meta_cb($object, $field_name, $request){
        return get_post_meta($object['id'], $field_name, true);
}
function children_update_post_meta_cb($value, $object, $field_name){
  return update_post_meta($object['id'], $field_name, $value);
}
add_action('rest_api_init', function(){
  register_rest_field('page', 'has_children',
    array(
    'get_callback' => 'children_get_post_meta_cb',
    'update_callback' => 'children_update_post_meta_cb',
    'schema' => null
    )
  );
});

//THIS LETS US SEARCH BY has_children variables
add_filter( 'rest_page_query', function( $args, $request ) {
    $has_children   = $request->get_param( 'has_children' );

    if ( ! empty( $has_children ) ) {
        $args['meta_query'] = array(
            array(
                'key'     => 'has_children',
                'value'   => $has_children,
                'compare' => '=',
            )
        );
    }

    return $args;
}, 10, 2 );



//make ACF let you see custom fields
add_filter( 'acf/settings/remove_wp_meta_box', '__return_false' );


//MODIFY H5P TO GO FULL SCREEN

function h5p_full_img_alter_styles(&$styles, $libraries, $embed_type) {
  $styles[] = (object) array(
    // Path must be relative to wp-content/uploads/h5p or absolute.
    'path' => get_stylesheet_directory_uri() . '/custom-h5p.css',
    'version' => '?ver=0.1' // Cache buster
  );
}
add_action('h5p_alter_library_styles', 'h5p_full_img_alter_styles', 10, 3);

//Add support for creation of static menu file
function histology_create_menu($post_id) {
    global $wpdb;
    $results = $wpdb->get_results( "SELECT `ID`, `post_title`, `post_parent`, `post_name`, `post_date` FROM {$wpdb->prefix}posts WHERE post_type='page' and post_status = 'publish' ORDER BY post_date ASC", ARRAY_A );
    foreach($results as &$result){
        $result['guid'] = get_permalink($result['ID']);//make better links
        if (test_for_children($result['ID']) === 'true'){
            $result['has_children'] = 'true';
        } else {
            $result['has_children'] = 'false';
        }
    }
    file_put_contents(get_stylesheet_directory() . '/results.json', json_encode($results));

}
add_action('save_post', 'histology_create_menu');


function test_for_children($post_id){
    $args = array(
        'post_parent' => $post_id,
        'post_type'   => 'page', 
        'numberposts' => 1,
    );
    $children = get_children( $args );
    if(count($children)>0){
        return 'true';
    } else {
        return 'false';
    }
}


//new minor index page list maker

function make_nav_list(){
    global $post;
        wp_list_pages(array(
        'child_of' => $post->ID,
        'title_li' => '',
        'sort_column' => 'post_date',
    ));
}

function randomHomeBackground(){
  $post_id = get_option('page_on_front');
  $rows = get_field('background', $post_id ); // get all the rows
    if($rows){
        $rand_row = $rows[ array_rand( $rows ) ]; // get a random row
        $rand_row_image = $rand_row['background_image' ]; // get the sub field value 
        return $rand_row_image;
    }
}

add_filter('acf/settings/remove_wp_meta_box', '__return_false');


//ADD SEARCH TO NAV

function add_last_nav_item($items) {
  return $items .= '<li><form class="navbar-form navbar-right" role="search" method="get" id="searchform" action="' . home_url( '/' ) . '"><div class="form-group"><input name="s" id="s" type="text" class="search-query form-control" autocomplete="off" placeholder="Search"></div></form></li>';
}
add_filter('wp_nav_menu_items','add_last_nav_item');



//ADD return to index for quizzes
function extra_quiz_nav($content) {
 global $post;
  $html = '<p><a class="btn btn-primary quiz-index" href="'. get_site_url() .'/quizzes/">Return to the quiz menu</a></p>';
  if ($post->post_parent === 21265) {
    return $content . $html;
  } else {
    return $content;
  }
}

add_filter( 'the_content', 'extra_quiz_nav' );


//add new item from sorter ajax

// register the ajax action for authenticated users
add_action('wp_ajax_hist_sorter_new_item', 'hist_sorter_new_item_callback');

function hist_sorter_new_item_callback(){
    $parent_id = $_REQUEST['parent_id'];
    $post_name = $_REQUEST['post_name'];
    $args = array(
        'post_type' => 'page',
        'post_parent' => $parent_id,
        'post_title' => $post_name,
        'page_template' => 'page-test-big.php',
    );
    $new_item_id = wp_insert_post($args);
    if(!is_wp_error($new_item_id)){
      //the post is valid
        $redirect_url = home_url() . "/wp-admin/post.php?post={$new_item_id}&action=edit";
        // write_log($redirect_url);
        // wp_redirect($redirect_url); 
        // exit;
        echo $redirect_url; 
        exit();   
    }else{
      //there was an error in the post insertion, 
      echo $new_item_id->get_error_message();
    }   
}


add_action('wp_ajax_hist_sorter_update_sort', 'hist_sorter_update_sort');
function hist_sorter_update_sort(){
     $post_ids = $_REQUEST['post_ids'];
     $post_dates = $_REQUEST['post_dates'];
     foreach ($post_ids as $key => $post) {
        $post_id = $post;
        $new_date = $post_dates[$key];
        $args = array(
            'ID' => $post_id,
            'post_date' => $new_date
        );
        wp_update_post($args);
        $exit;
         // code...
     }

}

//adds link to sorter page
add_action('wp_head', 'hist_add_admin_sorter_button');

function hist_add_admin_sorter_button(){
    if(current_user_can('administrator')){
        $args = array(
        'post_type'  => 'page', 
        'posts_per_page' => 1,
        'meta_query' => array( 
            array(
                'key'   => '_wp_page_template', 
                'value' => 'page-sorter.php'
            )
        )
    );
    $sorter_query = new WP_Query($args);
    if ( $sorter_query->have_posts() ) {
        while ( $sorter_query->have_posts() ) {
            $sorter_query->the_post();
            $link =  get_the_permalink();
            echo "<a class='sorter-button' href='{$link}'>Sorter</a>";
        }
    } else {
        '';
    }
    }        
}


//LOGGER -- like frogger but more useful

if ( ! function_exists('write_log')) {
   function write_log ( $log )  {
      if ( is_array( $log ) || is_object( $log ) ) {
         error_log( print_r( $log, true ) );
      } else {
         error_log( $log );
      }
   }
}
